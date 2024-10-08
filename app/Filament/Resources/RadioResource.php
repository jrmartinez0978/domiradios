<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RadioResource\Pages;
use App\Models\Radio;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Mokhosh\FilamentRating\Components\Rating;  // Importamos el componente de rating
use Mokhosh\FilamentRating\Columns\RatingColumn;  // Importamos la columna de rating

class RadioResource extends Resource
{
    protected static ?string $model = Radio::class;
    protected static ?string $navigationIcon = 'heroicon-o-radio';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255)
                    ->afterStateUpdated(function ($state, callable $set) {
                        // Cuando se actualiza el nombre, genera automáticamente el slug
                        $set('slug', Str::slug($state));
                    }),

                // Campo slug visible para que el usuario pueda modificarlo si lo desea
                Forms\Components\TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->hint('El slug se genera automáticamente a partir del nombre')
                    ->maxLength(255),

                Forms\Components\TextInput::make('bitrate')
                    ->label('Frecuencia')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('tags')
                    ->label('Tags')
                    ->maxLength(255),
                Forms\Components\Select::make('type_radio')
                    ->label('Format')
                    ->options([
                        'FLAC' => 'FLAC (audio/flac)',
                        'OGG' => 'OGG (audio/ogg)',
                        'MP3' => 'MP3 (audio/mpeg)',
                        'AAC' => 'AAC (audio/aacp)',
                    ])
                    ->required(),
                Forms\Components\Select::make('source_radio')
                    ->label('Source')
                    ->options([
                        'AzuraCast' => 'AzuraCast',
                        'SonicPanel' => 'SonicPanel',
                        'Shoutcast' => 'Shoutcast',
                        'Icecast' => 'Icecast',
                        'Other' => 'Other',
                    ])
                    ->required(),
                Forms\Components\RichEditor::make('description')
                    ->label('Descripción')
                    ->maxLength(1000)
                    ->nullable()
                    ->columnspan('full'),
                // Implementación del campo de clasificación (rating)
                Rating::make('rating')
                    ->stars(5)  // Número máximo de estrellas (por defecto 5)
                    ->required(),  // Clasificación obligatoria

                Forms\Components\TextInput::make('link_radio')
                    ->label('Link')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('genre_ids')
                    ->label('Ciudades')
                    ->relationship('genres', 'name')
                    ->required(),
                Forms\Components\FileUpload::make('img')
                    ->label('Image')
                    ->image()
                    ->directory('/radios')
                    ->disk('public')
                    ->visibility('public')
                    ->maxSize(2024)  // Máximo 2MB
                    ->hint('El tamaño de la imagen debe ser 500x500 píxeles')
                    ->columnSpanFull()
                    ->nullable()  // Permitir que sea nulo
                    ->preserveFilenames(),  // Preservar el nombre original del archivo
                Forms\Components\TextInput::make('user_agent_radio')
                    ->label('User Agent')
                    ->maxLength(255),
                Forms\Components\TextInput::make('url_facebook')
                    ->label('Facebook URL')
                    ->url()
                    ->maxLength(255)
                    ->nullable(),
                Forms\Components\TextInput::make('url_twitter')
                    ->label('Twitter URL')
                    ->url()
                    ->maxLength(255)
                    ->nullable(),
                Forms\Components\TextInput::make('url_instagram')
                    ->label('Instagram URL')
                    ->url()
                    ->maxLength(255)
                    ->nullable(),
                Forms\Components\TextInput::make('url_website')
                    ->label('Website URL')
                    ->url()
                    ->maxLength(255)
                    ->nullable(),
                Forms\Components\Toggle::make('isFeatured')
                    ->label('Featured')
                    ->default(false),
                Forms\Components\Toggle::make('isActive')
                    ->label('Active')
                    ->default(true),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Name')->searchable(),
                Tables\Columns\ImageColumn::make('img')->label('Image')->sortable(),
                Tables\Columns\TextColumn::make('type_radio')->label('Format')->searchable(),
                Tables\Columns\TextColumn::make('source_radio')->label('Source')->searchable(),
                Tables\Columns\TextColumn::make('link_radio')->label('Link')->searchable(),
                Tables\Columns\IconColumn::make('isFeatured')->label('Featured')->boolean(),
                Tables\Columns\IconColumn::make('isActive')->label('Active')->boolean(),

                // Columna de clasificación (rating) en la tabla
                RatingColumn::make('rating')
                    ->stars(5),  // Número de estrellas en la tabla
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('genre')
                    ->relationship('genres', 'name')
                    ->label('Filter by Genre'),
                Tables\Filters\Filter::make('featured')
                    ->label('Featured Radios')
                    ->query(fn (Builder $query): Builder => $query->where('isFeatured', true)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('toggleFeatured')
                    ->label('Toggle Featured')
                    ->action(function (Radio $record) {
                        $record->isFeatured = !$record->isFeatured;
                        $record->save();
                    }),
                Tables\Actions\Action::make('toggleActive')
                    ->label('Toggle Active')
                    ->action(function (Radio $record) {
                        $record->isActive = !$record->isActive;
                        $record->save();
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Relaciones si es necesario...
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRadios::route('/'),
            'create' => Pages\CreateRadio::route('/create'),
            'edit' => Pages\EditRadio::route('/{record}/edit'),
            'view' => Pages\ViewRadio::route('/{record}'),
        ];
    }
}
