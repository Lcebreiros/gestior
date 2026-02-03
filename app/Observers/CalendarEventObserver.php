<?php

namespace App\Observers;

use App\Jobs\SyncEventToGoogleCalendar;
use App\Models\CalendarEvent;

class CalendarEventObserver
{
    /**
     * Handle the CalendarEvent "created" event.
     */
    public function created(CalendarEvent $event): void
    {
        // Only sync if event has a date and belongs to a user
        if (!$event->event_date || !$event->user_id) {
            return;
        }

        SyncEventToGoogleCalendar::dispatch($event, 'sync');
    }

    /**
     * Handle the CalendarEvent "updated" event.
     */
    public function updated(CalendarEvent $event): void
    {
        // Delete from Google if cancelled
        if ($event->status === 'cancelled' && $event->metadata && isset($event->metadata['google_event_id'])) {
            SyncEventToGoogleCalendar::dispatch($event, 'delete');
            return;
        }

        // Delete if event no longer has a date
        if (!$event->event_date && $event->metadata && isset($event->metadata['google_event_id'])) {
            SyncEventToGoogleCalendar::dispatch($event, 'delete');
            return;
        }

        // Sync if event still has a date and user
        if ($event->event_date && $event->user_id) {
            SyncEventToGoogleCalendar::dispatch($event, 'sync');
        }
    }

    /**
     * Handle the CalendarEvent "deleted" event.
     */
    public function deleted(CalendarEvent $event): void
    {
        if ($event->metadata && isset($event->metadata['google_event_id'])) {
            SyncEventToGoogleCalendar::dispatch($event, 'delete');
        }
    }

    /**
     * Handle the CalendarEvent "restored" event.
     */
    public function restored(CalendarEvent $event): void
    {
        if ($event->event_date && $event->user_id) {
            SyncEventToGoogleCalendar::dispatch($event, 'sync');
        }
    }

    /**
     * Handle the CalendarEvent "force deleted" event.
     */
    public function forceDeleted(CalendarEvent $event): void
    {
        if ($event->metadata && isset($event->metadata['google_event_id'])) {
            SyncEventToGoogleCalendar::dispatch($event, 'delete');
        }
    }
}
