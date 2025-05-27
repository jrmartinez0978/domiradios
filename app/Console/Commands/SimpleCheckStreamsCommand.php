<?php

namespace App\Console\Commands;

use App\Models\Radio;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SimpleCheckStreamsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'radio:check-simple';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica los streams simplemente por código HTTP 200';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Iniciando verificación simple de streams de radio...');
        
        // Obtener todas las radios activas
        $radios = Radio::where('isActive', true)->get();
        $totalRadios = $radios->count();
        
        $this->info("Verificando {$totalRadios} streams de radio...");
        $bar = $this->output->createProgressBar($totalRadios);
        $bar->start();
        
        $successCount = 0;
        $errorCount = 0;
        
        foreach ($radios as $radio) {
            $url = $radio->link_radio;
            
            // Saltear si no hay URL
            if (empty($url)) {
                $bar->advance();
                continue;
            }
            
            $isActive = false;
            
            try {
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
                    if ($httpCode === 200) {
                        $isActive = true;
                    }
                }
            } catch (\Exception $e) {
                $this->error("\nError al verificar {$url}: " . $e->getMessage());
                $isActive = false;
            }
            
            // Actualizar el estado de la radio
            $radio->is_stream_active = $isActive;
            $radio->last_stream_check = Carbon::now();
            
            if ($isActive) {
                $successCount++;
                $this->info("\nStream activo: {$url}");
            } else {
                $errorCount++;
                $radio->last_stream_failure = Carbon::now();
                $this->error("\nStream inactivo: {$url}");
            }
            
            $radio->save();
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine(2);
        
        $this->info("Verificación completada:");
        $this->info("- Total de emisoras: {$totalRadios}");
        $this->info("- Streams activos: {$successCount}");
        $this->info("- Streams inactivos: {$errorCount}");
        
        return 0;
    }
}
