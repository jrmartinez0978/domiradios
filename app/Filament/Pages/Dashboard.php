<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?string $title = 'Panel de Control';
    protected static ?int $navigationSort = -2;

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\StatsOverviewWidget::class,
            \App\Filament\Widgets\TopEmisorasMonitor::class,
            \App\Filament\Widgets\LatestSubmittedRadiosWidget::class,
            \App\Filament\Widgets\RadiosBySourceChart::class,
            \App\Filament\Widgets\RadiosByRatingChart::class,
            \App\Filament\Widgets\TopGenresWidget::class,
            \App\Filament\Widgets\OfflineRadiosWidget::class,
            \App\Filament\Widgets\TopListenedRadiosWidget::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return 12;
    }
}
