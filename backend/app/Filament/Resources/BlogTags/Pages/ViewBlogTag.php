<?php

namespace App\Filament\Resources\BlogTags\Pages;

use App\Filament\Resources\BlogTags\BlogTagResource;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;

/**
 * "DeleteAction" ensures if the tag is currently used by one or more blog articles, delete button is disabled
 */
class ViewBlogTag extends ViewRecord
{
    protected static string $resource = BlogTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()->label(__('filament.actions.edit')),
            DeleteAction::make()
                ->label(__('filament.actions.delete'))
                ->disabled(fn () => $this->record->isUsed())
                ->tooltip(fn () => $this->record->isUsed() ? $this->record->deleteBlockReason() : null)
                ->requiresConfirmation(),
        ];
    }
}
