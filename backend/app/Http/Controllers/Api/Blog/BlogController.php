<?php

namespace App\Http\Controllers\Api\Blog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogArticle;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Http\Resources\Blog\BlogArticleListResource;
use App\Http\Resources\Blog\BlogArticleResource;
use App\Http\Resources\Blog\BlogCategoryResource;
use App\Http\Resources\Blog\BlogTagResource;

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

        $perPage = min(50, max(1, (int) $request->get('per_page', 12)));
        $articles = $query
            ->orderByDesc('publish_at')
            ->paginate(perPage: $perPage);

        return response()->json([
            'data' => BlogArticleListResource::collection($articles->items()),
            'meta' => [
                'page' => $articles->currentPage(),
                'per_page' => $articles->perPage(),
                'total' => $articles->total(),
                'last_page' => $articles->lastPage(),
            ],
        ]);
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
        return new BlogArticleResource($article->load(['category', 'tags', 'author']));
    }

    /**
     * Represents GET /api/blog/categories.
     */
    public function categories()
    {
        return BlogCategoryResource::collection(
            BlogCategory::query()->orderBy('name')->get()
        );
    }

    /**
     * Represents GET /api/blog/tags.
     */
    public function tags()
    {
        return BlogTagResource::collection(
            BlogTag::query()->orderBy('name')->get()
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

        return BlogArticleListResource::collection($items);
    }
}
