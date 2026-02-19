<?php

namespace App\Services\Insights\Generators;

use App\Models\BusinessInsight;
use App\Models\Expense;
use App\Models\User;
use App\Services\Insights\InsightGeneratorInterface;

class CostWarningGenerator implements InsightGeneratorInterface
{
    public function generate(User $user, ?int $organizationId): array
    {
        $insights = [];

        // 1. Gastos vencidos sin pagar
        $overdueExpenses = Expense::where('user_id', $user->id)
            ->when($organizationId, fn ($q) => $q->where('organization_id', $organizationId))
            ->overdue()
            ->get();

        if ($overdueExpenses->isNotEmpty()) {
            $totalOverdue = $overdueExpenses->sum('amount');
            $count = $overdueExpenses->count();

            $insights[] = [
                'type' => BusinessInsight::TYPE_COST_WARNING,
                'priority' => $totalOverdue > 30000
                    ? BusinessInsight::PRIORITY_HIGH
                    : BusinessInsight::PRIORITY_MEDIUM,
                'title' => "{$count} gastos vencidos",
                'description' => "Tenes \${$this->formatNumber($totalOverdue)} en gastos vencidos sin pagar. Los pagos atrasados pueden generar recargos.",
                'metadata' => [
                    'overdue_count' => $count,
                    'overdue_total' => (float) $totalOverdue,
                    'expense_ids' => $overdueExpenses->pluck('id')->toArray(),
                    'golden_formula' => [
                        'what' => "{$count} gastos vencidos sin pagar por \${$this->formatNumber($totalOverdue)}",
                        'compared_to' => "Todos los gastos deberian pagarse antes de su vencimiento",
                        'impact' => "Puede generar recargos, intereses y daniar la relacion con proveedores",
                        'action' => "Paga los gastos vencidos mas urgentes esta semana",
                    ],
                ],
                'action_label' => 'Ver gastos',
                'action_route' => '/expenses',
                'dedupe_key' => 'cost_overdue_expenses',
                'expires_at' => now()->addDays(3),
            ];
        }

        // 2. Comparar gastos este mes vs promedio últimos 3 meses
        $thisMonthExpenses = Expense::where('user_id', $user->id)
            ->when($organizationId, fn ($q) => $q->where('organization_id', $organizationId))
            ->whereBetween('expense_date', [now()->startOfMonth(), now()])
            ->sum('amount');

        $avgLast3Months = Expense::where('user_id', $user->id)
            ->when($organizationId, fn ($q) => $q->where('organization_id', $organizationId))
            ->whereBetween('expense_date', [now()->subMonths(3)->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->sum('amount') / 3;

        if ($avgLast3Months > 0) {
            $changePercent = (($thisMonthExpenses - $avgLast3Months) / $avgLast3Months) * 100;

            if ($changePercent >= 20) {
                $insights[] = [
                    'type' => BusinessInsight::TYPE_COST_WARNING,
                    'priority' => $changePercent >= 50
                        ? BusinessInsight::PRIORITY_HIGH
                        : BusinessInsight::PRIORITY_MEDIUM,
                    'title' => 'Gastos por encima del promedio',
                    'description' => "Este mes los gastos son " . round($changePercent) . "% mas altos que el promedio de los ultimos 3 meses (\${$this->formatNumber($thisMonthExpenses)} vs \${$this->formatNumber($avgLast3Months)} promedio).",
                    'metadata' => [
                        'this_month' => (float) $thisMonthExpenses,
                        'avg_3_months' => round($avgLast3Months, 2),
                        'change_percent' => round($changePercent, 1),
                        'golden_formula' => [
                            'what' => "Gastaste \${$this->formatNumber($thisMonthExpenses)} este mes",
                            'compared_to' => "El promedio de los ultimos 3 meses era \${$this->formatNumber($avgLast3Months)}",
                            'impact' => "Los gastos subieron " . round($changePercent) . "%, reduce tu margen de ganancia",
                            'action' => "Revisa que gastos nuevos aparecieron y evalua si son necesarios",
                        ],
                    ],
                    'action_label' => 'Ver gastos',
                    'action_route' => '/expenses',
                    'dedupe_key' => 'cost_above_avg_' . now()->format('Y-m'),
                    'expires_at' => now()->endOfMonth(),
                ];
            }
        }

        // 3. Gastos recurrentes próximos a vencer (próximos 7 días)
        $upcomingRecurring = Expense::where('user_id', $user->id)
            ->when($organizationId, fn ($q) => $q->where('organization_id', $organizationId))
            ->recurring()
            ->where('payment_status', 'pending')
            ->whereBetween('due_date', [now(), now()->addDays(7)])
            ->get();

        if ($upcomingRecurring->count() >= 2) {
            $totalUpcoming = $upcomingRecurring->sum('amount');

            $insights[] = [
                'type' => BusinessInsight::TYPE_COST_WARNING,
                'priority' => BusinessInsight::PRIORITY_LOW,
                'title' => "{$upcomingRecurring->count()} gastos recurrentes esta semana",
                'description' => "Se vencen \${$this->formatNumber($totalUpcoming)} en gastos recurrentes los proximos 7 dias. Asegurate de tener liquidez.",
                'metadata' => [
                    'upcoming_count' => $upcomingRecurring->count(),
                    'upcoming_total' => (float) $totalUpcoming,
                    'expense_ids' => $upcomingRecurring->pluck('id')->toArray(),
                    'golden_formula' => [
                        'what' => "{$upcomingRecurring->count()} pagos recurrentes vencen esta semana por \${$this->formatNumber($totalUpcoming)}",
                        'compared_to' => "Estos pagos son compromisos fijos del negocio",
                        'impact' => "Necesitas tener liquidez disponible para cubrirlos sin demora",
                        'action' => "Verifica tu saldo y agenda los pagos para esta semana",
                    ],
                ],
                'action_label' => 'Ver gastos',
                'action_route' => '/expenses',
                'dedupe_key' => 'cost_upcoming_recurring_' . now()->format('Y-W'),
                'expires_at' => now()->addDays(7),
            ];
        }

        return $insights;
    }

    private function formatNumber(float $number): string
    {
        return number_format($number, 0, ',', '.');
    }
}
