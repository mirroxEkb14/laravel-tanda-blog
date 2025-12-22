<?php

namespace App\Http\Controllers\Api\Blog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogArticle;
use App\Models\BlogCategory;
use App\Models\BlogTag;

class BlogController extends Controller
{
    /**
     * Represents GET /api/blog/articles.
     * 
     * Base query: only publicly visible articles.
     * Filter by category slug.
     * ilter by tag slug.
     * Filter by institution type context.
     * Basic text search.
     * Safe per_page bounds to prevent heavy queries.
     */
    public function articles(Request $request)
    {
        $query = BlogArticle::query()
            ->with(['category:id,name,slug', 'tags:id,name,slug', 'author:id,name'])
            ->published();

        if ($categorySlug = $request->string('category')->toString()) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $categorySlug));
        }

        if ($tagSlug = $request->string('tag')->toString()) {
            $query->whereHas('tags', fn ($q) => $q->where('slug', $tagSlug));
        }

        if ($type = $request->string('type')->toString()) {
            $query->whereJsonContains('related_types', $type);
        }

        if ($search = $request->string('search')->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        $articles = $query
            ->orderByDesc('publish_at')
            ->paginate(perPage: min(50, max(1, (int) $request->get('per_page', 12))));

        return response()->json($articles);
    }

    /**
     * Represents GET /api/blog/articles/{slug}.
     * 
     * Increments views_count on open to track popularity.
     * Optional relations are kept as JSON arrays until integrated into the main Institution domain
     *      ('related_types', 'related_institutions').
     * Optional SEO overrides (frontend should fallback to title/excerpt if empty) ('seo').
     */
    public function articleBySlug(string $slug)
    {
        $article = BlogArticle::query()
            ->with(['category:id,name,slug', 'tags:id,name,slug', 'author:id,name'])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        $article->increment('views_count');

        return response()->json([
            'id' => $article->id,
            'title' => $article->title,
            'slug' => $article->slug,
            'excerpt' => $article->excerpt,
            'content' => $article->content,
            'cover_image' => $article->cover_image,
            'reading_time' => $article->reading_time,
            'publish_at' => optional($article->publish_at)?->toDateTimeString(),
            'views_count' => $article->views_count,
            'category' => $article->category,
            'tags' => $article->tags,
            'author' => $article->author,
            'related_types' => $article->related_types,
            'related_institutions' => $article->related_institutions,
            'seo' => [
                'title' => $article->seo_title,
                'description' => $article->seo_description,
                'keywords' => $article->seo_keywords,
                'canonical_url' => $article->canonical_url,
            ],
        ]);
    }

    /**
     * Represents GET /api/blog/categories.
     */
    public function categories()
    {
        return response()->json(
            BlogCategory::query()
                ->select(['id', 'name', 'slug'])
                ->orderBy('name')
                ->get()
        );
    }

    /**
     * Represents GET /api/blog/tags.
     */
    public function tags()
    {
        return response()->json(
            BlogTag::query()
                ->select(['id', 'name', 'slug'])
                ->orderBy('name')
                ->get()
        );
    }

    /**
     * Represents GET /api/blog/articles/{id}/related.
     * 
     * Starts with same category as the strongest signal.
     * Expands matches by shared tags / related types (OR conditions).
     */
    public function related(int $id)
    {
        $article = BlogArticle::query()->with(['tags:id', 'category:id'])->findOrFail($id);

        $tagIds = $article->tags->pluck('id')->all();
        $types = $article->related_types ?? [];

        $query = BlogArticle::query()
            ->published()
            ->where('id', '!=', $article->id)
            ->with(['category:id,name,slug', 'tags:id,name,slug'])
            ->when($article->category_id, fn ($q) => $q->where('category_id', $article->category_id))
            ->when(!empty($tagIds), fn ($q) => $q->orWhereHas('tags', fn ($t) => $t->whereIn('blog_tags.id', $tagIds)))
            ->when(!empty($types), fn ($q) => $q->orWhere(function ($qq) use ($types) {
                foreach ($types as $type) {
                    $qq->orWhereJsonContains('related_types', $type);
                }
            }));

        $items = $query
            ->orderByDesc('publish_at')
            ->orderByDesc('views_count')
            ->limit(6)
            ->get();

        return response()->json($items);
    }
}
