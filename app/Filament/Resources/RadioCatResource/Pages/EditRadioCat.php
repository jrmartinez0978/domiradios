<?php

namespace App\Filament\Resources\RadioCatResource\Pages;

use App\Filament\Resources\RadioCatResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRadioCat extends EditRecord
{
    protected static string $resource = RadioCatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
