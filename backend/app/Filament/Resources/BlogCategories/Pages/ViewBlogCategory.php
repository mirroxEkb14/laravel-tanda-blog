<?php

namespace App\Filament\Resources\BlogCategories\Pages;

use App\Filament\Resources\BlogCategories\BlogCategoryResource;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;

/**
 * "DeleteAction" ensures if the category is currently used by one or more blog articles, delete button is disabled
 */
class ViewBlogCategory extends ViewRecord
{
    protected static string $resource = BlogCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()->label('Изменить'),
            DeleteAction::make()
                ->label('Удалить')
                ->disabled(fn () => $this->record->isUsed())
                ->tooltip(fn () => $this->record->isUsed() ? $this->record->deleteBlockReason() : null)
                ->requiresConfirmation(),
        ];
    }
}
