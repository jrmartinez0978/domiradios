<?php

namespace App\Filament\Resources\RadioResource\Pages;

use App\Filament\Resources\RadioResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Illuminate\Database\Eloquent\Model;

class ListRadios extends ListRecords
{
    protected static string $resource = RadioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')
                ->label('Name')
                ->sortable()
                ->searchable(),
            Tables\Columns\ImageColumn::make('img')
                ->label('Image')
                ->disk('public')  // Usa el disco 'public'
                ->path('radios')  // Define la carpeta donde se almacenan las imágenes
                ->defaultImageUrl(asset('storage/radios/radio_default.jpg'))  // Imagen por defecto si no hay ninguna
                ->url(fn ($record) => asset('storage/radios/' . $record->img))  // Construye la URL completa
                ->circular(),  // Opcional: muestra la imagen como circular
            Tables\Columns\TextColumn::make('type_radio')
                ->label('Format')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('source_radio')
                ->label('Source')
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('bitrate')
                ->label('BitRate'),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\Action::make('play')
                ->label('Play')
                ->icon('heroicon-o-play')
                ->action(fn (Model $record) => $this->playRadio($record->link_radio)),
        ];
    }

    protected function playRadio(string $streamUrl)
    {
        // Lógica para reproducir la emisora. Puedes usar un modal o una vista personalizada.
        $this->notify('success', 'Reproduciendo: ' . $streamUrl);

        $this->dispatchBrowserEvent('play-radio', ['streamUrl' => $streamUrl]);
    }

    protected function isTableLayoutGrid(): bool
    {
        return true; // Esto cambia el layout a grid
    }

    protected function getTableContentGridColumns(): int
    {
        return 3; // Número de columnas en la grid
    }
}

