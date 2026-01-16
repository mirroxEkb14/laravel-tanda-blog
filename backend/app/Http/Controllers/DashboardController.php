<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\BlogArticle;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $articles = BlogArticle::query()
            ->published()
            ->with('author')
            ->orderByDesc('publish_at')
            ->get();

        return view('dashboard.index', [
            'articles' => $articles,
        ]);
    }

    public function showArticle(string $slug): View
    {
        $article = BlogArticle::query()
            ->published()
            ->with('author')
            ->where('slug', $slug)
            ->firstOrFail();

        return view('dashboard.article', [
            'article' => $article,
        ]);
    }

    public function showAuthor(User $user): View
    {
        $articles = BlogArticle::query()
            ->published()
            ->with('author')
            ->where('author_id', $user->id)
            ->orderByDesc('publish_at')
            ->get();

        return view('dashboard.author', [
            'author' => $user,
            'articles' => $articles,
        ]);
    }
}
