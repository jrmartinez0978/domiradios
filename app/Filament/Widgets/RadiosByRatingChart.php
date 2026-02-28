<?php

namespace App\Filament\Widgets;

use App\Models\Radio;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class RadiosByRatingChart extends ChartWidget
{
    protected static ?string $heading = 'Distribución por Rating';
    protected static ?int $sort = 5;
    protected static ?string $pollingInterval = '60s';
    protected int | string | array $columnSpan = 6;

    protected function getData(): array
    {
        // Cachear por 5 minutos
        $ratings = Cache::remember('widget_radios_by_rating', 300, function() {
            return Radio::selectRaw('rating, COUNT(*) as count')
                ->whereNotNull('rating')
                ->where('rating', '>', 0)
                ->groupBy('rating')
                ->orderBy('rating')
                ->pluck('count', 'rating')
                ->toArray();
        });

        if (empty($ratings)) {
            return [
                'datasets' => [[
                    'label' => 'Número de Emisoras',
                    'data' => [0],
                    'backgroundColor' => 'rgba(200, 200, 200, 0.5)',
                ]],
                'labels' => ['Sin ratings'],
            ];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Número de Emisoras',
                    'data' => array_values($ratings),
                    'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => array_map(fn($r) => $r . ' ⭐', array_keys($ratings)),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => true,
            'aspectRatio' => 1.5,
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ];
    }

    public static function canView(): bool
    {
        return Radio::whereNotNull('rating')->where('rating', '>', 0)->count() > 0;
    }
}
