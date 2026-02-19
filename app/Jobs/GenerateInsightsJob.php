<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\Insights\InsightsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateInsightsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;
    public $backoff = 30;
    public $timeout = 120;

    public function __construct(
        protected User $user,
        protected ?int $organizationId = null,
        protected bool $clearExisting = false,
    ) {}

    public function handle(InsightsService $service): void
    {
        try {
            $insights = $service->generateInsights(
                user: $this->user,
                organizationId: $this->organizationId,
                clearExisting: $this->clearExisting,
            );

            Log::info('Insights generados', [
                'user_id' => $this->user->id,
                'count' => count($insights),
            ]);
        } catch (\Throwable $e) {
            Log::error('Error generando insights', [
                'user_id' => $this->user->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
