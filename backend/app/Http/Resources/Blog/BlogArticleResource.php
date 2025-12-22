<?php

namespace App\Http\Resources\Blog;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource represents the canonical representation of a single article page
 */
class BlogArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'cover_image' => $this->cover_image,
            'reading_time' => $this->reading_time,
            'publish_at' => optional($this->publish_at)?->toDateTimeString(),
            'views_count' => $this->views_count,
            'category' => $this->whenLoaded('category'),
            'tags' => $this->whenLoaded('tags'),
            'author' => $this->whenLoaded('author'),
            'related_institutions' => $this->related_institutions,
            'related_types' => $this->related_types,
            'seo' => [
                'title' => $this->seo_title,
                'description' => $this->seo_description,
                'keywords' => $this->seo_keywords,
                'canonical_url' => $this->canonical_url,
            ],
        ];
    }
}
