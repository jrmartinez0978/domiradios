<?php

namespace App\Filament\Resources\BlogPostResource\Pages;

use App\Filament\Resources\BlogPostResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBlogPost extends CreateRecord
{
    protected static string $resource = BlogPostResource::class;

    protected function getRedirectUrl(): string
    {
        // Redirigir a la página de edición después de crear
        return $this->getResource()::getUrl('edit', ['record' => $this->record]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Establecer autor por defecto si no está configurado
        if (empty($data['user_id'])) {
            $data['user_id'] = auth()->user()->user_id ?? null;
        }

        // Si se publica inmediatamente, establecer fecha de publicación
        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        return $data;
    }
}
