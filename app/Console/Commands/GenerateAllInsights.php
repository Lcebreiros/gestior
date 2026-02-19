<?php

namespace App\Console\Commands;

use App\Jobs\GenerateInsightsJob;
use App\Models\User;
use Illuminate\Console\Command;

class GenerateAllInsights extends Command
{
    protected $signature = 'insights:generate-all';
    protected $description = 'Genera insights de negocio para todos los usuarios activos';

    public function handle(): int
    {
        $users = User::where('is_active', true)->get();

        $count = 0;
        foreach ($users as $user) {
            GenerateInsightsJob::dispatch($user);
            $count++;
        }

        $this->info("Dispatched insight generation for {$count} users.");

        return self::SUCCESS;
    }
}
