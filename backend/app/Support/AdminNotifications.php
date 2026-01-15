<?php

namespace App\Support;

use Filament\Notifications\Notification;

class AdminNotifications
{
    public static function success(string $title, ?string $body = null): void
    {
        $n = Notification::make()->title($title)->success();
        if ($body !== null) {
            $n->body($body);
        }
        $n->send();
    }

    public static function danger(string $title, ?string $body = null): void
    {
        $n = Notification::make()->title($title)->danger();
        if ($body !== null) {
            $n->body($body);
        }
        $n->send();
    }

    public static function articleMovedToDraft(): void
    {
        self::success(__('filament.notifications.article_moved_to_draft'));
    }

    public static function categoryUpdated(): void
    {
        self::success(
            __('filament.notifications.category_updated_title'),
            __('filament.notifications.category_updated_body'),
        );
    }

    public static function cannotDeleteCategories(int $usedCount): void
    {
        self::danger(
            __('filament.notifications.cannot_delete_categories_title'),
            __('filament.notifications.cannot_delete_categories_body', ['count' => $usedCount]),
        );
    }

    public static function cannotDeleteTags(int $usedCount): void
    {
        self::danger(
            __('filament.notifications.cannot_delete_tags_title'),
            __('filament.notifications.cannot_delete_tags_body', ['count' => $usedCount]),
        );
    }

    public static function articlesPublished(int $count): void
    {
        self::success(
            __('filament.notifications.articles_published_title'),
            __('filament.notifications.articles_published_body', ['count' => $count]),
        );
    }

    public static function articlesMovedToDraft(int $count): void
    {
        self::success(
            __('filament.notifications.articles_moved_to_draft_title'),
            __('filament.notifications.articles_moved_to_draft_body', ['count' => $count]),
        );
    }
}
