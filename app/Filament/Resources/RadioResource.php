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
                Tables\Columns\TextColumn::make('name')->label('Nombre')->searchable(),
                Tables\Columns\ImageColumn::make('img')->label('Imagen')->sortable(),
                Tables\Columns\TextColumn::make('type_radio')->label('Formato')->searchable(),
                Tables\Columns\TextColumn::make('bitrate')->label('Frecuencia')->searchable(),
                Tables\Columns\TextColumn::make('source_radio')
                    ->label('Origen')
                    ->searchable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'user_submitted' => 'warning',
                        default => 'primary',
                    }),
                Tables\Columns\IconColumn::make('isFeatured')->label('Destacada')->boolean(),
                Tables\Columns\IconColumn::make('isActive')
                    ->label('Activa')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                // Columna de clasificación (rating) en la tabla
                RatingColumn::make('rating')->stars(5),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha creación')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('genre')
                    ->relationship('genres', 'name')
                    ->label('Filtrar por Ciudad/Género'),
                Tables\Filters\SelectFilter::make('source_radio')
                    ->options([
                        'user_submitted' => 'Emisoras enviadas por usuarios',
                        'AzuraCast' => 'AzuraCast',
                        'SonicPanel' => 'SonicPanel',
                        'Shoutcast' => 'Shoutcast',
                        'Icecast' => 'Icecast',
                        'Other' => 'Otros',
                    ])
                    ->label('Filtrar por Origen'),
                Tables\Filters\Filter::make('featured')
                    ->label('Emisoras Destacadas')
                    ->query(fn (Builder $query): Builder => $query->where('isFeatured', true)),
                Tables\Filters\Filter::make('inactive')
                    ->label('Emisoras Pendientes de Activación')
                    ->query(fn (Builder $query): Builder => $query->where('isActive', false)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                
                // Botón para activar una emisora (visible solo cuando está inactiva)
                Tables\Actions\Action::make('activateRadio')
                    ->label('Activar Emisora')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Radio $record): bool => !$record->isActive && $record->source_radio === 'user_submitted')
                    ->action(function (Radio $record) {
                        $record->isActive = true;
                        $record->save();
                        
                        // Opcional: Enviar un correo al usuario notificando la activación
                        // Mail::to('usuario@example.com')->send(new \App\Mail\RadioActivated($record));
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Activar esta emisora')
                    ->modalDescription('¿Estás seguro de que deseas activar esta emisora? Una vez activada, estará visible para todos los usuarios.')
                    ->modalSubmitActionLabel('Sí, activar emisora'),
                
                // Botón para destacar/quitar destacado
                Tables\Actions\Action::make('toggleFeatured')
                    ->label(fn (Radio $record): string => $record->isFeatured ? 'Quitar destacado' : 'Destacar')
                    ->icon('heroicon-o-star')
                    ->color(fn (Radio $record): string => $record->isFeatured ? 'warning' : 'primary')
                    ->action(function (Radio $record) {
                        $record->isFeatured = !$record->isFeatured;
                        $record->save();
                    }),
                
                // Botón para activar/desactivar (general)
                Tables\Actions\Action::make('toggleActive')
                    ->label(fn (Radio $record): string => $record->isActive ? 'Desactivar' : 'Activar')
                    ->icon(fn (Radio $record): string => $record->isActive ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                    ->color(fn (Radio $record): string => $record->isActive ? 'danger' : 'success')
                    ->action(function (Radio $record) {
                        $record->isActive = !$record->isActive;
                        $record->save();
                    }),
                
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('activateBulk')
                    ->label('Activar Emisoras Seleccionadas')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function ($records) {
                        foreach ($records as $record) {
                            $record->isActive = true;
                            $record->save();
                        }
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Activar emisoras seleccionadas')
                    ->modalDescription('¿Estás seguro de que deseas activar todas las emisoras seleccionadas? Estarán visibles para todos los usuarios.')
                    ->modalSubmitActionLabel('Sí, activar emisoras'),
                    
                Tables\Actions\BulkAction::make('featureBulk')
                    ->label('Destacar Emisoras Seleccionadas')
                    ->icon('heroicon-o-star')
                    ->color('warning')
                    ->action(function ($records) {
                        foreach ($records as $record) {
                            $record->isFeatured = true;
                            $record->save();
                        }
                    }),
                    
                Tables\Actions\BulkAction::make('deactivateBulk')
                    ->label('Desactivar Emisoras Seleccionadas')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(function ($records) {
                        foreach ($records as $record) {
                            $record->isActive = false;
                            $record->save();
                        }
                    })
                    ->requiresConfirmation(),
                    
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
