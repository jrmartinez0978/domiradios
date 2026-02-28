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
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Columns\Column;

class RadioResource extends Resource
{
    protected static ?string $model = Radio::class;
    protected static ?string $navigationIcon = 'heroicon-o-radio';
    protected static ?string $navigationGroup = 'Contenido';
    protected static ?string $navigationLabel = 'Emisoras';
    protected static ?string $modelLabel = 'Emisora';
    protected static ?string $pluralModelLabel = 'Emisoras';
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 50 ? 'success' : 'warning';
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
                        'Opus' => 'Opus (audio/opus) - Para RTCStream',
                    ])
                    ->required(),
                Forms\Components\Select::make('source_radio')
                    ->label('Source')
                    ->options([
                        'AzuraCast' => 'AzuraCast',
                        'SonicPanel' => 'SonicPanel',
                        'Shoutcast' => 'Shoutcast',
                        'Icecast' => 'Icecast',
                        'RTCStream' => 'RTCStream (WebRTC/Opus)',
                        'Other' => 'Other',
                    ])
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, $state) {
                        // Si el usuario selecciona RTCStream, establecer codec Opus automáticamente
                        if ($state === 'RTCStream') {
                            $set('type_radio', 'Opus');
                        }
                    }),
                Forms\Components\Textarea::make('description')
                    ->label('Descripción')
                    ->maxLength(5000)
                    ->autosize() // Ajusta automáticamente la altura según el contenido
                    ->rows(8) // Altura inicial más grande
                    ->nullable()
                    ->columnSpanFull()
                    ->helperText('Ingrese una descripción detallada de la emisora. Puede usar HTML básico (<b>negrita</b>, <i>cursiva</i>, <br> para saltos de línea).'),

                Forms\Components\TextInput::make('address')
                    ->label('Dirección o Ciudad*')
                    ->maxLength(255)
                    ->required()
                    ->placeholder('Ejemplo: Santo Domingo, República Dominicana')
                    ->helperText('Incluya ciudad y país para SEO. Este campo es obligatorio.')
                    ->validationMessages([
                        'required' => 'La dirección o ciudad es obligatoria.',
                        'max' => 'La dirección no puede superar los 255 caracteres.'
                    ]),

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
                
                // Ya tenemos los botones estándar de Ver/Editar arriba, no necesitamos duplicarlos
                    
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

                // Nuevo: Cambiar origen masivamente
                Tables\Actions\BulkAction::make('changeSource')
                    ->label('Cambiar Origen')
                    ->icon('heroicon-o-server')
                    ->color('info')
                    ->form([
                        Forms\Components\Select::make('source_radio')
                            ->label('Nuevo Origen')
                            ->options([
                                'AzuraCast' => 'AzuraCast',
                                'SonicPanel' => 'SonicPanel',
                                'Shoutcast' => 'Shoutcast',
                                'Icecast' => 'Icecast',
                                'RTCStream' => 'RTCStream',
                                'Other' => 'Other',
                            ])
                            ->required(),
                    ])
                    ->action(function ($records, array $data) {
                        foreach ($records as $record) {
                            $record->source_radio = $data['source_radio'];
                            $record->save();
                        }
                    })
                    ->deselectRecordsAfterCompletion(),

                // Nuevo: Asignar rating masivamente
                Tables\Actions\BulkAction::make('setRating')
                    ->label('Asignar Rating')
                    ->icon('heroicon-o-star')
                    ->color('warning')
                    ->form([
                        Forms\Components\Select::make('rating')
                            ->label('Rating')
                            ->options([
                                1 => '1 ⭐',
                                2 => '2 ⭐⭐',
                                3 => '3 ⭐⭐⭐',
                                4 => '4 ⭐⭐⭐⭐',
                                5 => '5 ⭐⭐⭐⭐⭐',
                            ])
                            ->required(),
                    ])
                    ->action(function ($records, array $data) {
                        foreach ($records as $record) {
                            $record->rating = $data['rating'];
                            $record->save();
                        }
                    }),

                // Nuevo: Remover de destacadas
                Tables\Actions\BulkAction::make('unfeatureBulk')
                    ->label('Quitar de Destacadas')
                    ->icon('heroicon-o-star')
                    ->color('gray')
                    ->action(function ($records) {
                        foreach ($records as $record) {
                            $record->isFeatured = false;
                            $record->save();
                        }
                    }),

                Tables\Actions\DeleteBulkAction::make(),

                // Export a Excel
                ExportBulkAction::make()
                    ->exports([
                        ExcelExport::make()
                            ->fromTable()
                            ->withFilename(fn () => 'emisoras-' . date('Y-m-d-His'))
                            ->withColumns([
                                Column::make('name')->heading('Nombre'),
                                Column::make('slug')->heading('Slug'),
                                Column::make('bitrate')->heading('Frecuencia'),
                                Column::make('type_radio')->heading('Formato'),
                                Column::make('source_radio')->heading('Origen'),
                                Column::make('link_radio')->heading('URL Stream'),
                                Column::make('rating')->heading('Rating'),
                                Column::make('address')->heading('Ciudad'),
                                Column::make('isActive')->heading('Activa')
                                    ->formatStateUsing(fn ($state) => $state ? 'Sí' : 'No'),
                                Column::make('isFeatured')->heading('Destacada')
                                    ->formatStateUsing(fn ($state) => $state ? 'Sí' : 'No'),
                                Column::make('url_website')->heading('Website'),
                                Column::make('created_at')->heading('Fecha Creación'),
                            ])
                    ])
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->label('Exportar a Excel'),
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
