<?php

namespace App\Livewire;

use Carbon\Carbon;

use Livewire\Component;
use App\Models\Radio;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Artisan;

class StreamStatusManager extends Component
{
    public function formatDateForHumans($date)
    {
        if (!$date) {
            return 'Nunca';
        }

        // Asegurar que $date sea una instancia de Carbon
        if (is_string($date)) {
            try {
                $date = Carbon::parse($date);
            } catch (\Exception $e) {
                return 'Fecha inválida'; // Manejar error de parseo
            }
        }

        if (!$date instanceof Carbon) {
            return 'Fecha inválida'; // Si aún no es Carbon después del intento de parseo
        }

        return $date->diffForHumans();
    }

    // El resto de tu clase StreamStatusManager continúa aquí...
    public $offlineRadios;
    public $totalOfflineRadios;

    public function mount()
    {
        $this->loadOfflineRadios();
    }

    public function loadOfflineRadios()
    {
        $this->offlineRadios = Radio::where('is_stream_active', false)
            ->where('stream_failure_count', '>', 2)
            ->orderByDesc('last_stream_failure')
            ->limit(15)
            ->get();
            
        $this->totalOfflineRadios = Radio::where('is_stream_active', false)
            ->where('stream_failure_count', '>', 2)
            ->count();
    }

    public function checkIndividualStream($radioId)
    {
        $radio = Radio::find($radioId);
        
        if (!$radio) {
            $this->dispatch('notify', 
                type: 'error',
                message: 'No se pudo encontrar la emisora especificada.'
            );
            return;
        }
        
        try {
            $url = $radio->link_radio;
            
            // Verificación con cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
            
            curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            $isActive = ($httpCode === 200);
            
            if (!$isActive) {
                // Si HEAD falla, intentar con GET
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
                
                curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                
                $isActive = ($httpCode === 200);
            }
            
            // Actualizar el estado del radio
            $radio->is_stream_active = $isActive;
            $radio->last_stream_check = now();
            
            if ($isActive) {
                $radio->stream_failure_count = 0;
                $message = 'El stream está activo.';
                $type = 'success';
            } else {
                $radio->last_stream_failure = now();
                $message = 'El stream sigue inactivo.';
                $type = 'warning';
            }
            
            $radio->save();
            $this->loadOfflineRadios();
            
            $this->dispatch('notify', 
                type: $type,
                message: $message
            );
            
        } catch (\Exception $e) {
            $this->dispatch('notify', 
                type: 'error',
                message: 'Error al verificar el stream: ' . $e->getMessage()
            );
        }
    }
    
    public function resetStreamStatus($radioId)
    {
        $radio = Radio::find($radioId);
        
        if ($radio) {
            $radio->is_stream_active = true;
            $radio->stream_failure_count = 0;
            $radio->last_stream_check = now();
            $radio->save();
            
            $this->loadOfflineRadios();
            
            $this->dispatch('notify', 
                type: 'success',
                message: 'El estado del stream ha sido restablecido.'
            );
        }
    }
    
    public function resetAllStreams()
    {
        $count = Radio::where('is_stream_active', false)
            ->where('stream_failure_count', '>', 2)
            ->update([
                'is_stream_active' => true,
                'stream_failure_count' => 0,
                'last_stream_check' => now()
            ]);
            
        $this->loadOfflineRadios();
        
        $this->dispatch('notify', 
            type: 'success',
            message: "Se han restablecido {$count} streams correctamente."
        );
    }
    
    public function checkAllStreams()
    {
        try {
            Artisan::call('radio:check-streams');
            $this->loadOfflineRadios();
            
            $this->dispatch('notify', 
                type: 'success',
                message: 'La verificación de streams ha finalizado.'
            );
        } catch (\Exception $e) {
            $this->dispatch('notify', 
                type: 'error',
                message: 'Error al verificar los streams: ' . $e->getMessage()
            );
        }
    }
    
    public function render()
    {
        return view('livewire.stream-status-manager');
    }
}
