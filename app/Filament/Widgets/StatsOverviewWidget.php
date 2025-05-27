<?php

namespace App\Filament\Widgets;

use App\Models\Radio;
use App\Models\Genre;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $totalRadios = Radio::count();
        $activeRadios = Radio::where('isActive', true)->count();
        $featuredRadios = Radio::where('isFeatured', true)->count();
        $totalGenres = Genre::count();

        return [
            Stat::make('Total Emisoras', $totalRadios)
                ->description('Emisoras en la plataforma')
                ->descriptionIcon('heroicon-o-radio')
                ->color('gray')
                ->chart([0, $totalRadios/2, $totalRadios]),

            Stat::make('Emisoras Activas', $activeRadios)
                ->description(round(($activeRadios / max(1, $totalRadios) * 100), 1) . '% del total')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success')
                ->chart([0, $activeRadios/2, $activeRadios]),

            Stat::make('Emisoras Destacadas', $featuredRadios)
                ->description(round(($featuredRadios / max(1, $totalRadios) * 100), 1) . '% del total')
                ->descriptionIcon('heroicon-o-star')
                ->color('warning')
                ->chart([0, $featuredRadios/2, $featuredRadios]),
                
            Stat::make('Ciudades/Géneros', $totalGenres)
                ->description('Categorías disponibles')
                ->descriptionIcon('heroicon-o-map')
                ->color('primary')
                ->chart([0, $totalGenres/2, $totalGenres]),
        ];
    }
}
