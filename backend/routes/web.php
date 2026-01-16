<?php

use Dedoc\Scramble\Scramble;
use Illuminate\Support\Facades\Route;

Route::prefix('docs')->group(function () {
    Scramble::registerUiRoute('swagger');
    Scramble::registerJsonSpecificationRoute('swagger.json');
});

Route::get('/', function () {
    return view('welcome');
});

Route::view('/dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__.'/auth.php';
