<?php

namespace App\Filament\Widgets;

use App\Models\Radio;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Mokhosh\FilamentRating\Columns\RatingColumn;

class TopEmisorasMonitor extends BaseWidget
{
    protected static ?int $sort = 6;
    protected int | string | array $columnSpan = 'full';
    protected static ?int $refreshInterval = 15; // Actualizar cada 15 segundos

    public function table(Table $table): Table
    {
        return $table
            ->heading('Emisoras Populares')
            ->description('Las emisoras mejor valoradas por los usuarios')
            ->query(
                Radio::query()
                    ->where('isActive', true)
                    ->orderByDesc('rating')
                    ->orderByDesc('isFeatured')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\ImageColumn::make('img')
                    ->label('Logo')
                    ->circular(),
                Tables\Columns\TextColumn::make('bitrate')
                    ->label('Frecuencia'),
                Tables\Columns\TextColumn::make('tags')
                    ->label('Géneros')
                    ->limit(30),
                RatingColumn::make('rating')
                    ->label('Valoración')
                    ->stars(5),
                Tables\Columns\IconColumn::make('isFeatured')
                    ->label('Destacada')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-star')
                    ->trueColor('warning')
                    ->falseColor('gray'),
            ])
            ->actions([
                Tables\Actions\Action::make('viewRadio')
                    ->label('Ver')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Radio $record): string => '/admin/resources/radios/'.$record->id.'/edit'),
                Tables\Actions\Action::make('toggleFeatured')
                    ->label(fn (Radio $record): string => $record->isFeatured ? 'Quitar destacado' : 'Destacar')
                    ->icon('heroicon-o-star')
                    ->color(fn (Radio $record): string => $record->isFeatured ? 'warning' : 'gray')
                    ->action(function (Radio $record) {
                        $record->isFeatured = !$record->isFeatured;
                        $record->save();
                    }),
            ])
            ->emptyStateHeading('No hay emisoras populares')
            ->emptyStateDescription('Las emisoras con mejor valoración aparecerán aquí.')
            ->emptyStateIcon('heroicon-o-star');
    }
}
