<?php

namespace App\Console\Commands;

use App\Models\CalendarEvent;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendUpcomingEventNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calendar:notify-upcoming';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications for upcoming calendar events';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Checking for upcoming events...');

        // Get events happening in the next 24 hours that haven't been notified
        $upcomingEvents = CalendarEvent::where('status', 'pending')
            ->where('event_date', '>', now())
            ->where('event_date', '<=', now()->addDay())
            ->whereNull('reminder_sent_at')
            ->with('user')
            ->get();

        $this->info("Found {$upcomingEvents->count()} upcoming events");

        $notifiedCount = 0;

        foreach ($upcomingEvents as $event) {
            try {
                // Check if user has reminder_date set
                if ($event->reminder_date && $event->reminder_date > now()) {
                    // Skip if reminder is not yet due
                    continue;
                }

                // Send notification
                $this->sendNotification($event);

                // Mark as notified
                $event->update([
                    'metadata' => array_merge($event->metadata ?? [], [
                        'reminder_sent_at' => now()->toIso8601String(),
                    ]),
                ]);

                $notifiedCount++;
                $this->info("Notified: {$event->title} for user {$event->user->name}");
            } catch (\Exception $e) {
                Log::error('Error sending event notification', [
                    'event_id' => $event->id,
                    'error' => $e->getMessage(),
                ]);
                $this->error("Error notifying event {$event->id}: {$e->getMessage()}");
            }
        }

        $this->info("Successfully sent {$notifiedCount} notifications");

        return Command::SUCCESS;
    }

    /**
     * Send notification to user
     */
    protected function sendNotification(CalendarEvent $event): void
    {
        $user = $event->user;

        if (!$user) {
            return;
        }

        $title = 'Recordatorio: ' . $event->title;
        $body = $this->buildNotificationBody($event);

        // You can use Laravel's notification system here
        // or integrate with Firebase Cloud Messaging for push notifications
        // For now, we'll just create a notification record in the database

        $user->notifications()->create([
            'type' => 'App\\Notifications\\CalendarEventReminder',
            'data' => [
                'event_id' => $event->id,
                'title' => $title,
                'body' => $body,
                'event_type' => $event->event_type,
                'event_date' => $event->event_date->toIso8601String(),
            ],
            'read_at' => null,
        ]);

        Log::info('Calendar event notification created', [
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);
    }

    /**
     * Build notification body text
     */
    protected function buildNotificationBody(CalendarEvent $event): string
    {
        $timeUntil = now()->diffForHumans($event->event_date, [
            'parts' => 2,
            'syntax' => Carbon::DIFF_ABSOLUTE,
        ]);

        $body = "Tu evento está programado en {$timeUntil}";

        if ($event->description) {
            $body .= "\n\n" . \Illuminate\Support\Str::limit($event->description, 100);
        }

        return $body;
    }
}
