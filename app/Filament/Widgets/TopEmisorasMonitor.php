<?php

namespace App\Filament\Widgets;

use Filament\Widgets\BarChartWidget;
use App\Models\Radio;
use App\Models\Visita;
use Illuminate\Support\Facades\DB;

class TopEmisorasMonitor extends BarChartWidget
{
    protected static ?string $heading = 'Emisoras Mas Ecuchadas';

    protected static ?int $refreshInterval = 60; // Actualizar cada 60 segundos

    protected int|string|array $columnSpan = 'Small';

    protected function getData(): array
    {
        // Obtener el rango de fechas para los últimos 7 días
        $startDate = now()->subDays(6)->startOfDay();
        $endDate = now()->endOfDay();

        // Obtener las visitas totales por emisora en el rango de fechas
        $visitas = Visita::select('radio_id', DB::raw('count(*) as total'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('radio_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Obtener los nombres de las emisoras, total de visitas y asignar colores
        $emisoras = [];
        $totales = [];
        $backgroundColors = [];
        $colores = [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
            '#9966FF', '#FF9F40', '#E7E9ED', '#8A2BE2',
            '#00CED1', '#DC143C',
        ];
        $contador = 1;

        foreach ($visitas as $index => $visita) {
            $radio = Radio::find($visita->radio_id);
            $emisoras[] = "{$contador}. " . ($radio->name ?? 'Desconocido');
            $totales[] = $visita->total;
            $backgroundColors[] = $colores[$index % count($colores)];
            $contador++;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Número de Visitas',
                    'data' => $totales,
                    'backgroundColor' => $backgroundColors,
                ],
            ],
            'labels' => $emisoras,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y', // Gráfico de barras horizontales
            'scales' => [
                'x' => [
                    'beginAtZero' => true,
                ],
                'y' => [
                    'barThickness' => 7, // Ajusta este valor para el grosor de las barras
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
                'title' => [
                    'display' => true,
                    'text' => 'Top 10 En Los Últimos 7 Días',
                    'font' => [
                        'size' => 16,
                    ],
                ],
                // Configuración de DataLabels (si la estás utilizando)
                'tooltip' => [
                    'enabled' => true,
                ],
                'datalabels' => [
                    'anchor' => 'end',
                    'align' => 'right',
                    'color' => '#555',
                    'font' => [
                        'weight' => 'bold',
                    ],
                    'formatter' => function ($value, $context) {
                        return $value;
                    },
                ],
            ],
        ];
    }

    protected function getChartHeight(): ?int
    {
        return 400; // Ajusta este valor para cambiar la altura del gráfico
    }

    // Incluimos el método para registrar el plugin DataLabels si es necesario
    protected function getExtraConfig(): array
    {
        return [
            'plugins' => [
                'datalabels',
            ],
        ];
    }
}


