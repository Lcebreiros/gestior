<?php

namespace App\Console\Commands;

use App\Models\Supply;
use App\Models\SupplySchedule;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckLowStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stock:check-low';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for low stock supplies and send notifications';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Checking for low stock supplies...');

        // Get all supply schedules with low stock
        $lowStockSchedules = SupplySchedule::with(['supply', 'supplier', 'user'])
            ->where(function ($query) {
                $query->where('status', 'low_stock')
                    ->orWhereRaw('current_stock <= reorder_level');
            })
            ->get();

        $this->info("Found {$lowStockSchedules->count()} low stock items");

        $notifiedCount = 0;

        foreach ($lowStockSchedules as $schedule) {
            try {
                // Check if already notified recently (within last 24 hours)
                $metadata = $schedule->metadata ?? [];
                $lastNotified = isset($metadata['low_stock_notified_at'])
                    ? \Carbon\Carbon::parse($metadata['low_stock_notified_at'])
                    : null;

                if ($lastNotified && $lastNotified->diffInHours(now()) < 24) {
                    // Skip, already notified recently
                    continue;
                }

                // Send notification
                $this->sendLowStockNotification($schedule);

                // Update metadata
                $metadata['low_stock_notified_at'] = now()->toIso8601String();
                $schedule->update(['metadata' => $metadata]);

                $notifiedCount++;
                $this->info("Notified low stock: {$schedule->supply->name}");
            } catch (\Exception $e) {
                Log::error('Error sending low stock notification', [
                    'schedule_id' => $schedule->id,
                    'error' => $e->getMessage(),
                ]);
                $this->error("Error notifying schedule {$schedule->id}: {$e->getMessage()}");
            }
        }

        // Check for out of stock
        $outOfStockSchedules = SupplySchedule::with(['supply', 'supplier', 'user'])
            ->where(function ($query) {
                $query->where('status', 'out_of_stock')
                    ->orWhere('current_stock', '<=', 0);
            })
            ->get();

        $this->info("Found {$outOfStockSchedules->count()} out of stock items");

        foreach ($outOfStockSchedules as $schedule) {
            try {
                $metadata = $schedule->metadata ?? [];
                $lastNotified = isset($metadata['out_of_stock_notified_at'])
                    ? \Carbon\Carbon::parse($metadata['out_of_stock_notified_at'])
                    : null;

                if ($lastNotified && $lastNotified->diffInHours(now()) < 24) {
                    continue;
                }

                $this->sendOutOfStockNotification($schedule);

                $metadata['out_of_stock_notified_at'] = now()->toIso8601String();
                $schedule->update(['metadata' => $metadata]);

                $notifiedCount++;
                $this->info("Notified out of stock: {$schedule->supply->name}");
            } catch (\Exception $e) {
                Log::error('Error sending out of stock notification', [
                    'schedule_id' => $schedule->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("Successfully sent {$notifiedCount} stock notifications");

        return Command::SUCCESS;
    }

    /**
     * Send low stock notification
     */
    protected function sendLowStockNotification(SupplySchedule $schedule): void
    {
        $user = $schedule->user;

        if (!$user) {
            return;
        }

        $title = '⚠️ Stock Bajo: ' . $schedule->supply->name;
        $body = "Quedan solo {$schedule->current_stock} {$schedule->unit}. Nivel de reorden: {$schedule->reorder_level} {$schedule->unit}.";

        if ($schedule->supplier) {
            $body .= "\n\nProveedor: {$schedule->supplier->name}";
        }

        $body .= "\n\nCantidad sugerida a pedir: {$schedule->reorder_quantity} {$schedule->unit}";

        $user->notifications()->create([
            'type' => 'App\\Notifications\\LowStockAlert',
            'data' => [
                'schedule_id' => $schedule->id,
                'supply_id' => $schedule->supply_id,
                'supply_name' => $schedule->supply->name,
                'title' => $title,
                'body' => $body,
                'current_stock' => $schedule->current_stock,
                'reorder_level' => $schedule->reorder_level,
                'reorder_quantity' => $schedule->reorder_quantity,
                'unit' => $schedule->unit,
                'severity' => 'warning',
            ],
            'read_at' => null,
        ]);

        Log::info('Low stock notification created', [
            'user_id' => $user->id,
            'schedule_id' => $schedule->id,
            'supply_name' => $schedule->supply->name,
        ]);
    }

    /**
     * Send out of stock notification
     */
    protected function sendOutOfStockNotification(SupplySchedule $schedule): void
    {
        $user = $schedule->user;

        if (!$user) {
            return;
        }

        $title = '🚨 Sin Stock: ' . $schedule->supply->name;
        $body = "¡URGENTE! Este insumo está agotado.";

        if ($schedule->supplier) {
            $body .= "\n\nProveedor: {$schedule->supplier->name}";
        }

        $body .= "\n\nCantidad sugerida a pedir: {$schedule->reorder_quantity} {$schedule->unit}";

        $user->notifications()->create([
            'type' => 'App\\Notifications\\OutOfStockAlert',
            'data' => [
                'schedule_id' => $schedule->id,
                'supply_id' => $schedule->supply_id,
                'supply_name' => $schedule->supply->name,
                'title' => $title,
                'body' => $body,
                'reorder_quantity' => $schedule->reorder_quantity,
                'unit' => $schedule->unit,
                'severity' => 'critical',
            ],
            'read_at' => null,
        ]);

        Log::info('Out of stock notification created', [
            'user_id' => $user->id,
            'schedule_id' => $schedule->id,
            'supply_name' => $schedule->supply->name,
        ]);
    }
}
