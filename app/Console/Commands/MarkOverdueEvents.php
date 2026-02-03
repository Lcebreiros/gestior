<?php

namespace App\Console\Commands;

use App\Models\CalendarEvent;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MarkOverdueEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calendar:mark-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark pending events as overdue if their due date has passed';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Checking for overdue events...');

        // Get pending events with due_date in the past
        $overdueEvents = CalendarEvent::where('status', 'pending')
            ->where(function ($query) {
                $query->where('due_date', '<', now())
                    ->orWhere(function ($q) {
                        $q->whereNull('due_date')
                          ->where('event_date', '<', now());
                    });
            })
            ->get();

        $this->info("Found {$overdueEvents->count()} overdue events");

        foreach ($overdueEvents as $event) {
            try {
                $event->markAsOverdue();
                $this->info("Marked as overdue: {$event->title}");
            } catch (\Exception $e) {
                Log::error('Error marking event as overdue', [
                    'event_id' => $event->id,
                    'error' => $e->getMessage(),
                ]);
                $this->error("Error marking event {$event->id} as overdue: {$e->getMessage()}");
            }
        }

        $this->info("Successfully marked {$overdueEvents->count()} events as overdue");

        return Command::SUCCESS;
    }
}
