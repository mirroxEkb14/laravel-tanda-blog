<?php

namespace App\Filament\Resources\BlogArticles\Pages;

use App\Filament\Resources\BlogArticles\BlogArticleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBlogArticle extends EditRecord
{
    protected static string $resource = BlogArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    /**
     * Represents a hook executed before saving the article.
     *
     * TODO: If user tries to downgrade published -> draft, force confirmation via UI setting.
     * Implement confirmation as an extra "Are you sure?" action next.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }
}
