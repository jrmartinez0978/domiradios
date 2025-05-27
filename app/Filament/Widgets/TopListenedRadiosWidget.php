<?php

namespace App\Filament\Widgets;

use App\Models\Radio;
use App\Models\Visita;
use Filament\Widgets\BarChartWidget;
use Illuminate\Support\Facades\DB;

class TopListenedRadiosWidget extends BarChartWidget
{
    protected static ?string $heading = 'Emisoras Más Escuchadas';
    protected static ?string $pollingInterval = '15s';
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 'full';
    
    // Personalizar el diseño del widget
    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                    'labels' => [
                        'usePointStyle' => true,
                        'boxWidth' => 7,
                        'font' => [
                            'weight' => 'bold',
                        ],
                    ],
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'grid' => [
                        'display' => true,
                        'drawBorder' => false,
                    ],
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                    'ticks' => [
                        'font' => [
                            'weight' => 'bold',
                        ],
                    ],
                ],
            ],
            'elements' => [
                'bar' => [
                    'borderRadius' => 8,
                    'borderWidth' => 2,
                    'borderColor' => '#ffffff',
                ],
            ],
        ];
    }

    // Obtener los datos para el gráfico
    protected function getData(): array
    {
        $startDate = now()->subDays(6)->startOfDay();
        $endDate = now()->endOfDay();

        // Obtener los datos de visitas desde la base de datos
        $visitas = Visita::select('radio_id', DB::raw('count(*) as total'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('radio_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Mapear los datos para el gráfico
        $labels = [];
        $values = [];
        $colors = [];
        $backgroundColors = [];
        $borderColors = [];

        // Colores del gradiente de la marca
        $brandColors = [
            'rgba(0, 123, 255, 0.7)',  // Azul marca (brand-blue)
            'rgba(220, 53, 69, 0.7)',   // Rojo marca (brand-red)
            'rgba(255, 127, 80, 0.7)',  // Coral
            'rgba(102, 16, 242, 0.7)',   // Morado
            'rgba(255, 193, 7, 0.7)',    // Amarillo
            'rgba(40, 167, 69, 0.7)',    // Verde
            'rgba(111, 66, 193, 0.7)',   // Indigo
            'rgba(23, 162, 184, 0.7)',   // Cian
            'rgba(253, 126, 20, 0.7)',   // Naranja
            'rgba(32, 201, 151, 0.7)'    // Verde azulado
        ];

        // Colores del borde - versiones más oscuras
        $borderBrandColors = [
            'rgba(0, 123, 255, 1)',     // Azul marca
            'rgba(220, 53, 69, 1)',      // Rojo marca
            'rgba(255, 127, 80, 1)',     // Coral
            'rgba(102, 16, 242, 1)',     // Morado
            'rgba(255, 193, 7, 1)',      // Amarillo
            'rgba(40, 167, 69, 1)',      // Verde
            'rgba(111, 66, 193, 1)',     // Indigo
            'rgba(23, 162, 184, 1)',     // Cian
            'rgba(253, 126, 20, 1)',     // Naranja
            'rgba(32, 201, 151, 1)'      // Verde azulado
        ];

        $i = 0;
        foreach ($visitas as $visita) {
            $radio = Radio::find($visita->radio_id);
            if ($radio) {
                $labels[] = $radio->name;
                $values[] = $visita->total;
                $backgroundColors[] = $brandColors[$i % count($brandColors)];
                $borderColors[] = $borderBrandColors[$i % count($borderBrandColors)];
                $i++;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Reproducciones',
                    'data' => $values,
                    'backgroundColor' => $backgroundColors,
                    'borderColor' => $borderColors,
                    'borderWidth' => 2,
                    'hoverOffset' => 4,
                ],
            ],
            'labels' => $labels,
        ];
    }
}
