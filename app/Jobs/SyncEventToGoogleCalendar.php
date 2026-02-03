<?php

namespace App\Jobs;

use App\Models\CalendarEvent;
use App\Services\GoogleCalendarService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncEventToGoogleCalendar implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = 10;

    protected CalendarEvent $event;
    protected string $action;

    /**
     * Create a new job instance.
     */
    public function __construct(CalendarEvent $event, string $action = 'sync')
    {
        $this->event = $event;
        $this->action = $action; // 'sync' or 'delete'
    }

    /**
     * Execute the job.
     */
    public function handle(GoogleCalendarService $googleCalendar): void
    {
        if ($this->action === 'delete') {
            $this->deleteFromGoogleCalendar($googleCalendar);
            return;
        }

        $this->syncToGoogleCalendar($googleCalendar);
    }

    /**
     * Sync event to Google Calendar
     */
    protected function syncToGoogleCalendar(GoogleCalendarService $googleCalendar): void
    {
        // Validate event has required data
        if (!$this->event->user || !$this->event->event_date) {
            return;
        }

        $googleCalendar->forUser($this->event->user);

        // Check if user is connected and sync is enabled
        if (!$googleCalendar->isConnected() || !$this->event->user->google_calendar_sync_enabled) {
            return;
        }

        // Build event details
        $summary = $this->event->title;
        $description = $this->event->description ?? '';

        // Add metadata to description
        if ($this->event->metadata) {
            $description .= "\n\nTipo: " . $this->event->eventType->displayName;
        }

        $location = $this->event->metadata['location'] ?? null;

        // Calculate end time (1 hour after start if no due_date)
        $startDate = new \DateTime($this->event->event_date->format('Y-m-d H:i:s'));
        $endDate = $this->event->due_date
            ? new \DateTime($this->event->due_date->format('Y-m-d H:i:s'))
            : (clone $startDate)->modify('+1 hour');

        try {
            // Update existing event or create new one
            if ($this->event->metadata && isset($this->event->metadata['google_event_id'])) {
                $event = $googleCalendar->updateEvent(
                    $this->event->metadata['google_event_id'],
                    $summary,
                    $startDate,
                    $endDate,
                    $description,
                    $location
                );
            } else {
                $event = $googleCalendar->createEvent(
                    $summary,
                    $startDate,
                    $endDate,
                    $description,
                    $location,
                    $this->event->event_type->value
                );

                if ($event) {
                    // Store Google event ID in metadata
                    $metadata = $this->event->metadata ?? [];
                    $metadata['google_event_id'] = $event->getId();

                    $this->event->update(['metadata' => $metadata]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error syncing event to Google Calendar', [
                'event_id' => $this->event->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Delete event from Google Calendar
     */
    protected function deleteFromGoogleCalendar(GoogleCalendarService $googleCalendar): void
    {
        if (!$this->event->user || !$this->event->metadata || !isset($this->event->metadata['google_event_id'])) {
            return;
        }

        $googleCalendar->forUser($this->event->user);

        if (!$googleCalendar->isConnected()) {
            return;
        }

        try {
            $googleCalendar->deleteEvent($this->event->metadata['google_event_id']);

            // Remove Google event ID from metadata
            $metadata = $this->event->metadata;
            unset($metadata['google_event_id']);
            $this->event->update(['metadata' => $metadata]);
        } catch (\Exception $e) {
            Log::error('Error deleting event from Google Calendar', [
                'event_id' => $this->event->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
