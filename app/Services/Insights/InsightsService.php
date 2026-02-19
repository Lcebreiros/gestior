<?php

namespace App\Services\Insights;

use App\Models\BusinessInsight;
use App\Models\User;
use App\Services\Insights\Generators\LowStockInsightGenerator;
use App\Services\Insights\Generators\RevenueOpportunityGenerator;
use App\Services\Insights\Generators\CostWarningGenerator;
use App\Services\Insights\Generators\ClientRetentionGenerator;
use App\Services\Insights\Generators\TrendInsightGenerator;
use App\Services\Insights\Generators\ProjectionGenerator;
use App\Services\Insights\Generators\CrossAnalysisGenerator;
use Illuminate\Support\Facades\Log;

class InsightsService
{
    /**
     * Generadores activos registrados.
     *
     * @var array<class-string<InsightGeneratorInterface>>
     */
    protected array $generators = [
        // Nivel 1: Inteligencia Operativa (anomalías + contexto)
        LowStockInsightGenerator::class,
        RevenueOpportunityGenerator::class,
        CostWarningGenerator::class,
        ClientRetentionGenerator::class,
        TrendInsightGenerator::class,
        // Nivel 2: Proyecciones (extrapolación lineal)
        ProjectionGenerator::class,
        // Nivel 3: Análisis Cruzado (conecta variables entre categorías)
        // IMPORTANTE: siempre al final porque analiza métricas globales
        CrossAnalysisGenerator::class,
    ];

    /**
     * Genera insights ejecutando todos los generadores.
     * Usa dedupe_key para evitar duplicados.
     */
    public function generateInsights(User $user, ?int $organizationId = null, bool $clearExisting = false): array
    {
        if ($clearExisting) {
            BusinessInsight::where('user_id', $user->id)
                ->when($organizationId, fn ($q) => $q->forOrganization($organizationId))
                ->delete();
        }

        // Expirar insights viejos
        BusinessInsight::where('user_id', $user->id)
            ->when($organizationId, fn ($q) => $q->forOrganization($organizationId))
            ->where('expires_at', '<', now())
            ->where('is_dismissed', false)
            ->update(['is_dismissed' => true]);

        $createdInsights = [];

        foreach ($this->generators as $generatorClass) {
            try {
                /** @var InsightGeneratorInterface $generator */
                $generator = new $generatorClass();
                $rawInsights = $generator->generate($user, $organizationId);

                foreach ($rawInsights as $insightData) {
                    $insight = $this->createOrSkip($user, $organizationId, $insightData);
                    if ($insight) {
                        $createdInsights[] = $insight;
                    }
                }
            } catch (\Throwable $e) {
                Log::error("Error en generador {$generatorClass}", [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $createdInsights;
    }

    /**
     * Crea un insight solo si no existe uno activo con el mismo dedupe_key.
     */
    protected function createOrSkip(User $user, ?int $organizationId, array $data): ?BusinessInsight
    {
        $dedupeKey = $data['dedupe_key'] ?? null;

        // Si hay dedupe_key, verificar que no exista uno activo
        if ($dedupeKey) {
            $existing = BusinessInsight::where('user_id', $user->id)
                ->when($organizationId, fn ($q) => $q->forOrganization($organizationId))
                ->where('dedupe_key', $dedupeKey)
                ->where('is_dismissed', false)
                ->where(function ($q) {
                    $q->whereNull('expires_at')
                        ->orWhere('expires_at', '>', now());
                })
                ->first();

            if ($existing) {
                // Actualizar metadata si cambió (ej: stock actual)
                if (isset($data['metadata']) && $existing->metadata !== $data['metadata']) {
                    $existing->update([
                        'metadata' => $data['metadata'],
                        'description' => $data['description'],
                        'priority' => $data['priority'],
                        'priority_color' => BusinessInsight::priorityColor($data['priority']),
                    ]);
                }
                return null;
            }
        }

        return BusinessInsight::create([
            'user_id' => $user->id,
            'organization_id' => $organizationId,
            'type' => $data['type'],
            'priority' => $data['priority'],
            'title' => $data['title'],
            'description' => $data['description'],
            'metadata' => $data['metadata'] ?? [],
            'action_label' => $data['action_label'] ?? null,
            'action_route' => $data['action_route'] ?? null,
            'dedupe_key' => $dedupeKey,
            'priority_color' => BusinessInsight::priorityColor($data['priority']),
            'type_icon' => BusinessInsight::typeIcon($data['type']),
            'expires_at' => $data['expires_at'] ?? now()->addDays(7),
        ]);
    }

    /**
     * Obtiene insights filtrados por tipo/prioridad con organization_id.
     */
    public function getInsights(User $user, ?int $organizationId = null, ?string $type = null, ?string $priority = null, int $limit = 20): array
    {
        return BusinessInsight::where('user_id', $user->id)
            ->when($organizationId, fn ($q) => $q->forOrganization($organizationId))
            ->active()
            ->when($type, fn ($q) => $q->ofType($type))
            ->when($priority, fn ($q) => $q->ofPriority($priority))
            ->orderByRaw("FIELD(priority, 'critical', 'high', 'medium', 'low')")
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Descarta un insight con validación de organization_id.
     */
    public function dismissInsight(User $user, int $insightId, ?int $organizationId = null): bool
    {
        $insight = BusinessInsight::where('user_id', $user->id)
            ->when($organizationId, fn ($q) => $q->forOrganization($organizationId))
            ->findOrFail($insightId);

        return $insight->update(['is_dismissed' => true]);
    }

    /**
     * Estadísticas con filtro de organization_id.
     */
    public function getStats(User $user, ?int $organizationId = null): array
    {
        $query = BusinessInsight::where('user_id', $user->id)
            ->when($organizationId, fn ($q) => $q->forOrganization($organizationId))
            ->active();

        $insights = $query->get();

        return [
            'total' => $insights->count(),
            'by_type' => $insights->groupBy('type')->map->count()->toArray(),
            'by_priority' => $insights->groupBy('priority')->map->count()->toArray(),
        ];
    }
}
