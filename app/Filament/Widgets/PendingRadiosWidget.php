<?php

namespace App\Filament\Widgets;

use App\Models\Radio;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Widget;

class PendingRadiosWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $pendingRadios = Radio::where('isActive', false)
            ->where('source_radio', 'user_submitted')
            ->count();

        $totalUserRadios = Radio::where('source_radio', 'user_submitted')->count();
        
        $totalActiveRadios = Radio::where('isActive', true)->count();
        
        $featuredRadios = Radio::where('isFeatured', true)->count();

        return [
            Stat::make('Emisoras Pendientes', $pendingRadios)
                ->description('Enviadas por usuarios pendientes de activación')
                ->descriptionIcon('heroicon-o-exclamation-circle')
                ->color('warning')
                ->chart([0, 1, $pendingRadios]),
                // Comentamos la URL hasta confirmar la ruta correcta de Filament
                // ->url(route('filament.resources.radios.index')),

            Stat::make('Emisoras de Usuarios', $totalUserRadios)
                ->description('Total enviadas por usuarios')
                ->descriptionIcon('heroicon-o-users')
                ->color('primary')
                ->chart([0, $totalUserRadios - $pendingRadios, $totalUserRadios]),

            Stat::make('Emisoras Activas', $totalActiveRadios)
                ->description('De un total de ' . Radio::count() . ' emisoras')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success')
                ->chart([0, $totalActiveRadios, Radio::count()]),
        ];
    }
}
