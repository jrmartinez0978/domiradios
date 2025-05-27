<?php

namespace App\Filament\Widgets;

use App\Models\Radio;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestSubmittedRadiosWidget extends BaseWidget
{
    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = 'full';

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
                Tables\Actions\Action::make('activateRadio')
                    ->label('Activar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Radio $record): bool => !$record->isActive)
                    ->action(function (Radio $record) {
                        $record->isActive = true;
                        $record->save();
                    }),
                Tables\Actions\Action::make('viewRadio')
                    ->label('Ver')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Radio $record): string => '/admin/resources/radios/'.$record->id.'/edit'),
            ])
            ->emptyStateHeading('No hay nuevas emisoras')
            ->emptyStateDescription('Cuando los usuarios envíen emisoras, aparecerán aquí para su revisión.')
            ->emptyStateIcon('heroicon-o-radio');
    }
}
