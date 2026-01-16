<?php

use Dedoc\Scramble\Scramble;
use Illuminate\Support\Facades\Route;

Scramble::registerUiRoute('docs/swagger');
Scramble::registerJsonRoute('docs/swagger.json');

Route::get('/', function () {
    return view('welcome');
});
