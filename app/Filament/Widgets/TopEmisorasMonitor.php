<?php

namespace App\Filament\Widgets;

use Filament\Widgets\LineChartWidget;
use App\Models\Radio;
use App\Models\Visita;
use Illuminate\Support\Facades\DB;

class TopEmisorasMonitor extends LineChartWidget
{
    protected static ?string $heading = 'Top 10 Emisoras Más Visitadas';

    protected static ?int $refreshInterval = 60; // Actualizar cada 60 segundos

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        // Obtener el rango de fechas para los últimos 7 días
        $startDate = now()->subDays(6)->startOfDay();
        $endDate = now()->endOfDay();

        // Obtener las visitas por emisora en el rango de fechas
        $visitas = Visita::select('radio_id', DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('radio_id', 'date')
            ->get();

        // Obtener los IDs de las 10 emisoras más visitadas en total en el rango de fechas
        $topEmisorasIds = Visita::select('radio_id', DB::raw('count(*) as total'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('radio_id')
            ->orderByDesc('total')
            ->limit(10)
            ->pluck('radio_id')
            ->toArray();

        // Obtener los nombres de las emisoras
        $emisoras = Radio::whereIn('id', $topEmisorasIds)->pluck('name', 'id')->toArray();

        // Inicializar el arreglo de datos
        $datasets = [];
        $labels = [];

        // Generar las etiquetas de fechas para los últimos 7 días
        $dates = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
            $labels[] = $date->format('d M');
        }

        // Crear un arreglo para almacenar las visitas por emisora y fecha
        $dataByEmisora = [];
        foreach ($topEmisorasIds as $radioId) {
            $dataByEmisora[$radioId] = array_fill_keys($dates, 0);
        }

        // Rellenar el arreglo con los datos de visitas
        foreach ($visitas as $visita) {
            if (in_array($visita->radio_id, $topEmisorasIds)) {
                $dataByEmisora[$visita->radio_id][$visita->date] = $visita->total;
            }
        }

        // Convertir los datos en datasets para el gráfico
        foreach ($dataByEmisora as $radioId => $visitasPorFecha) {
            $datasets[] = [
                'label' => $emisoras[$radioId] ?? 'Desconocido',
                'data' => array_values($visitasPorFecha),
                'borderColor' => $this->getRandomColor(),
                'fill' => false,
            ];
        }

        return [
            'datasets' => $datasets,
            'labels' => $labels,
        ];
    }

    // Método para obtener un color aleatorio para el dataset
    private function getRandomColor()
    {
        $colors = [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
            '#9966FF', '#FF9F40', '#E7E9ED', '#8A2BE2',
            '#00CED1', '#DC143C', '#FFD700', '#00FA9A',
        ];
        return $colors[array_rand($colors)];
    }
}
