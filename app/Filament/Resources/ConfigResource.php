<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConfigResource\Pages;
use App\Models\Config;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;

class ConfigResource extends Resource
{
    protected static ?string $model = Config::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('app_type')
                    ->label('App Types')
                    ->options([
                        1 => 'Single Radio',
                        2 => 'Multi Radios',
                    ])
                    ->required(),

                Forms\Components\Select::make('is_full_bg')
                    ->label('Background Mode')
                    ->options([
                        0 => 'Just ActionBar',
                        1 => 'Full Background',
                    ])
                    ->required(),

                Forms\Components\Select::make('ui_top_chart')
                    ->label('UI TopChart')
                    ->options([
                        1 => 'Flat Grid',
                        2 => 'Flat List',
                        3 => 'Card Grid',
                        4 => 'Card List',
                    ])
                    ->required(),

                Forms\Components\Select::make('ui_genre')
                    ->label('UI Genres')
                    ->options([
                        1 => 'Flat Grid',
                        2 => 'Flat List',
                        3 => 'Card Grid',
                        4 => 'Card List',
                        5 => 'Magic Grid',
                        0 => 'Hidden',
                    ])
                    ->required(),

                Forms\Components\Select::make('ui_favorite')
                    ->label('UI Favorite')
                    ->options([
                        1 => 'Flat Grid',
                        2 => 'Flat List',
                        3 => 'Card Grid',
                        4 => 'Card List',
                    ])
                    ->required(),

                Forms\Components\Select::make('ui_search')
                    ->label('UI Search')
                    ->options([
                        1 => 'Flat Grid',
                        2 => 'Flat List',
                        3 => 'Card Grid',
                        4 => 'Card List',
                    ])
                    ->required(),

                Forms\Components\Select::make('ui_themes')
                    ->label('UI Themes')
                    ->options([
                        1 => 'Flat Grid',
                        2 => 'Flat List',
                        3 => 'Card Grid',
                        4 => 'Card List',
                        0 => 'Hidden',
                    ])
                    ->required(),

                Forms\Components\Select::make('ui_detail_genre')
                    ->label('UI Detail Genre')
                    ->options([
                        1 => 'Flat Grid',
                        2 => 'Flat List',
                        3 => 'Card Grid',
                        4 => 'Card List',
                    ])
                    ->required(),

                Forms\Components\Select::make('ui_player')
                    ->label('UI Player')
                    ->options([
                        1 => 'Square Disk',
                        2 => 'Circle Disk',
                        3 => 'Rotate Disk',
                        4 => 'Square Disk - No LastFM',
                        5 => 'Circle Disk - No LastFM',
                        6 => 'Rotate Disk - No LastFM',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('app_type')
                    ->label('App Types')
                    ->sortable(),

                Tables\Columns\TextColumn::make('is_full_bg')
                    ->label('Background Mode')
                    ->sortable(),

                Tables\Columns\TextColumn::make('ui_top_chart')
                    ->label('UI TopChart')
                    ->sortable(),

                Tables\Columns\TextColumn::make('ui_genre')
                    ->label('UI Genres')
                    ->sortable(),

                Tables\Columns\TextColumn::make('ui_favorite')
                    ->label('UI Favorite')
                    ->sortable(),

                Tables\Columns\TextColumn::make('ui_search')
                    ->label('UI Search')
                    ->sortable(),

                Tables\Columns\TextColumn::make('ui_themes')
                    ->label('UI Themes')
                    ->sortable(),

                Tables\Columns\TextColumn::make('ui_detail_genre')
                    ->label('UI Detail Genre')
                    ->sortable(),

                Tables\Columns\TextColumn::make('ui_player')
                    ->label('UI Player')
                    ->sortable(),
            ])
            ->filters([
                // Aquí podrías agregar filtros si es necesario
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Aquí podrías definir relaciones si las hay
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListConfigs::route('/'),
            'edit' => Pages\EditConfig::route('/{record}/edit'),
        ];
    }
}

