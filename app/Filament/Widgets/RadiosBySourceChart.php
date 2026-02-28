<?php

namespace App\Filament\Widgets;

use App\Models\Radio;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class RadiosBySourceChart extends ChartWidget
{
    protected static ?string $heading = 'Emisoras por Origen';
    protected static ?int $sort = 4;
    protected static ?string $pollingInterval = '60s';
    protected int | string | array $columnSpan = 6;

    protected function getData(): array
    {
        // Cachear por 5 minutos para mejorar rendimiento
        $sources = Cache::remember('widget_radios_by_source', 300, function() {
            return Radio::selectRaw('source_radio, COUNT(*) as count')
                ->groupBy('source_radio')
                ->orderBy('count', 'desc')
                ->pluck('count', 'source_radio')
                ->toArray();
        });

        if (empty($sources)) {
            return [
                'datasets' => [[
                    'label' => 'Emisoras',
                    'data' => [0],
                    'backgroundColor' => ['rgba(200, 200, 200, 0.7)'],
                ]],
                'labels' => ['Sin datos'],
            ];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Emisoras',
                    'data' => array_values($sources),
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)',
                    ],
                ],
            ],
            'labels' => array_keys($sources),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => true,
            'aspectRatio' => 1.5,
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
        ];
    }

    public static function canView(): bool
    {
        return Radio::count() > 0;
    }
}
