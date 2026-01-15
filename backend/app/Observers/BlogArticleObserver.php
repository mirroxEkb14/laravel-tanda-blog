<?php

namespace App\Observers;

use App\Models\BlogArticle;
use Illuminate\Support\Str;
use App\Enums\BlogArticleStatusEnum;

/**
 * Observer holds lifecycle rules and runs on save (create/update) to keep data consistent.
 *
 * Auto-generates slug if missing.
 * Calculates reading time from HTML content (HTML -> plain text -> word count -> minutes (approx. 200 wpm)),
 *      ensures at least 1 minute if there is content and rounds up (201 words -> 2 minutes).
 * Ensures scheduling safety ("scheduled" without "publish_at" is invalid => fallback to draft).
 * If "published" is set with a future "publish_at", treats it as "scheduled" until "publish_at" time arrives.
 */
class BlogArticleObserver
{
    public function saving(BlogArticle $article): void
    {
        if (blank($article->slug) && filled($article->title)) {
            $article->slug = Str::slug($article->title);
        }

        if (filled($article->content)) {
            $text = trim(strip_tags($article->content));
            $words = str_word_count($text);
            $minutes = (int) ceil(max(1, $words / 200));
            $article->reading_time = $minutes;
        } else {
            $article->reading_time = 0;
        }

        if ($article->status === BlogArticleStatusEnum::Scheduled->value && blank($article->publish_at)) {
            $article->status = BlogArticleStatusEnum::Draft->value;
        }

        if (
            $article->status === BlogArticleStatusEnum::Published->value
            && filled($article->publish_at)
            && $article->publish_at->isFuture()
        ) {
            $article->status = BlogArticleStatusEnum::Scheduled->value;
        }
    }

    /**
     * Handle the BlogArticle "created" event.
     */
    public function created(BlogArticle $blogArticle): void
    {
        //
    }

    /**
     * Handle the BlogArticle "updated" event.
     */
    public function updated(BlogArticle $blogArticle): void
    {
        //
    }

    /**
     * Handle the BlogArticle "deleted" event.
     */
    public function deleted(BlogArticle $blogArticle): void
    {
        //
    }

    /**
     * Handle the BlogArticle "restored" event.
     */
    public function restored(BlogArticle $blogArticle): void
    {
        //
    }

    /**
     * Handle the BlogArticle "force deleted" event.
     */
    public function forceDeleted(BlogArticle $blogArticle): void
    {
        //
    }
}
