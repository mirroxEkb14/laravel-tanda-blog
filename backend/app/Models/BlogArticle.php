<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BlogArticle extends Model
{
    protected $table = 'blog_articles';

    // mass-assignable attributes (in bulk)
    protected $fillable = [
        'category_id',
        'author_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'status',
        'publish_at',
        'reading_time',
        'views_count',
        'cover_image',
        'related_types',
        'related_institutions',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'canonical_url',
    ];

    // attribute casting (automatic type conversion)
    protected $casts = [
        'publish_at' => 'datetime',
        'related_types' => 'array',
        'related_institutions' => 'array',
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

    // reusable query fragment gets published articles
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where(function ($q) {
                $q->whereNull('publish_at')->orWhere('publish_at', '<=', now());
            });
    }
}
