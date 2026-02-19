<?php

namespace App\Services\Insights\Generators;

use App\Models\BusinessInsight;
use App\Models\SupplySchedule;
use App\Models\User;
use App\Services\Insights\InsightGeneratorInterface;

class LowStockInsightGenerator implements InsightGeneratorInterface
{
    public function generate(User $user, ?int $organizationId): array
    {
        $schedules = SupplySchedule::with(['supply', 'supplier'])
            ->where('user_id', $user->id)
            ->when($organizationId, fn ($q) => $q->where('organization_id', $organizationId))
            ->where(function ($q) {
                $q->where('status', 'out_of_stock')
                    ->orWhere('current_stock', '<=', 0)
                    ->orWhereRaw('current_stock <= reorder_level');
            })
            ->get();

        if ($schedules->isEmpty()) {
            return [];
        }

        $insights = [];

        // Insights individuales para los más críticos (out of stock)
        $outOfStock = $schedules->filter(fn ($s) => $s->current_stock <= 0);
        foreach ($outOfStock as $schedule) {
            $supplyName = $schedule->supply?->name ?? 'Insumo #' . $schedule->supply_id;

            $supplierName = $schedule->supplier?->name ?? 'sin proveedor asignado';

            $insights[] = [
                'type' => BusinessInsight::TYPE_STOCK_ALERT,
                'priority' => BusinessInsight::PRIORITY_CRITICAL,
                'title' => "Sin stock: {$supplyName}",
                'description' => "Este insumo esta agotado. Se necesitan al menos {$schedule->reorder_quantity} {$schedule->unit} para reponer.",
                'metadata' => [
                    'supply_id' => $schedule->supply_id,
                    'schedule_id' => $schedule->id,
                    'current_stock' => (float) $schedule->current_stock,
                    'reorder_level' => (float) $schedule->reorder_level,
                    'reorder_quantity' => (float) $schedule->reorder_quantity,
                    'unit' => $schedule->unit,
                    'supplier_name' => $schedule->supplier?->name,
                    'days_until_depleted' => 0,
                    'golden_formula' => [
                        'what' => "{$supplyName} tiene 0 {$schedule->unit} en stock",
                        'compared_to' => "El nivel de reorden es {$schedule->reorder_level} {$schedule->unit}",
                        'impact' => "No podes producir ni entregar pedidos que requieran este insumo",
                        'action' => "Contacta a {$supplierName} y pedi {$schedule->reorder_quantity} {$schedule->unit} urgente",
                    ],
                ],
                'action_label' => 'Ver inventario',
                'action_route' => '/stock',
                'dedupe_key' => "stock_out_{$schedule->supply_id}",
                'expires_at' => now()->addDays(14),
            ];
        }

        // Insights para stock bajo (no agotado)
        $lowStock = $schedules->filter(fn ($s) => $s->current_stock > 0 && $s->current_stock <= $s->reorder_level);
        foreach ($lowStock as $schedule) {
            $supplyName = $schedule->supply?->name ?? 'Insumo #' . $schedule->supply_id;
            $daysUntilDepleted = $this->estimateDaysUntilDepleted($schedule);

            $priority = $daysUntilDepleted !== null && $daysUntilDepleted <= 3
                ? BusinessInsight::PRIORITY_HIGH
                : BusinessInsight::PRIORITY_MEDIUM;

            $daysText = $daysUntilDepleted !== null
                ? " Estimacion: {$daysUntilDepleted} dias hasta agotarse."
                : '';

            $supplierName = $schedule->supplier?->name ?? 'tu proveedor';
            $daysImpact = $daysUntilDepleted !== null && $daysUntilDepleted <= 3
                ? "Se agota en {$daysUntilDepleted} dias, puede frenar produccion"
                : "Todavia tenes margen pero hay que reponer pronto";

            $insights[] = [
                'type' => BusinessInsight::TYPE_STOCK_ALERT,
                'priority' => $priority,
                'title' => "Stock bajo: {$supplyName}",
                'description' => "Quedan {$schedule->current_stock} {$schedule->unit}. Nivel de reorden: {$schedule->reorder_level} {$schedule->unit}.{$daysText}",
                'metadata' => [
                    'supply_id' => $schedule->supply_id,
                    'schedule_id' => $schedule->id,
                    'current_stock' => (float) $schedule->current_stock,
                    'reorder_level' => (float) $schedule->reorder_level,
                    'reorder_quantity' => (float) $schedule->reorder_quantity,
                    'unit' => $schedule->unit,
                    'supplier_name' => $schedule->supplier?->name,
                    'days_until_depleted' => $daysUntilDepleted,
                    'golden_formula' => [
                        'what' => "{$supplyName} tiene {$schedule->current_stock} {$schedule->unit} (por debajo del nivel de reorden)",
                        'compared_to' => "El minimo recomendado es {$schedule->reorder_level} {$schedule->unit}",
                        'impact' => $daysImpact,
                        'action' => "Pedi {$schedule->reorder_quantity} {$schedule->unit} a {$supplierName}",
                    ],
                ],
                'action_label' => 'Ver inventario',
                'action_route' => '/stock',
                'dedupe_key' => "stock_low_{$schedule->supply_id}",
                'expires_at' => now()->addDays(7),
            ];
        }

        // Si hay muchos items con stock bajo, agregar insight resumen
        if ($schedules->count() >= 5) {
            $criticalCount = $outOfStock->count();
            $lowCount = $lowStock->count();

            $insights[] = [
                'type' => BusinessInsight::TYPE_STOCK_ALERT,
                'priority' => $criticalCount > 0 ? BusinessInsight::PRIORITY_HIGH : BusinessInsight::PRIORITY_MEDIUM,
                'title' => "{$schedules->count()} insumos necesitan atencion",
                'description' => "{$criticalCount} agotados y {$lowCount} con stock bajo. Revisa el inventario completo para evitar interrupciones.",
                'metadata' => [
                    'total_alerts' => $schedules->count(),
                    'out_of_stock_count' => $criticalCount,
                    'low_stock_count' => $lowCount,
                ],
                'action_label' => 'Ver inventario',
                'action_route' => '/stock',
                'dedupe_key' => 'stock_summary',
                'expires_at' => now()->addDays(3),
            ];
        }

        return $insights;
    }

    /**
     * Estima días hasta agotarse basado en consumo promedio.
     * Usa reorder_quantity / 30 como estimación de consumo diario.
     */
    private function estimateDaysUntilDepleted(SupplySchedule $schedule): ?int
    {
        if ($schedule->reorder_quantity <= 0) {
            return null;
        }

        // Consumo diario estimado = cantidad de reorden / 30 días
        $dailyConsumption = (float) $schedule->reorder_quantity / 30;

        if ($dailyConsumption <= 0) {
            return null;
        }

        return (int) round((float) $schedule->current_stock / $dailyConsumption);
    }
}
