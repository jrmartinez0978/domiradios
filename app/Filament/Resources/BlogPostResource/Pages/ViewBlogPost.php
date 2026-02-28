<?php

namespace App\Filament\Resources\BlogPostResource\Pages;

use App\Filament\Resources\BlogPostResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBlogPost extends ViewRecord
{
    protected static string $resource = BlogPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),

            // Acción para ver en frontend
            Actions\Action::make('viewFrontend')
                ->label('Ver en Sitio')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(fn () => route('blog.show', $this->record->slug))
                ->openUrlInNewTab()
                ->visible(fn () => $this->record->isPublished()),
        ];
    }
}
