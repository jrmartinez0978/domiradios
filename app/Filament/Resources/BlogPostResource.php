<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogPostResource\Pages;
use App\Models\BlogPost;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class BlogPostResource extends Resource
{
    protected static ?string $model = BlogPost::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Blog Posts';
    protected static ?string $modelLabel = 'Post';
    protected static ?string $pluralModelLabel = 'Posts';
    protected static ?string $navigationGroup = 'Contenido';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Contenido Principal')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Título')
                            ->required()
                            ->maxLength(255)
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, $get) {
                                // Generar slug automáticamente
                                if (empty($get('slug'))) {
                                    $set('slug', Str::slug($state));
                                }
                                // Generar meta_title por defecto
                                if (empty($get('meta_title'))) {
                                    $set('meta_title', $state);
                                }
                            })
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('slug')
                            ->label('Slug (URL)')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->hint('Se genera automáticamente del título')
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('excerpt')
                            ->label('Extracto')
                            ->rows(3)
                            ->maxLength(500)
                            ->hint('Resumen breve del post (máx. 160 caracteres recomendado para SEO)')
                            ->columnSpanFull(),

                        Forms\Components\RichEditor::make('content')
                            ->label('Contenido')
                            ->required()
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'strike',
                                'link',
                                'heading',
                                'bulletList',
                                'orderedList',
                                'blockquote',
                                'codeBlock',
                                'undo',
                                'redo',
                            ])
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Media')
                    ->schema([
                        Forms\Components\FileUpload::make('featured_image')
                            ->label('Imagen Destacada')
                            ->image()
                            ->directory('blog/featured')
                            ->disk('public')
                            ->visibility('public')
                            ->maxSize(5120) // 5MB
                            ->hint('Tamaño recomendado: 1200x630px (relación 1.91:1) para Open Graph')
                            ->imageEditor()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('featured_image_alt')
                            ->label('Texto Alt de la Imagen')
                            ->maxLength(255)
                            ->hint('Descripción de la imagen para SEO y accesibilidad')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Categorización')
                    ->schema([
                        Forms\Components\TextInput::make('category')
                            ->label('Categoría')
                            ->maxLength(255)
                            ->datalist([
                                'Noticias',
                                'Tutoriales',
                                'Industria Musical',
                                'Tecnología',
                                'Entrevistas',
                                'Eventos',
                            ])
                            ->hint('Escriba o seleccione de la lista'),

                        Forms\Components\TagsInput::make('tags')
                            ->label('Etiquetas')
                            ->separator(',')
                            ->hint('Presione Enter después de cada etiqueta'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('SEO')
                    ->schema([
                        Forms\Components\TextInput::make('meta_title')
                            ->label('Meta Título')
                            ->maxLength(60)
                            ->hint('60 caracteres recomendado')
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('meta_description')
                            ->label('Meta Descripción')
                            ->rows(3)
                            ->maxLength(160)
                            ->hint('160 caracteres recomendado')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('meta_keywords')
                            ->label('Meta Keywords')
                            ->maxLength(255)
                            ->hint('Palabras clave separadas por comas')
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('canonical_url')
                            ->label('URL Canónica')
                            ->url()
                            ->maxLength(255)
                            ->hint('Dejar en blanco para usar la URL del post')
                            ->columnSpanFull(),
                    ])
                    ->collapsed(),

                Forms\Components\Section::make('Publicación')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Estado')
                            ->options([
                                'draft' => 'Borrador',
                                'published' => 'Publicado',
                                'scheduled' => 'Programado',
                            ])
                            ->default('draft')
                            ->required()
                            ->reactive(),

                        Forms\Components\DateTimePicker::make('published_at')
                            ->label('Fecha de Publicación')
                            ->hint('Dejar en blanco para publicar inmediatamente')
                            ->visible(fn ($get) => $get('status') !== 'draft'),

                        Forms\Components\Select::make('user_id')
                            ->label('Autor')
                            ->relationship('user', 'name', fn ($query) => $query->orderBy('name'))
                            ->default(auth()->user()->user_id ?? null)
                            ->required()
                            ->searchable(),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Opciones Avanzadas')
                    ->schema([
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Destacado')
                            ->default(false)
                            ->hint('Mostrar en sección destacados'),

                        Forms\Components\Toggle::make('allow_comments')
                            ->label('Permitir Comentarios')
                            ->default(true),

                        Forms\Components\TextInput::make('reading_time')
                            ->label('Tiempo de Lectura (minutos)')
                            ->numeric()
                            ->hint('Se calcula automáticamente si se deja vacío')
                            ->disabled(),

                        Forms\Components\TextInput::make('views')
                            ->label('Vistas')
                            ->numeric()
                            ->default(0)
                            ->disabled(),
                    ])
                    ->columns(4)
                    ->collapsed(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Imagen')
                    ->square()
                    ->defaultImageUrl(url('images/default-blog-post.jpg')),

                Tables\Columns\TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->title),

                Tables\Columns\TextColumn::make('category')
                    ->label('Categoría')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Autor')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'scheduled' => 'warning',
                        'draft' => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'published' => 'Publicado',
                        'scheduled' => 'Programado',
                        'draft' => 'Borrador',
                    }),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Destacado')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-star')
                    ->trueColor('warning')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('views')
                    ->label('Vistas')
                    ->sortable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Publicado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'draft' => 'Borrador',
                        'published' => 'Publicado',
                        'scheduled' => 'Programado',
                    ]),

                Tables\Filters\SelectFilter::make('category')
                    ->label('Categoría')
                    ->options(function () {
                        return BlogPost::query()
                            ->whereNotNull('category')
                            ->distinct()
                            ->pluck('category', 'category')
                            ->toArray();
                    }),

                Tables\Filters\Filter::make('featured')
                    ->label('Destacados')
                    ->query(fn (Builder $query): Builder => $query->where('is_featured', true)),

                Tables\Filters\Filter::make('published')
                    ->label('Publicados')
                    ->query(fn (Builder $query): Builder => $query->published()),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('publish')
                    ->label('Publicar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (BlogPost $record): bool => $record->status !== 'published')
                    ->action(function (BlogPost $record) {
                        $record->status = 'published';
                        $record->published_at = $record->published_at ?? now();
                        $record->save();
                    })
                    ->requiresConfirmation(),

                Tables\Actions\Action::make('toggleFeatured')
                    ->label(fn (BlogPost $record): string => $record->is_featured ? 'Quitar destacado' : 'Destacar')
                    ->icon('heroicon-o-star')
                    ->color(fn (BlogPost $record): string => $record->is_featured ? 'warning' : 'gray')
                    ->action(function (BlogPost $record) {
                        $record->is_featured = !$record->is_featured;
                        $record->save();
                    }),

                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('publish')
                    ->label('Publicar Seleccionados')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function ($records) {
                        foreach ($records as $record) {
                            $record->status = 'published';
                            $record->published_at = $record->published_at ?? now();
                            $record->save();
                        }
                    })
                    ->requiresConfirmation(),

                Tables\Actions\BulkAction::make('feature')
                    ->label('Destacar Seleccionados')
                    ->icon('heroicon-o-star')
                    ->color('warning')
                    ->action(function ($records) {
                        foreach ($records as $record) {
                            $record->is_featured = true;
                            $record->save();
                        }
                    }),

                Tables\Actions\BulkAction::make('draft')
                    ->label('Mover a Borrador')
                    ->icon('heroicon-o-document')
                    ->color('gray')
                    ->action(function ($records) {
                        foreach ($records as $record) {
                            $record->status = 'draft';
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
            // Relaciones si es necesario (ej: comentarios)
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogPosts::route('/'),
            'create' => Pages\CreateBlogPost::route('/create'),
            'edit' => Pages\EditBlogPost::route('/{record}/edit'),
            'view' => Pages\ViewBlogPost::route('/{record}'),
        ];
    }
}
