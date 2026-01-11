<?php

namespace App\Filament\Resources\BlogArticles\Pages;

use App\Filament\Resources\BlogArticles\BlogArticleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action;
use App\Enums\BlogArticleStatus;
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
                ->label('Перенести в черновик')
                ->color('warning')
                ->visible(fn () => $this->record->status === BlogArticleStatus::Published->value)
                ->requiresConfirmation()
                ->modalHeading('Перенести статью в черновик?')
                ->modalDescription('Это снимет статью с публикации и исключит её из API')
                ->modalSubmitActionLabel('Подтвердить')
                ->modalCancelActionLabel('Отмена')
                ->action(function () {
                    $this->record->update([
                        'status' => BlogArticleStatus::Draft->value,
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
            ($this->record->status ?? null) === BlogArticleStatus::Published->value
            && ($data['status'] ?? null) === BlogArticleStatus::Draft->value
        ) {
            $data['status'] = BlogArticleStatus::Published->value;
        }
        return $data;
    }
}
