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
        self::success('Статья переведена в черновик');
    }

    public static function categoryUpdated(): void
    {
        self::success(
            'Категория обновлена',
            'Выбранная категория была успешно обновлена',
        );
    }

    public static function cannotDeleteCategories(int $usedCount): void
    {
        self::danger(
            'Невозможно удалить выбранные категории',
            "{$usedCount} выбранная(ых) категория(ии) назначена(ы) статьям",
        );
    }

    public static function cannotDeleteTags(int $usedCount): void
    {
        self::danger(
            'Невозможно удалить выбранные теги',
            "{$usedCount} выбранный(ых) тег(ов) назначен(ы) статьям",
        );
    }

    public static function articlesPublished(int $count): void
    {
        self::success(
            'Статьи опубликованы',
            "{$count} статья(ей) была(и) успешно опубликована(ы)",
        );
    }

    public static function articlesMovedToDraft(int $count): void
    {
        self::success(
            'Статьи переведены в черновик',
            "{$count} статья(ей) была(и) успешно переведена(ы) в черновик",
        );
    }
}
