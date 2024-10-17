<?php
// codigo original que funciona
namespace App\Filament\Widgets;

use Filament\Widgets\BarChartWidget;
use App\Models\Radio;
use App\Models\Visita;
use Illuminate\Support\Facades\DB;

class TopEmisorasMonitor extends BarChartWidget
{
    protected static ?string $heading = 'Emisoras Más Escuchadas';
    protected static ?int $sort = 3; // Colocar encima, orden más bajo

    protected static ?int $refreshInterval = 15; // Actualizar cada 15 segundos

    protected int|string|array $columnSpan = 'Small';

    // Obtener los datos para el gráfico
    protected function getData(): array
    {
        $startDate = now()->subDays(6)->startOfDay();
        $endDate = now()->endOfDay();

        $visitas = Visita::select('radio_id', DB::raw('count(*) as total'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('radio_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

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

    // Opciones del gráfico
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
            ],
        ];
    }

    // Método para borrar los datos
    public function borrarDatos()
    {
        Visita::truncate(); // Borrar todos los registros de visitas

        // Emitir un evento para recargar el gráfico
        $this->emit('refreshChart');
    }

    // Listener para actualizar el gráfico en tiempo real
    protected $listeners = ['refreshChart' => 'getData'];
}

