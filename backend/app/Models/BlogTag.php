<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BlogTag extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(
            BlogArticle::class,
            'blog_article_tag',
            'blog_tag_id',
            'blog_article_id'
        );
    }

    /**
     * Determines if the tag is used by any articles
     */
    public function isUsed(): bool
    {
        if (array_key_exists('articles_count', $this->attributes)) {
            return (int) $this->attributes['articles_count'] > 0;
        }
        return $this->articles()->exists();
    }

    public function deleteBlockReason(): string
    {
        return __('filament.blog.tags.delete_block_reason');
    }
}
