<?php

namespace App\Enums;

enum BlogArticleStatus: string
{
    case Draft = 'draft';
    case Scheduled = 'scheduled';
    case Published = 'published';

    public function label(): string
    {
        return match ($this) {
            self::Draft => __('blog.status.draft'),
            self::Scheduled => __('blog.status.scheduled'),
            self::Published => __('blog.status.published'),
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn ($case) => [$case->value => $case->label()])
            ->toArray();
    }
}
