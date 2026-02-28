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

            // Verificación real de WebSocket via handshake
            if (str_starts_with($url, 'wss://') || str_starts_with($url, 'ws://')) {
                $isActive = $this->checkWebSocket($url);
                $radio->is_stream_active = $isActive;
                $radio->last_stream_check = Carbon::now();
                if ($isActive) {
                    $radio->stream_failure_count = 0;
                    $successCount++;
                    $this->info("\nStream WebSocket activo: {$url}");
                } else {
                    $radio->stream_failure_count = $radio->stream_failure_count + 1;
                    $radio->last_stream_failure = Carbon::now();
                    $errorCount++;
                    $this->error("\nStream WebSocket inactivo: {$url} (Fallos: {$radio->stream_failure_count})");
                }
                $radio->save();
                $bar->advance();
                continue;
            }

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
     * Verifica un stream WebSocket haciendo el handshake HTTP Upgrade.
     * Si el servidor responde 101 Switching Protocols, el stream está activo.
     */
    private function checkWebSocket(string $url): bool
    {
        try {
            $httpUrl = str_replace(['wss://', 'ws://'], ['https://', 'http://'], $url);
            $parsed = parse_url($httpUrl);
            $host = $parsed['host'] ?? '';
            $secure = str_starts_with($url, 'wss://');
            $port = $parsed['port'] ?? ($secure ? 443 : 80);
            $path = $parsed['path'] ?? '/';

            $key = base64_encode(random_bytes(16));
            $prefix = $secure ? 'ssl' : 'tcp';
            $context = stream_context_create(['ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ]]);

            $socket = @stream_socket_client(
                "{$prefix}://{$host}:{$port}",
                $errno, $errstr, 5,
                STREAM_CLIENT_CONNECT, $context
            );

            if (!$socket) {
                return false;
            }

            $request = "GET {$path} HTTP/1.1\r\n" .
                "Host: {$host}:{$port}\r\n" .
                "Upgrade: websocket\r\n" .
                "Connection: Upgrade\r\n" .
                "Sec-WebSocket-Key: {$key}\r\n" .
                "Sec-WebSocket-Version: 13\r\n" .
                "Origin: https://domiradios.com.do\r\n" .
                "\r\n";

            fwrite($socket, $request);
            stream_set_timeout($socket, 5);

            $response = '';
            while ($line = fgets($socket)) {
                $response .= $line;
                if (trim($line) === '') break;
            }
            fclose($socket);

            return str_contains($response, '101');
        } catch (\Exception $e) {
            return false;
        }
    }
}
