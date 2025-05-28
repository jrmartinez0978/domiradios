<?php

namespace App\Filament\Widgets;

use App\Models\Visita;
use Filament\Widgets\Widget;

class TopBotonReiniciarMonitor extends Widget
{
    protected static string $view = 'filament.widgets.top-boton-reiniciar-monitor';

    protected static ?int $sort = 8; // Para colocar el widget encima

    public function resetVisitas()
    {
        Visita::truncate();
        // No necesitamos emitir eventos ni mostrar notificaciones
    }
}







