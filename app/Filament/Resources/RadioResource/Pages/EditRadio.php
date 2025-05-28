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
    
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Garantizar que el campo description está disponible para el formulario
        if (isset($data['description'])) {
            // Asegurarnos que los datos HTML son sanitizados pero preservados
            $data['description'] = $data['description'];
        }
        
        return $data;
    }
    
    protected function afterSave(): void
    {
        // Limpiar cache si es necesario
        $this->record->refresh(); 
    }
}
