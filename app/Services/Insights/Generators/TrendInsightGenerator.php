<?php

namespace App\Services\Insights\Generators;

use App\Models\BusinessInsight;
use App\Models\Order;
use App\Models\Client;
use App\Models\Expense;
use App\Models\User;
use App\Services\Insights\InsightGeneratorInterface;
use Illuminate\Support\Facades\DB;

class TrendInsightGenerator implements InsightGeneratorInterface
{
    public function generate(User $user, ?int $organizationId): array
    {
        $insights = [];

        $insights = array_merge(
            $insights,
            $this->salesTrend($user, $organizationId),
            $this->topClientsTrend($user, $organizationId),
            $this->ticketPromedio($user, $organizationId),
        );

        return $insights;
    }

    /**
     * Tendencia de ventas: compara últimas 4 semanas entre sí.
     */
    private function salesTrend(User $user, ?int $organizationId): array
    {
        $weeks = [];
        for ($i = 0; $i < 4; $i++) {
            $start = now()->subWeeks($i + 1)->startOfWeek();
            $end = now()->subWeeks($i)->startOfWeek();

            $weeks[$i] = (float) Order::where('user_id', $user->id)
                ->when($organizationId, fn ($q) => $q->where('organization_id', $organizationId))
                ->where('payment_status', 'paid')
                ->whereBetween('order_date', [$start, $end])
                ->sum('total');
        }

        // Semana más reciente vs promedio de las 3 anteriores
        $recent = $weeks[0];
        $avgPrevious = ($weeks[1] + $weeks[2] + $weeks[3]) / 3;

        if ($avgPrevious <= 0) {
            return [];
        }

        $changePercent = (($recent - $avgPrevious) / $avgPrevious) * 100;

        // Solo generar si el cambio es significativo (>25%)
        if (abs($changePercent) < 25) {
            return [];
        }

        $isPositive = $changePercent > 0;

        return [[
            'type' => BusinessInsight::TYPE_TREND,
            'priority' => abs($changePercent) > 50
                ? ($isPositive ? BusinessInsight::PRIORITY_LOW : BusinessInsight::PRIORITY_HIGH)
                : BusinessInsight::PRIORITY_MEDIUM,
            'title' => $isPositive
                ? 'Ventas en tendencia alcista'
                : 'Ventas en tendencia bajista',
            'description' => $isPositive
                ? "La ultima semana las ventas subieron " . round(abs($changePercent)) . "% respecto al promedio de las 3 semanas anteriores (\${$this->fmt($recent)} vs \${$this->fmt($avgPrevious)} promedio)."
                : "La ultima semana las ventas cayeron " . round(abs($changePercent)) . "% respecto al promedio de las 3 semanas anteriores (\${$this->fmt($recent)} vs \${$this->fmt($avgPrevious)} promedio).",
            'metadata' => [
                'recent_week' => $recent,
                'avg_previous_3_weeks' => round($avgPrevious, 2),
                'change_percent' => round($changePercent, 1),
                'weeks_data' => $weeks,
            ],
            'action_label' => 'Ver ventas',
            'action_route' => '/orders',
            'dedupe_key' => 'trend_sales_weekly_' . now()->format('Y-W'),
            'expires_at' => now()->addDays(7),
        ]];
    }

