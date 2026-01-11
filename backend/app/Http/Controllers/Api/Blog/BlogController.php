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
     * Returns a paginated list of published articles with optional filters.
     * 
     * Base query: only publicly visible articles.
     * Filter by category slug ('?category=schools').
     * Filter by tag slug ('?tag=education').
     * Basic text search ('?search=private').
     * Safe 'per_page' bounds to prevent heavy queries (like 'per_page=10000' that'd kill DB) (?page=1&per_page=12).
     * 
     * Paginated list returns records in pages, each page containing a fixed number of items (not all records at once).
     * Basically, instead of “get all 10 000 articles”, this returns “get page 1, with 12 articles per page”,
     *      “now get page 2, again 12 articles” etc.
     * 'per_page' is set to: default = 12, minimum = 1, maximum = 50.
     * "meta" contains information about the pagination (not the articles themselves).
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

        return $this->paginatedResponse($articles, BlogArticleListResource::class);
    }

    /**
     * Represents GET /api/blog/articles/{slug}.
     * Returns one published article by slug.
     * 
     * Increments 'views_count' on open to track popularity.
     * "->firstOrFail" ensures 404 if not found or not published.
     */
    public function articleBySlug(string $slug)
    {
        $article = BlogArticle::query()
            ->with(['category:id,name,slug', 'tags:id,name,slug', 'author:id,name'])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        $article->increment('views_count');
        return new BlogArticleResource($article);
    }

    /**
     * Represents GET /api/blog/categories.
     * Returns all categories ordered by name.
     */
    public function categories()
    {
        return BlogCategoryResource::collection(
            BlogCategory::query()->orderBy('name')->get()
        );
    }

    /**
     * Represents GET /api/blog/tags.
     * Returns all tags ordered by name.
     */
    public function tags()
    {
        return BlogTagResource::collection(
            BlogTag::query()->orderBy('name')->get()
        );
    }

    /**
     * Represents GET /api/blog/articles/{id}/related.
     * Returns up to 6 related published articles for a given article ID: (same category) OR (shares tags).
     */
    public function related(int $id)
    {
        $article = BlogArticle::query()->with(['tags:id', 'category:id'])->findOrFail($id);

        $tagIds = $article->tags->pluck('id')->all();

        $query = BlogArticle::query()
            ->published()
            ->where('id', '!=', $article->id)
            ->with(['category:id,name,slug', 'tags:id,name,slug'])
            ->where(function ($q) use ($article, $tagIds) {
                $q->when($article->category_id, fn ($qq) => $qq->where('category_id', $article->category_id))
                ->when(! empty($tagIds), fn ($qq) => $qq->orWhereHas('tags', fn ($t) => $t->whereIn('blog_tags.id', $tagIds)));
            });

        $articles = $query
            ->orderByDesc('publish_at')
            ->paginate(perPage: 6);

        return $this->paginatedResponse($articles, BlogArticleListResource::class);
    }

    /**
     * Formats a paginated response for 'articles' and 'related'
     */
    protected function paginatedResponse($paginator, $resource)
    {
        return response()->json([
            'data' => $resource::collection($paginator->items()),
            'meta' => [
                'page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
            ],
        ]);
    }
}
