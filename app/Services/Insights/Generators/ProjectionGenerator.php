<?php

namespace App\Services\Insights\Generators;

use App\Models\BusinessInsight;
use App\Models\Expense;
use App\Models\Order;
use App\Models\SupplySchedule;
use App\Models\User;
use App\Services\Insights\InsightGeneratorInterface;
use Illuminate\Support\Facades\DB;

/**
 * Nivel 2+: Proyecciones simples.
 *
 * Extrapola tendencias actuales para generar predicciones:
 * "A este ritmo, en X días/semanas..."
 *
 * No usa machine learning - usa extrapolación lineal sobre datos reales.
 */
class ProjectionGenerator implements InsightGeneratorInterface
{
    public function generate(User $user, ?int $organizationId): array
    {
        return array_merge(
            $this->revenueProjection($user, $organizationId),
            $this->stockDepletionProjection($user, $organizationId),
            $this->monthEndProjection($user, $organizationId),
            $this->clientChurnProjection($user, $organizationId),
        );
    }

    /**
     * Proyección de ingresos: a este ritmo, cuánto facturarás este mes.
     */
    private function revenueProjection(User $user, ?int $organizationId): array
    {
        $dayOfMonth = (int) now()->format('j');
        $daysInMonth = (int) now()->format('t');

        // Necesitamos al menos 7 días de datos para proyectar
        if ($dayOfMonth < 7) {
            return [];
        }

        $currentRevenue = (float) Order::where('user_id', $user->id)
            ->when($organizationId, fn ($q) => $q->where('organization_id', $organizationId))
            ->where('payment_status', 'paid')
            ->whereBetween('order_date', [now()->startOfMonth(), now()])
            ->sum('total');

        if ($currentRevenue <= 0) {
            return [];
        }

        $dailyRate = $currentRevenue / $dayOfMonth;
        $projectedRevenue = round($dailyRate * $daysInMonth, 2);
        $remainingDays = $daysInMonth - $dayOfMonth;

        // Comparar con mes anterior completo
        $lastMonthRevenue = (float) Order::where('user_id', $user->id)
            ->when($organizationId, fn ($q) => $q->where('organization_id', $organizationId))
            ->where('payment_status', 'paid')
            ->whereBetween('order_date', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->sum('total');

        if ($lastMonthRevenue <= 0) {
            return [];
        }

        $vsLastMonth = round((($projectedRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1);
        $gap = $projectedRevenue - $lastMonthRevenue;

        // Determinar prioridad según la proyección vs mes anterior
        if (abs($vsLastMonth) < 10) {
            return []; // Proyección similar, no es noticia
        }

        $isPositive = $vsLastMonth > 0;
        $priority = abs($vsLastMonth) > 30
            ? ($isPositive ? BusinessInsight::PRIORITY_LOW : BusinessInsight::PRIORITY_HIGH)
            : BusinessInsight::PRIORITY_MEDIUM;

        $title = $isPositive
            ? "Proyeccion: superarias el mes anterior en +" . round($vsLastMonth) . "%"
            : "Proyeccion: cerrarias " . abs(round($vsLastMonth)) . "% debajo del mes anterior";

        $description = "Llevas \${$this->fmt($currentRevenue)} en {$dayOfMonth} dias. "
            . "A este ritmo (\${$this->fmt($dailyRate)}/dia), "
            . "cerrarias el mes en ~\${$this->fmt($projectedRevenue)}. "
            . ($isPositive
                ? "Eso seria \${$this->fmt(abs($gap))} mas que el mes pasado (\${$this->fmt($lastMonthRevenue)})."
                : "Eso seria \${$this->fmt(abs($gap))} menos que el mes pasado (\${$this->fmt($lastMonthRevenue)}). "
                . "Tenes {$remainingDays} dias para revertir la tendencia.");

        return [[
            'type' => BusinessInsight::TYPE_PREDICTION,
            'priority' => $priority,
            'title' => $title,
            'description' => $description,
            'metadata' => [
                'current_revenue' => $currentRevenue,
                'daily_rate' => round($dailyRate, 2),
                'projected_revenue' => $projectedRevenue,
                'last_month_revenue' => $lastMonthRevenue,
                'vs_last_month_percent' => $vsLastMonth,
                'remaining_days' => $remainingDays,
                'day_of_month' => $dayOfMonth,
                'projection_type' => 'revenue_linear',
            ],
            'action_label' => 'Ver ventas',
            'action_route' => '/orders',
            'dedupe_key' => 'projection_revenue_' . now()->format('Y-m-W'),
            'expires_at' => now()->addDays(7),
        ]];
    }

    /**
     * Proyección de agotamiento de stock: cuándo se agota cada insumo crítico.
     */
    private function stockDepletionProjection(User $user, ?int $organizationId): array
    {
        $schedules = SupplySchedule::with('supply')
            ->where('user_id', $user->id)
            ->when($organizationId, fn ($q) => $q->where('organization_id', $organizationId))
            ->where('current_stock', '>', 0)
            ->whereRaw('current_stock <= reorder_level * 2') // Stock en zona de riesgo
            ->where('reorder_quantity', '>', 0)
            ->get();

        if ($schedules->isEmpty()) {
            return [];
        }

        $criticalItems = [];

        foreach ($schedules as $schedule) {
            $dailyConsumption = (float) $schedule->reorder_quantity / 30;
            if ($dailyConsumption <= 0) {
                continue;
            }

            $daysUntilDepleted = (int) round((float) $schedule->current_stock / $dailyConsumption);
            $depletionDate = now()->addDays($daysUntilDepleted);

            // Solo alertar si se agota en menos de 14 días
            if ($daysUntilDepleted > 14) {
                continue;
            }

            $leadTime = $schedule->lead_time_days ?? 0;
            $willArriveInTime = $leadTime <= $daysUntilDepleted;

            $criticalItems[] = [
                'name' => $schedule->supply?->name ?? 'Insumo #' . $schedule->supply_id,
                'days_until_depleted' => $daysUntilDepleted,
                'current_stock' => (float) $schedule->current_stock,
                'unit' => $schedule->unit,
                'lead_time' => $leadTime,
                'will_arrive_in_time' => $willArriveInTime,
                'depletion_date' => $depletionDate->toDateString(),
                'schedule_id' => $schedule->id,
            ];
        }

        if (empty($criticalItems)) {
            return [];
        }

        // Ordenar por urgencia
        usort($criticalItems, fn ($a, $b) => $a['days_until_depleted'] <=> $b['days_until_depleted']);

        $mostUrgent = $criticalItems[0];
        $count = count($criticalItems);

        $priority = $mostUrgent['days_until_depleted'] <= 3
            ? BusinessInsight::PRIORITY_HIGH
            : BusinessInsight::PRIORITY_MEDIUM;

        // Insight de resumen si hay varios
        if ($count > 1) {
            $names = array_slice(array_column($criticalItems, 'name'), 0, 3);
            $namesText = implode(', ', $names);
            if ($count > 3) {
                $namesText .= " y " . ($count - 3) . " mas";
            }

            return [[
                'type' => BusinessInsight::TYPE_PREDICTION,
                'priority' => $priority,
                'title' => "{$count} insumos se agotan en los proximos 14 dias",
                'description' => "A este ritmo de consumo: {$namesText}. "
                    . "El mas urgente ({$mostUrgent['name']}) se agota en {$mostUrgent['days_until_depleted']} dias. "
                    . ($mostUrgent['will_arrive_in_time']
                        ? "Si pedis hoy, el proveedor llega a tiempo."
                        : "El tiempo de entrega del proveedor ({$mostUrgent['lead_time']} dias) NO alcanza. Pedi ya."),
                'metadata' => [
                    'critical_items' => $criticalItems,
                    'most_urgent' => $mostUrgent,
                    'projection_type' => 'stock_depletion',
                ],
                'action_label' => 'Ver inventario',
                'action_route' => '/stock',
                'dedupe_key' => 'projection_stock_depletion_' . now()->format('Y-W'),
                'expires_at' => now()->addDays(7),
            ]];
        }

        // Insight individual
        return [[
            'type' => BusinessInsight::TYPE_PREDICTION,
            'priority' => $priority,
            'title' => "{$mostUrgent['name']} se agota en {$mostUrgent['days_until_depleted']} dias",
            'description' => "Quedan {$mostUrgent['current_stock']} {$mostUrgent['unit']}. "
                . "A este ritmo de consumo, se agota el {$mostUrgent['depletion_date']}. "
                . ($mostUrgent['will_arrive_in_time']
                    ? "El proveedor tarda {$mostUrgent['lead_time']} dias, asi que todavia llegas si pedis esta semana."
                    : "El proveedor tarda {$mostUrgent['lead_time']} dias. Necesitas pedir YA para evitar el faltante."),
            'metadata' => [
                'item' => $mostUrgent,
                'projection_type' => 'stock_depletion',
            ],
            'action_label' => 'Ver inventario',
            'action_route' => '/stock',
            'dedupe_key' => "projection_stock_{$mostUrgent['schedule_id']}",
            'expires_at' => now()->addDays(5),
        ]];
    }

    /**
     * Proyección de cierre de mes: ingresos vs gastos proyectados.
     */
    private function monthEndProjection(User $user, ?int $organizationId): array
    {
        $dayOfMonth = (int) now()->format('j');
        $daysInMonth = (int) now()->format('t');

        if ($dayOfMonth < 10) {
            return [];
        }

        // Ingresos proyectados
        $currentRevenue = (float) Order::where('user_id', $user->id)
            ->when($organizationId, fn ($q) => $q->where('organization_id', $organizationId))
            ->where('payment_status', 'paid')
            ->whereBetween('order_date', [now()->startOfMonth(), now()])
            ->sum('total');

        // Gastos actuales + recurrentes pendientes
        $currentExpenses = (float) Expense::where('user_id', $user->id)
            ->when($organizationId, fn ($q) => $q->where('organization_id', $organizationId))
            ->whereBetween('expense_date', [now()->startOfMonth(), now()])
            ->sum('amount');

        $pendingRecurring = (float) Expense::where('user_id', $user->id)
            ->when($organizationId, fn ($q) => $q->where('organization_id', $organizationId))
            ->recurring()
            ->where('payment_status', '!=', 'paid')
            ->whereBetween('due_date', [now(), now()->endOfMonth()])
            ->sum('amount');

        if ($currentRevenue <= 0 && $currentExpenses <= 0) {
            return [];
        }

        $projectedRevenue = round(($currentRevenue / $dayOfMonth) * $daysInMonth, 2);
        $projectedExpenses = $currentExpenses + $pendingRecurring;
        $projectedMargin = $projectedRevenue - $projectedExpenses;
        $marginPercent = $projectedRevenue > 0
            ? round(($projectedMargin / $projectedRevenue) * 100, 1)
            : -100;

        // Solo generar si el margen proyectado es preocupante o excelente
        if ($marginPercent >= 10 && $marginPercent <= 40) {
            return []; // Margen normal, no es noticia
        }

        if ($marginPercent < 10) {
            $priority = $marginPercent < 0
                ? BusinessInsight::PRIORITY_HIGH
                : BusinessInsight::PRIORITY_MEDIUM;

            return [[
                'type' => BusinessInsight::TYPE_PREDICTION,
                'priority' => $priority,
                'title' => $marginPercent < 0
                    ? 'Proyeccion: mes cerraria en rojo'
                    : 'Proyeccion: margen ajustado a fin de mes',
                'description' => "Ingresos proyectados: \${$this->fmt($projectedRevenue)}. "
                    . "Gastos comprometidos: \${$this->fmt($projectedExpenses)} "
                    . "(incluye \${$this->fmt($pendingRecurring)} en recurrentes pendientes). "
                    . ($marginPercent < 0
                        ? "El resultado seria negativo en \${$this->fmt(abs($projectedMargin))}. Actua antes de fin de mes."
                        : "El margen quedaria en {$marginPercent}%, bastante ajustado."),
                'metadata' => [
                    'projected_revenue' => $projectedRevenue,
                    'projected_expenses' => $projectedExpenses,
                    'pending_recurring' => $pendingRecurring,
                    'projected_margin' => $projectedMargin,
                    'margin_percent' => $marginPercent,
                    'projection_type' => 'month_end_margin',
                ],
                'action_label' => 'Ver gastos',
                'action_route' => '/expenses',
                'dedupe_key' => 'projection_monthend_' . now()->format('Y-m-W'),
                'expires_at' => now()->addDays(7),
            ]];
        }

        // Margen excelente (>40%)
        return [[
            'type' => BusinessInsight::TYPE_PREDICTION,
            'priority' => BusinessInsight::PRIORITY_LOW,
            'title' => 'Proyeccion: mes cerraria con margen de ' . round($marginPercent) . '%',
            'description' => "Ingresos proyectados: \${$this->fmt($projectedRevenue)} con gastos de \${$this->fmt($projectedExpenses)}. "
                . "El resultado neto seria ~\${$this->fmt($projectedMargin)}. "
                . "Excelente momento para reinvertir o ahorrar.",
            'metadata' => [
                'projected_revenue' => $projectedRevenue,
                'projected_expenses' => $projectedExpenses,
                'projected_margin' => $projectedMargin,
                'margin_percent' => $marginPercent,
                'projection_type' => 'month_end_margin',
            ],
            'action_label' => 'Ver ventas',
            'action_route' => '/orders',
            'dedupe_key' => 'projection_monthend_positive_' . now()->format('Y-m-W'),
            'expires_at' => now()->addDays(7),
        ]];
    }

    /**
     * Proyección de churn: si la tendencia de pérdida de clientes continúa.
     */
    private function clientChurnProjection(User $user, ?int $organizationId): array
    {
        // Clientes activos últimos 3 meses
        $monthlyActive = [];
        for ($i = 0; $i < 3; $i++) {
            $start = now()->subMonths($i)->startOfMonth();
            $end = $i === 0 ? now() : now()->subMonths($i)->endOfMonth();

            $monthlyActive[$i] = (int) Order::where('user_id', $user->id)
                ->when($organizationId, fn ($q) => $q->where('organization_id', $organizationId))
                ->where('payment_status', 'paid')
                ->whereBetween('order_date', [$start, $end])
                ->whereNotNull('client_id')
                ->distinct('client_id')
                ->count('client_id');
        }

        // Necesitamos datos de al menos 2 meses
        if ($monthlyActive[1] === 0 || $monthlyActive[2] === 0) {
            return [];
        }

        // Calcular tendencia: pérdida promedio por mes
        $change1 = $monthlyActive[0] - $monthlyActive[1]; // Este mes vs anterior
        $change2 = $monthlyActive[1] - $monthlyActive[2]; // Anterior vs ante-anterior

        $avgMonthlyChange = ($change1 + $change2) / 2;

        // Solo si la tendencia es negativa y consistente
        if ($avgMonthlyChange >= 0 || $change1 >= 0) {
            return [];
        }

        $lostPerMonth = abs(round($avgMonthlyChange));
        $currentActive = $monthlyActive[0];

        // Proyectar cuántos meses hasta llegar a la mitad
        $monthsToHalf = $currentActive > 0 && $lostPerMonth > 0
            ? (int) ceil(($currentActive / 2) / $lostPerMonth)
            : 0;

        if ($monthsToHalf === 0 || $monthsToHalf > 12) {
            return [];
        }

        return [[
            'type' => BusinessInsight::TYPE_PREDICTION,
            'priority' => $monthsToHalf <= 3
                ? BusinessInsight::PRIORITY_HIGH
                : BusinessInsight::PRIORITY_MEDIUM,
            'title' => "A este ritmo, perderas la mitad de tus clientes en {$monthsToHalf} meses",
            'description' => "Tenes {$currentActive} clientes activos y estas perdiendo ~{$lostPerMonth}/mes. "
                . "Mes anterior: {$monthlyActive[1]} activos, ante-anterior: {$monthlyActive[2]}. "
                . "Si la tendencia continua, en {$monthsToHalf} meses tendras solo " . round($currentActive / 2) . " clientes. "
                . "Es momento de invertir en retencion.",
            'metadata' => [
                'current_active' => $currentActive,
                'lost_per_month' => $lostPerMonth,
                'months_to_half' => $monthsToHalf,
                'monthly_active' => $monthlyActive,
                'projection_type' => 'client_churn',
            ],
            'action_label' => 'Ver clientes',
            'action_route' => '/clients',
            'dedupe_key' => 'projection_churn_' . now()->format('Y-m'),
            'expires_at' => now()->endOfMonth(),
        ]];
    }

    private function fmt(float $n): string
    {
        return number_format($n, 0, ',', '.');
    }
}
