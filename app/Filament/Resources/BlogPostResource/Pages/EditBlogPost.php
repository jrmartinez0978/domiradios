<?php

namespace App\Filament\Resources\BlogPostResource\Pages;

use App\Filament\Resources\BlogPostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBlogPost extends EditRecord
{
    protected static string $resource = BlogPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),

            // Acción personalizada para vista previa
            Actions\Action::make('preview')
                ->label('Vista Previa')
                ->icon('heroicon-o-eye')
                ->url(fn () => route('blog.show', $this->record->slug))
                ->openUrlInNewTab()
                ->visible(fn () => $this->record->isPublished()),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Si cambia a publicado y no tiene fecha, establecer ahora
        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        return $data;
    }

    protected function afterSave(): void
    {
        // Limpiar caché del blog si es necesario
        $this->record->refresh();
    }
}
