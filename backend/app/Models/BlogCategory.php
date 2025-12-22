<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogCategory extends Model
{
    protected $table = 'blog_categories';

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
}
