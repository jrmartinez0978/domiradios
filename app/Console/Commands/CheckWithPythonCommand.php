<?php

namespace App\Console\Commands;

use App\Models\Radio;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckWithPythonCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'radio:check-python';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica los streams de radio usando el script de Python';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Iniciando verificación de streams de radio con Python...');
        
        // Ruta al script de Python
        $pythonScript = base_path('python/check_streams.py');
        
        // Verificar que el script exista
        if (!file_exists($pythonScript)) {
            $this->error('El script de Python no existe en: ' . $pythonScript);
            return 1;
        }
        
        // Verificar que Python esté instalado
        $pythonCommand = $this->getPythonCommand();
        if (!$pythonCommand) {
            $this->error('Python no está disponible en el sistema. Por favor instala Python 3.');
            return 1;
        }
        
        // Instalar dependencias necesarias si no están presentes
        $this->installPythonDependencies($pythonCommand);
        
        // Obtener todas las radios activas
        $radios = Radio::where('isActive', true)->get();
        $totalRadios = $radios->count();
        
        $this->info("Verificando {$totalRadios} streams de radio...");
        $bar = $this->output->createProgressBar($totalRadios);
        $bar->start();
        
        $offlineCount = 0;
        $successCount = 0;
        $errorCount = 0;
        
        foreach ($radios as $radio) {
            $url = $radio->link_radio;
            
            // Saltear si no hay URL
            if (empty($url)) {
                $bar->advance();
                $errorCount++;
                continue;
            }
            
            // Verificar el stream con Python
            $result = $this->checkStream($pythonCommand, $pythonScript, $url);
            
            // Actualizar estado de la radio
            if ($result !== null && isset($result['success']) && $result['success']) {
                $isActive = $result['is_active'] ?? false;
                
                if ($isActive) {
                    // Stream está activo - responde con 200 OK
                    $radio->is_stream_active = true;
                    $radio->stream_failure_count = 0;
                    $radio->last_stream_check = Carbon::now();
                    $successCount++;
                } else {
                    // Stream está caído - no responde con 200 OK
                    $offlineCount++;
                    $radio->is_stream_active = false;
                    $radio->last_stream_failure = Carbon::now();
                    $radio->last_stream_check = Carbon::now();
                    
                    // Loguear información sobre el fallo
                    Log::info("Stream inactivo: {$url} - Razón: " . ($result['message'] ?? 'Desconocida'));
                }
                
                $radio->save();
            } else {
                $errorCount++;
                Log::warning("Error verificando stream: {$url} - " . json_encode($result));
            }
            
            // Pequeña pausa para evitar sobrecarga
            usleep(200000); // 200ms
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine(2);
        
        $this->info("Verificación completada:");
        $this->info("- Total de emisoras: {$totalRadios}");
        $this->info("- Streams activos: {$successCount}");
        $this->info("- Streams inactivos: {$offlineCount}");
        $this->info("- Errores: {$errorCount}");
        
        return 0;
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
            // Verificar que sea Python 3.x
            $versionString = implode("\n", $output);
            if (strpos($versionString, 'Python 3') !== false) {
                return 'python';
            }
        }
        
        // Si llegamos aquí, Python 3 no está instalado o no está en el PATH
        return null;
    }
    
    /**
     * Instala las dependencias de Python necesarias
     */
    private function installPythonDependencies($pythonCommand)
    {
        $this->info('Verificando dependencias de Python...');
        
        // Lista de paquetes necesarios
        $packages = ['requests'];
        
        foreach ($packages as $package) {
            // Verificar si el paquete ya está instalado
            $checkCommand = "{$pythonCommand} -c \"import {$package}\" 2>/dev/null";
            exec($checkCommand, $output, $returnVal);
            
            if ($returnVal !== 0) {
                $this->info("Instalando dependencia: {$package}");
                $installCommand = "{$pythonCommand} -m pip install {$package}";
                exec($installCommand, $output, $returnVal);
                
                if ($returnVal !== 0) {
                    $this->warn("No se pudo instalar {$package}. Es posible que algunas funciones no trabajen correctamente.");
                }
            }
        }
    }
    
    /**
     * Verifica un stream usando el script de Python
     */
    private function checkStream($pythonCommand, $pythonScript, $url)
    {
        // Escapar la URL para la línea de comandos
        $escapedUrl = escapeshellarg($url);
        
        // Construir el comando
        $command = "{$pythonCommand} {$pythonScript} {$escapedUrl}";
        
        // Ejecutar el comando y capturar la salida
        exec($command, $output, $returnVal);
        
        // Procesar la salida
        if ($returnVal === 0 && !empty($output)) {
            $jsonOutput = implode("\n", $output);
            $result = json_decode($jsonOutput, true);
            return $result;
        }
        
        return null;
    }
}
