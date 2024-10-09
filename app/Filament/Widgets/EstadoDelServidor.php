<?php

namespace App\Filament\Widgets;

use Filament\Widgets\LineChartWidget;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class EstadoDelServidor extends LineChartWidget
{
    protected static ?string $heading = 'Estado del Servidor';

    protected static ?int $refreshInterval = 60; // Refrescar cada 60 segundos

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        // Obtener métricas del servidor
        $cpuUsage = $this->getCpuUsage();
        $memoryUsage = $this->getMemoryUsage();
        $diskUsage = $this->getDiskUsage();

        // Guardar datos en caché para mantener historial
        $this->storeMetricsInCache('cpu_usage', $cpuUsage);
        $this->storeMetricsInCache('memory_usage', $memoryUsage);
        $this->storeMetricsInCache('disk_usage', $diskUsage);

        // Obtener historial de métricas
        $cpuData = Cache::get('cpu_usage_history', []);
        $memoryData = Cache::get('memory_usage_history', []);
        $diskData = Cache::get('disk_usage_history', []);

        // Generar etiquetas de tiempo
        $labels = Cache::get('metrics_timestamps', []);

        return [
            'datasets' => [
                [
                    'label' => 'Uso de CPU (%)',
                    'data' => $cpuData,
                    'borderColor' => '#FF6384',
                    'fill' => false,
                ],
                [
                    'label' => 'Uso de Memoria (%)',
                    'data' => $memoryData,
                    'borderColor' => '#36A2EB',
                    'fill' => false,
                ],
                [
                    'label' => 'Uso de Disco (%)',
                    'data' => $diskData,
                    'borderColor' => '#FFCE56',
                    'fill' => false,
                ],
            ],
            'labels' => $labels,
        ];
    }

    private function getCpuUsage()
    {
        // Obtener uso de CPU (Linux)
        $load = sys_getloadavg();
        $cpuCores = shell_exec('nproc');
        $cpuUsage = ($load[0] / $cpuCores) * 100;

        return round($cpuUsage, 2);
    }

    private function getMemoryUsage()
    {
        // Obtener uso de memoria (Linux)
        $free = shell_exec('free');
        $free = (string)trim($free);
        $free_arr = explode("\n", $free);
        $mem = explode(" ", $free_arr[1]);
        $mem = array_filter($mem);
        $mem = array_merge($mem);
        $memoryUsage = ($mem[2] / $mem[1]) * 100;

        return round($memoryUsage, 2);
    }

    private function getDiskUsage()
    {
        // Obtener uso de disco
        $diskTotal = disk_total_space("/");
        $diskFree = disk_free_space("/");
        $diskUsed = $diskTotal - $diskFree;
        $diskUsage = ($diskUsed / $diskTotal) * 100;

        return round($diskUsage, 2);
    }

    private function storeMetricsInCache($key, $value)
    {
        $historyKey = $key . '_history';
        $timestampsKey = 'metrics_timestamps';

        // Obtener historial existente o iniciar uno nuevo
        $history = Cache::get($historyKey, []);
        $timestamps = Cache::get($timestampsKey, []);

        // Agregar nuevo valor al historial
        $history[] = $value;
        $timestamps[] = now()->format('H:i:s');

        // Mantener solo los últimos 20 registros
        if (count($history) > 20) {
            array_shift($history);
            array_shift($timestamps);
        }

        // Guardar historial actualizado en caché
        Cache::put($historyKey, $history, now()->addMinutes(10));
        Cache::put($timestampsKey, $timestamps, now()->addMinutes(10));
    }
}

