<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Schedule calendar tasks
Schedule::command('calendar:notify-upcoming')->hourly();
Schedule::command('calendar:mark-overdue')->hourly();

// Schedule stock checks
Schedule::command('stock:check-low')->everyThreeHours();
