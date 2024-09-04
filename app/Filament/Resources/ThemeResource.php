<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ThemeResource\Pages;
use App\Models\Theme;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ThemeResource extends Resource
{
    protected static ?string $model = Theme::class;

    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('img')
                    ->label('Image')
                    ->directory('uploads/themes')
                    ->visibility('public')
                    ->maxSize(1024)
                    ->nullable(),
                Forms\Components\TextInput::make('grad_start_color')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('grad_end_color')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('grad_orientation')
                    ->options([
                        0 => 'Left Right',
                        180 => 'Right Left',
                        270 => 'Top Bottom',
                        90 => 'Bottom Top',
                        315 => 'Top Left Bottom Right',
                        225 => 'Top Right Bottom Left',
                        45 => 'Bottom Left Top Right',
                        135 => 'Bottom Right Top Left',
                    ])
                    ->required(),
                Forms\Components\Toggle::make('is_single_theme')
                    ->label('Single Theme')
                    ->default(false),
                Forms\Components\Toggle::make('isActive')
                    ->label('Active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\ImageColumn::make('img')->label('Image'),
                Tables\Columns\TextColumn::make('grad_start_color')->label('Start Color'),
                Tables\Columns\TextColumn::make('grad_end_color')->label('End Color'),
                Tables\Columns\TextColumn::make('grad_orientation')->label('Orientation'),
                Tables\Columns\IconColumn::make('is_single_theme')->label('Single Theme')->boolean(),
                Tables\Columns\IconColumn::make('isActive')->label('Active')->boolean(),
            ])
            ->filters([
                Tables\Filters\Filter::make('active')
                    ->label('Active Themes')
                    ->query(fn (Builder $query): Builder => $query->where('isActive', true)),
                Tables\Filters\Filter::make('single_theme')
                    ->label('Single Themes')
                    ->query(fn (Builder $query): Builder => $query->where('is_single_theme', true)),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('toggleActive')
                    ->label('Toggle Active')
                    ->action(fn (Theme $record) => $record->update(['isActive' => !$record->isActive])),
                Tables\Actions\Action::make('toggleSingleTheme')
                    ->label('Set as Single Theme')
                    ->action(function (Theme $record) {
                        Theme::query()->update(['is_single_theme' => false]); // Reset all themes
                        $record->update(['is_single_theme' => true]);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListThemes::route('/'),
            'create' => Pages\CreateTheme::route('/create'),
            'edit' => Pages\EditTheme::route('/{record}/edit'),
        ];
    }
}
