<?php

namespace App\Filament\Resources\RadioResource\Pages;

use App\Filament\Resources\RadioResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRadio extends EditRecord
{
    protected static string $resource = RadioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
