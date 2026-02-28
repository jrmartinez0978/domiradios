<?php

namespace App\Console\Commands;

use App\Models\Radio;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class CheckRadioStreamsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'radio:check-streams';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica el estado de los streams de radio por código HTTP 200';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Iniciando verificación de streams de radio...');
        
        // Obtener todas las emisoras (intentar filtrar por activas si existe el campo)
        try {
            // Intentar primero con 'active'
            if (Schema::hasColumn('radios', 'active')) {
                $radios = Radio::where('active', true)->get();
                $this->info("Filtrando por campo 'active'.");
            }
            // Intentar con 'isActive' si 'active' no existe
            elseif (Schema::hasColumn('radios', 'isActive')) {
                $radios = Radio::where('isActive', true)->get();
                $this->info("Filtrando por campo 'isActive'.");
            }
            else {
                $this->info("No se pudo filtrar por estado activo. Verificando todas las emisoras.");
                $radios = Radio::all();
            }
        } catch (\Exception $e) {
            $this->error("Error al filtrar emisoras: {$e->getMessage()}");
            $radios = Radio::all();
        }
        
        $totalRadios = $radios->count();
        $this->info("Verificando {$totalRadios} streams de radio...");
        
        // Crear la barra de progreso
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
            
            // Verificar el stream 
            $isActive = $this->checkUrl($url);
            
            // Actualizar el estado de la radio
            $radio->is_stream_active = $isActive;
            $radio->last_stream_check = Carbon::now();

            if ($isActive) {
                $successCount++;
                // Resetear contador de fallos cuando el stream está activo
                $radio->stream_failure_count = 0;
                $this->info("\nStream activo: {$url}");
            } else {
                $errorCount++;
                // Incrementar contador de fallos cuando el stream está inactivo
                $radio->stream_failure_count = $radio->stream_failure_count + 1;
                $radio->last_stream_failure = Carbon::now();
                $this->error("\nStream inactivo: {$url} (Fallos: {$radio->stream_failure_count})");
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
    
    /**
     * Verifica si una URL está activa comprobando si devuelve código HTTP 200
     * 
     * @param string $url
     * @return bool
     */
    private function checkUrl($url)
    {
        try {
            // Verificación con HEAD
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_NOBODY, true); // Solo obtenemos el encabezado
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
            
            curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            // Si el código es 200, el stream está activo
            if ($httpCode === 200) {
                return true;
            }
            
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
            return $httpCode === 200;
            
        } catch (\Exception $e) {
            $this->error("Error al verificar URL {$url}: {$e->getMessage()}");
            return false;
        }
    }
}
