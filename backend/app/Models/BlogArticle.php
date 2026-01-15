<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Enums\BlogArticleStatusEnum;

class BlogArticle extends Model
{
    // mass-assignable attributes (passed in one array)
    protected $fillable = [
        'sort_order',
        'category_id',
        'author_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'status',
        'featured',
        'publish_at',
        'reading_time',
        'views_count',
        'cover_image',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'canonical_url',
    ];

    // attribute casting (automatic type conversion from DB to PHP)
    protected $casts = [
        'publish_at' => 'datetime',
        'featured' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(
            BlogTag::class,
            'blog_article_tag',
            'blog_article_id',
            'blog_tag_id'
        );
    }

    /**
     * Gets published articles (used in Controller for API).
     *
     * If 'published' has 'published_at' as null, then visible.
     * If 'published' has 'published_at' in the past, then visible.
     * If 'published' has 'published_at' in the future, then not visible.
     * If 'scheduled' has 'published_at' as any, then not visible.
     * If 'draft' has 'published_at' as any, then not visible.
     */
    public function scopePublished($query)
    {
        return $query->where('status', BlogArticleStatusEnum::Published->value)
            ->where(function ($q) {
                $q->whereNull('publish_at')->orWhere('publish_at', '<=', now());
            });
    }

    /**
     * Gets articles that are scheduled to be published (used in Console Publish Command)
     */
    public function scopeReadyToPublish($query)
    {
        return $query
            ->where('status', BlogArticleStatusEnum::Scheduled->value)
            ->whereNotNull('publish_at')
            ->where('publish_at', '<=', now());
    }
}
