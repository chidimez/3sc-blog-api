<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Console\Scheduling\Schedule;
use function Laravel\Prompts\info;


app(Schedule::class)->command('posts:publish-scheduled-posts')->everyMinute();

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
