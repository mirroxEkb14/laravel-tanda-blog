<?php

namespace App\Filament\Resources\BlogArticles\Pages;

use App\Filament\Resources\BlogArticles\BlogArticleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Filament\Actions\Action;

class EditBlogArticle extends EditRecord
{
    protected static string $resource = BlogArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('moveToDraft')
                ->label('Move to draft')
                ->color('warning')
                ->visible(fn () => $this->record->status === 'published')
                ->requiresConfirmation()
                ->modalHeading('Move article to draft?')
                ->modalDescription('This will unpublish the article and remove it from the public API')
                ->action(function () {
                    $this->record->update([
                        'status' => 'draft',
                        'publish_at' => null,
                    ]);
                    Notification::make()
                        ->title('Article moved to draft')
                        ->success()
                        ->send();
                }),
            DeleteAction::make(),
        ];
    }

    /**
     * Represents a hook executed before saving the article.
     * Prevent bypassing confirmation by changing status in the dropdown.
     * Keep published, user must use the Move to draft action.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (($this->record->status ?? null) === 'published' && ($data['status'] ?? null) === 'draft') {
            $data['status'] = 'published';
        }
        return $data;
    }
}
