<?php

use Dedoc\Scramble\Scramble;
use Illuminate\Support\Facades\Route;

Scramble::registerUiRoutes('docs/swagger');
Scramble::registerJsonRoutes('docs/swagger.json');

Route::get('/', function () {
    return view('welcome');
});
