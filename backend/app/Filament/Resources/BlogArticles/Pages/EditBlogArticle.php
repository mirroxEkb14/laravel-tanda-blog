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

    /**
     * Represents custom “Move to draft” action
     */
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
     * Prevents bypassing confirmation "Move to draft" by changing status ('published' -> 'draft') in the dropdown
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (($this->record->status ?? null) === 'published' && ($data['status'] ?? null) === 'draft') {
            $data['status'] = 'published';
        }
        return $data;
    }
}
