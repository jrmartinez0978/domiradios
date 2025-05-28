<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class OfflineRadiosWidget extends Widget
{
    protected static string $view = 'filament.widgets.offline-radios-widget';
    
    // Posicionar este widget en último lugar
    protected static ?int $sort = 2;
    
    // Asegurar que ocupe todo el ancho
    protected int|string|array $columnSpan = 'full';
    
    protected static ?string $heading = 'Emisoras con Streams Caídos';
    
    protected function getViewData(): array
    {
        return [
            'component' => 'stream-status-manager',
        ];
    }
}
