<?php

namespace App\Filament\Resources\BlogTags\Pages;

use App\Filament\Resources\BlogTags\BlogTagResource;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;

class ViewBlogTag extends ViewRecord
{
    protected static string $resource = BlogTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make()
                ->disabled(fn () => $this->record->isUsed())
                ->tooltip(fn () => $this->record->isUsed() ? $this->record->deleteBlockReason() : null)
                ->requiresConfirmation(),
        ];
    }
}
