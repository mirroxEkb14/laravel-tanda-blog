<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// scheduled publishing command runs every minute, one at a time, and only on one server
Schedule::command('blog:publish-scheduled')
    ->everyMinute()
    ->withoutOverlapping()
    ->onOneServer();
