<?php

namespace App\Filament\Widgets;

use App\Models\Radio;
use App\Filament\Resources\RadioResource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestSubmittedRadiosWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 12;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Últimas Emisoras Enviadas')
            ->description('Emisoras recientemente enviadas por usuarios pendientes de revisión')
            ->query(
                Radio::query()
                    ->where('source_radio', 'user_submitted')
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
            )
            ->columns([
                // Agregar el ID como columna para facilitar la búsqueda
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\ImageColumn::make('img')
                    ->label('Logo')
                    ->circular(),
                Tables\Columns\TextColumn::make('tags')
                    ->label('Géneros')
                    ->limit(30),
                Tables\Columns\IconColumn::make('isActive')
                    ->label('Estado')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->actions([
                // Botón para activar directamente las emisoras
                Tables\Actions\Action::make('activateRadio')
                    ->label('Activar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Radio $record): bool => !$record->isActive)
                    ->action(function (Radio $record) {
                        $record->isActive = true;
                        $record->save();
                    }),
                    
                // Botón para editar la emisora (URL correcta)
                Tables\Actions\Action::make('editRadio')
                    ->label('Editar')
                    ->icon('heroicon-o-pencil')
                    ->url(fn (Radio $record): string => '/panel/radios/' . $record->id . '/edit')
                    ->color('warning')
            ])
            ->emptyStateHeading('No hay nuevas emisoras')
            ->emptyStateDescription('Cuando los usuarios envíen emisoras, aparecerán aquí para su revisión.')
            ->emptyStateIcon('heroicon-o-radio');
    }
}
