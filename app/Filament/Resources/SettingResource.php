<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('app_name')
                    ->label('App Name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('app_email')
                    ->label('Email Contact')
                    ->email()
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('app_website')
                    ->label('Website')
                    ->url()
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('app_copyright')
                    ->label('Copyright Info')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('app_phone')
                    ->label('Phone Number')
                    ->tel()
                    ->maxLength(255),

                Forms\Components\TextInput::make('app_facebook')
                    ->label('Facebook Page')
                    ->url()
                    ->maxLength(255),

                Forms\Components\TextInput::make('app_twitter')
                    ->label('Twitter Page')
                    ->url()
                    ->maxLength(255),

                Forms\Components\RichEditor::make('app_privacy_policy')
                    ->label('Privacy Policy')
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\RichEditor::make('app_term_of_use')
                    ->label('Term of Use')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('app_name')
                    ->label('App Name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('app_email')
                    ->label('Email Contact')
                    ->searchable(),

                Tables\Columns\TextColumn::make('app_copyright')
                    ->label('Copyright Info')
                    ->searchable(),

                Tables\Columns\TextColumn::make('app_phone')
                    ->label('Phone Number')
                    ->searchable(),

                Tables\Columns\TextColumn::make('app_website')
                    ->label('Website')
                    ->searchable(),

                Tables\Columns\TextColumn::make('app_facebook')
                    ->label('Facebook Page')
                    ->searchable(),

                Tables\Columns\TextColumn::make('app_twitter')
                    ->label('Twitter Page')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
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
            'index' => Pages\ListSettings::route('/'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}

