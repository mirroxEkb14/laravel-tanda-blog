<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Blog\BlogController;

Route::prefix('blog')->group(function () {
    Route::get('articles', [BlogController::class, 'articles']);
    Route::get('articles/featured', [BlogController::class, 'featured']);
    Route::get('articles/{id}/related', [BlogController::class, 'related'])->whereNumber('id');
    Route::get('articles/{slug}', [BlogController::class, 'articleBySlug']);
    Route::get('categories', [BlogController::class, 'categories']);
    Route::get('tags', [BlogController::class, 'tags']);
});
