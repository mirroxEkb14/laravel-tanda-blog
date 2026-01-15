<?php

namespace App\Filament\Resources\BlogArticles\Pages;

use App\Filament\Resources\BlogArticles\BlogArticleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action;
use App\Enums\BlogArticleStatusEnum;
use App\Support\AdminNotifications;

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
                ->label(__('filament.blog.articles.actions.move_to_draft'))
                ->color('warning')
                ->visible(fn () => $this->record->status === BlogArticleStatusEnum::Published->value)
                ->requiresConfirmation()
                ->modalHeading(__('filament.blog.articles.actions.draft_modal_heading'))
                ->modalDescription(__('filament.blog.articles.actions.draft_modal_description'))
                ->modalSubmitActionLabel(__('filament.actions.confirm'))
                ->modalCancelActionLabel(__('filament.actions.cancel'))
                ->action(function () {
                    $this->record->update([
                        'status' => BlogArticleStatusEnum::Draft->value,
                        'publish_at' => null,
                    ]);
                    AdminNotifications::articleMovedToDraft();
                }),
            DeleteAction::make(),
        ];
    }

    /**
     * Prevents bypassing confirmation "Move to draft" by changing status ('published' -> 'draft') in the dropdown
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (
            ($this->record->status ?? null) === BlogArticleStatusEnum::Published->value
            && ($data['status'] ?? null) === BlogArticleStatusEnum::Draft->value
        ) {
            $data['status'] = BlogArticleStatusEnum::Published->value;
        }
        return $data;
    }
}
