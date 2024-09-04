<?php

namespace App\Filament\Resources\RadioCatResource\Pages;

use App\Filament\Resources\RadioCatResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRadioCats extends ListRecords
{
    protected static string $resource = RadioCatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
