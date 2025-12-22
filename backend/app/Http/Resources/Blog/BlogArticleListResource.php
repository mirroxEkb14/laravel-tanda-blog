<?php

namespace App\Http\Resources\Blog;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Resource is used for article listings
 */
class BlogArticleListResource extends JsonResource
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
            'cover_image' => $this->cover_image,
            'reading_time' => $this->reading_time,
            'publish_at' => optional($this->publish_at)?->toDateString(),
        ];
    }
}
