<?php

namespace App\Filament\Widgets;

use App\Models\Radio;
use Carbon\Carbon;
use Filament\Widgets\Widget;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\HtmlString;

class OfflineRadiosWidget extends Widget
{
    protected static string $view = 'filament.widgets.offline-radios-widget';
    
    // Posicionar este widget en último lugar
    protected static ?int $sort = 999;
    
    // Asegurar que ocupe todo el ancho
    protected int|string|array $columnSpan = 'full';
    
    // Sin límite de altura para mostrar más contenido
    protected static ?string $maxHeight = null;
    
    protected static ?string $heading = 'Emisoras con Streams Caídos';
    
    public function getOfflineRadios()
    {
        // Considerar como caídas solo las emisoras con más de 2 fallos
        // para reducir falsos positivos
        $query = Radio::where('is_stream_active', false)
                   ->where('stream_failure_count', '>', 2);
        
        // Intentar filtrar por estado activo si el campo existe
        try {
            if (Schema::hasColumn('radios', 'active')) {
                $query->where('active', true);
            } elseif (Schema::hasColumn('radios', 'activo')) {
                $query->where('activo', true);
            }
        } catch (\Exception $e) {
            // Simplemente continuar si hay un error
        }
        
        return $query->orderByDesc('last_stream_failure')
            ->limit(15)
            ->get();
    }
    
    public function getTotalOfflineRadios()
    {
        // Usamos la misma lógica que en getOfflineRadios() para consistencia
        // Considerar como caídas solo las emisoras con más de 2 fallos
        $query = Radio::where('is_stream_active', false)
                  ->where('stream_failure_count', '>', 2);
        
        // Intentar filtrar por estado activo si el campo existe
        try {
            if (Schema::hasColumn('radios', 'active')) {
                $query->where('active', true);
            } elseif (Schema::hasColumn('radios', 'activo')) {
                $query->where('activo', true);
            }
        } catch (\Exception $e) {
            // Simplemente continuar si hay un error
        }
        
        return $query->count();
    }
    
    public function formatLastChecked($date)
    {
        if (!$date) return new HtmlString('<span class="text-gray-500">Nunca</span>');
        
        $carbon = Carbon::parse($date);
        $diffForHumans = $carbon->diffForHumans();
        
        return new HtmlString(
            '<span title="' . $carbon->format('d/m/Y H:i:s') . '">' . $diffForHumans . '</span>'
        );
    }
    
    public function getFailureCountHtml($count)
    {
        $colorClass = 'text-gray-500';
        
        if ($count > 10) {
            $colorClass = 'text-red-500 font-bold';
        } elseif ($count > 5) {
            $colorClass = 'text-orange-500 font-medium';
        } elseif ($count > 0) {
            $colorClass = 'text-yellow-500';
        }
        
        return new HtmlString(
            '<span class="' . $colorClass . '">' . $count . '</span>'
        );
    }
    
    /**
     * Restablece el estado de un stream individual
     */
    public function resetStreamStatus($radioId)
    {
        $radio = Radio::find($radioId);
        
        if ($radio) {
            $radio->is_stream_active = true;
            $radio->stream_failure_count = 0;
            $radio->last_stream_failure = null;
            $radio->save();
            
            Notification::make()
                ->title('Stream restablecido')
                ->body('El estado del stream se ha restablecido correctamente.')
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Error')
                ->body('No se pudo encontrar la emisora especificada.')
                ->danger()
                ->send();
        }
        
        // Recargar el widget
        $this->dispatch('refresh');
    }
    
    /**
     * Restablece el estado de todos los streams inactivos
     */
    public function resetAllStreams()
    {
        $radios = Radio::where('is_stream_active', false)->get();
        
        $count = 0;
        foreach ($radios as $radio) {
            $radio->is_stream_active = true;
            $radio->stream_failure_count = 0;
            $radio->last_stream_failure = null;
            $radio->save();
            $count++;
        }
        
        Notification::make()
            ->title('Streams restablecidos')
            ->body("Se han restablecido {$count} streams correctamente.")
            ->success()
            ->send();
            
        // Recargar el widget
        $this->dispatch('refresh');
    }
    
    public function artisan()
    {
        try {
            // Ejecutar el comando directamente sin usar proceso en segundo plano
            Artisan::call('radio:check-streams');
            
            Notification::make()
                ->title('Verificación completada')
                ->body('La verificación de streams ha finalizado.')
                ->success()
                ->send();
                
            $this->dispatch('refresh');
            
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('No se pudo realizar la verificación: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
    
    /**
     * Determina qué comando de Python usar (python3 o python)
     */
    private function getPythonCommand()
    {
        // Intentar primero con 'python3'
        exec('python3 --version 2>&1', $output, $returnVal);
        if ($returnVal === 0) {
            return 'python3';
        }
        
        // Intentar con 'python'
        exec('python --version 2>&1', $output, $returnVal);
        if ($returnVal === 0) {
            return 'python';
        }
        
        // Si llegamos aquí, Python no está instalado o no está en el PATH
        return null;
    }
    
    /**
     * Verifica un stream individual directamente con el comando artisan
     */
    public function checkIndividualStream($radioId)
    {
        try {
            $radio = Radio::find($radioId);
            
            if (!$radio) {
                Notification::make()
                    ->title('Error')
                    ->body('No se pudo encontrar la emisora especificada.')
                    ->danger()
                    ->send();
                return;
            }
            
            $url = $radio->link_radio;
            if (empty($url)) {
                Notification::make()
                    ->title('Error')
                    ->body('La emisora no tiene una URL de stream configurada.')
                    ->danger()
                    ->send();
                return;
            }
            
            // Ejecutar una verificación simple con curl directamente en PHP
            $isActive = false;
            
            // Verificación con HEAD
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_NOBODY, true); // Solo encabezado
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
            
            curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            // Si el código es 200, el stream está activo
            if ($httpCode === 200) {
                $isActive = true;
            } else {
                // Si HEAD no funciona, intentar con GET
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
                
                curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                
                // Si el código es 200, el stream está activo
                $isActive = ($httpCode === 200);
            }
            
            // Actualizar el estado del radio
            $radio->is_stream_active = $isActive;
            $radio->last_stream_check = now();
            
            if ($isActive) {
                $radio->stream_failure_count = 0;
                Notification::make()
                    ->title('Stream activo')
                    ->body("El stream está activo.")
                    ->success()
                    ->send();
            } else {
                $radio->last_stream_failure = now();
                Notification::make()
                    ->title('Stream inactivo')
                    ->body("El stream está inactivo.")
                    ->warning()
                    ->send();
            }
            
            $radio->save();
            
            // Recargar el widget
            $this->dispatch('refresh');
            
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('Error al verificar el stream: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
}
