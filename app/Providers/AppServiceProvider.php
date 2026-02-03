<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\CalendarEvent;
use App\Observers\CalendarEventObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register CalendarEvent observer for Google Calendar sync
        CalendarEvent::observe(CalendarEventObserver::class);
    }
}
