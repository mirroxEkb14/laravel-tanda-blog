<?php

namespace App\Observers;

use App\Models\BlogArticle;
use Illuminate\Support\Str;

/**
 * Observer holds lifecycle rules and runs on save (create/update) to keep data consistent.
 * 
 * Ensures slug exists for SEO-friendly routes (auto-fill if not set manually).
 * Calculates reading time from HTML content (HTML -> plain text -> word count -> minutes (approx. 200 wpm)).
 * Ensures scheduling safety ("scheduled" without "publish_at" is invalid => fallback to draft).
 * If an editor sets "published" with a future "publish_at", treats it as "scheduled" until "publish_at" time arrives.
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

        if ($article->status === 'scheduled' && blank($article->publish_at)) {
            $article->status = 'draft';
        }

        if ($article->status === 'published' && filled($article->publish_at) && $article->publish_at->isFuture()) {
            $article->status = 'scheduled';
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
