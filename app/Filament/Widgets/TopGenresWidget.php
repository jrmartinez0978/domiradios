<?php

namespace App\Filament\Widgets;

use App\Models\Genre;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Cache;

class TopGenresWidget extends BaseWidget
{
    protected static ?int $sort = 6;
    protected static ?string $pollingInterval = '120s';
    protected int | string | array $columnSpan = 12;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Genre::query()
                    ->withCount('radios')
                    ->having('radios_count', '>', 0)
                    ->orderBy('radios_count', 'desc')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Ciudad/Género')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-map-pin')
                    ->iconColor('primary')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('radios_count')
                    ->label('Emisoras')
                    ->badge()
                    ->color('success')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->copyable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->heading('Top 10 Ciudades/Géneros')
            ->description('Ciudades con más emisoras registradas')
            ->defaultSort('radios_count', 'desc')
            ->paginated(false)
            ->striped();
    }

    public static function canView(): bool
    {
        return Genre::has('radios')->count() > 0;
    }
}
