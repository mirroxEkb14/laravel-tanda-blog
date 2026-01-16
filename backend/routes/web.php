<?php

use Dedoc\Scramble\Scramble;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::prefix('docs')->group(function () {
    Scramble::registerUiRoute('swagger');
    Scramble::registerJsonSpecificationRoute('swagger.json');
});

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
    Route::get('/dashboard/articles/{slug}', [DashboardController::class, 'showArticle'])
        ->name('dashboard.articles.show');
    Route::get('/dashboard/authors/{user}', [DashboardController::class, 'showAuthor'])
        ->name('dashboard.authors.show');
});

require __DIR__.'/auth.php';