    /**
     * Top clientes: identifica clientes que están comprando más o menos.
     */
    private function topClientsTrend(User $user, ?int $organizationId): array
    {
        // Clientes con más compras este mes vs mes pasado
        $thisMonth = DB::table('orders')
            ->select('client_id', 'client_name', DB::raw('SUM(total) as total'))
            ->where('user_id', $user->id)
            ->when($organizationId, fn ($q) => $q->where('organization_id', $organizationId))
            ->where('payment_status', 'paid')
            ->whereBetween('order_date', [now()->startOfMonth(), now()])
            ->whereNotNull('client_id')
            ->groupBy('client_id', 'client_name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        if ($thisMonth->isEmpty()) {
            return [];
        }

        $lastMonth = DB::table('orders')
            ->select('client_id', DB::raw('SUM(total) as total'))
            ->where('user_id', $user->id)
            ->when($organizationId, fn ($q) => $q->where('organization_id', $organizationId))
            ->where('payment_status', 'paid')
            ->whereBetween('order_date', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->whereNotNull('client_id')
            ->groupBy('client_id')
            ->get()
            ->keyBy('client_id');

        $insights = [];

        // Detectar clientes con crecimiento notable (>50%)
        $growingClients = [];
        foreach ($thisMonth as $client) {
            $prevTotal = $lastMonth->get($client->client_id)?->total ?? 0;
            if ($prevTotal > 0) {
                $change = (($client->total - $prevTotal) / $prevTotal) * 100;
                if ($change > 50) {
                    $growingClients[] = [
                        'name' => $client->client_name ?? 'Cliente',
                        'this_month' => (float) $client->total,
                        'last_month' => (float) $prevTotal,
                        'change' => round($change),
                    ];
                }
            }
        }

        if (count($growingClients) > 0) {
            $names = array_slice(array_column($growingClients, 'name'), 0, 3);

            $insights[] = [
                'type' => BusinessInsight::TYPE_TREND,
                'priority' => BusinessInsight::PRIORITY_LOW,
                'title' => count($growingClients) == 1
                    ? "{$names[0]} aumento sus compras"
                    : count($growingClients) . ' clientes aumentaron compras',
                'description' => 'Estos clientes estan comprando significativamente mas que el mes anterior: ' . implode(', ', $names) . '. Buen momento para ofrecer productos complementarios.',
                'metadata' => [
                    'growing_clients' => $growingClients,
                ],
                'action_label' => 'Ver clientes',
                'action_route' => '/clients',
                'dedupe_key' => 'trend_growing_clients_' . now()->format('Y-m'),
                'expires_at' => now()->endOfMonth(),
            ];
        }

        return $insights;
    }

    /**
     * Ticket promedio: tendencia del valor promedio por pedido.
     */
    private function ticketPromedio(User $user, ?int $organizationId): array
    {
        $thisMonthAvg = Order::where('user_id', $user->id)
            ->when($organizationId, fn ($q) => $q->where('organization_id', $organizationId))
            ->where('payment_status', 'paid')
            ->whereBetween('order_date', [now()->startOfMonth(), now()])
            ->avg('total');

        $lastMonthAvg = Order::where('user_id', $user->id)
            ->when($organizationId, fn ($q) => $q->where('organization_id', $organizationId))
            ->where('payment_status', 'paid')
            ->whereBetween('order_date', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->avg('total');

        if (!$thisMonthAvg || !$lastMonthAvg || $lastMonthAvg <= 0) {
            return [];
        }

        $changePercent = (($thisMonthAvg - $lastMonthAvg) / $lastMonthAvg) * 100;

        if (abs($changePercent) < 15) {
            return [];
        }

        $isUp = $changePercent > 0;

        return [[
            'type' => BusinessInsight::TYPE_TREND,
            'priority' => BusinessInsight::PRIORITY_LOW,
            'title' => $isUp
                ? 'Ticket promedio subiendo'
                : 'Ticket promedio bajando',
            'description' => $isUp
                ? "El valor promedio por venta subio " . round(abs($changePercent)) . "% (\${$this->fmt($thisMonthAvg)} vs \${$this->fmt($lastMonthAvg)} mes anterior). Los clientes estan gastando mas por pedido."
                : "El valor promedio por venta bajo " . round(abs($changePercent)) . "% (\${$this->fmt($thisMonthAvg)} vs \${$this->fmt($lastMonthAvg)} mes anterior). Considera paquetes o up-sells.",
            'metadata' => [
                'this_month_avg' => round($thisMonthAvg, 2),
                'last_month_avg' => round($lastMonthAvg, 2),
                'change_percent' => round($changePercent, 1),
            ],
            'action_label' => 'Ver ventas',
            'action_route' => '/orders',
            'dedupe_key' => 'trend_ticket_avg_' . now()->format('Y-m'),
            'expires_at' => now()->endOfMonth(),
        ]];
    }

    private function fmt(float $n): string
    {
        return number_format($n, 0, ',', '.');
    }
}
