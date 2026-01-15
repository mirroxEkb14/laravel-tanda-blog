<?php

namespace App\Filament\Resources\BlogCategories\Pages;

use App\Filament\Resources\BlogCategories\BlogCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBlogCategory extends EditRecord
{
    protected static string $resource = BlogCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label(__('filament.actions.delete'))
                ->disabled(fn () => $this->record->isUsed())
                ->tooltip(fn () => $this->record->isUsed() ? $this->record->deleteBlockReason() : null)
                ->requiresConfirmation(),
        ];
    }
}
