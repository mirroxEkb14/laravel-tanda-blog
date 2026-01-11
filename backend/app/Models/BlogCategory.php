<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'seo_title',
        'seo_description',
    ];

    public function articles(): HasMany
    {
        return $this->hasMany(BlogArticle::class, 'category_id');
    }

    /**
     * Determines if this category's used by at least one article
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
        return 'Нельзя удалить категорию, используемую в статьях блога';
    }
}
