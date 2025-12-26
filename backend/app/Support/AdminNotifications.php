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
        self::success('Article moved to draft');
    }

    public static function categoryUpdated(): void
    {
        self::success(
            'Category updated',
            'Selected articles were assigned to the chosen category',
        );
    }

    public static function cannotDeleteCategories(int $usedCount): void
    {
        self::danger(
            'Cannot delete selected categories',
            "{$usedCount} of the selected category(ies) are assigned to articles",
        );
    }

    public static function cannotDeleteTags(int $usedCount): void
    {
        self::danger(
            'Cannot delete selected tags',
            "{$usedCount} of the selected tag(s) are assigned to articles",
        );
    }

    public static function articlesPublished(int $count): void
    {
        self::success(
            'Articles published',
            "{$count} article(s) were set to Published",
        );
    }

    public static function articlesMovedToDraft(int $count): void
    {
        self::success(
            'Articles moved to draft',
            "{$count} article(s) were set to Draft",
        );
    }
}
