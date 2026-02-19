<?php

namespace App\Services\Insights\Generators;

use App\Models\BusinessInsight;
use App\Models\Order;
use App\Models\User;
use App\Services\Insights\InsightGeneratorInterface;
use Illuminate\Support\Facades\DB;

class RevenueOpportunityGenerator implements InsightGeneratorInterface
{
    public function generate(User $user, ?int $organizationId): array
    {
        $insights = [];

        // 1. Pedidos con pago pendiente / vencido
        $overdueOrders = Order::where('user_id', $user->id)
            ->when($organizationId, fn ($q) => $q->where('organization_id', $organizationId))
            ->where('payment_status', '!=', 'paid')
            ->where('payment_deadline', '<', now())
            ->get();

        if ($overdueOrders->isNotEmpty()) {
            $totalOverdue = $overdueOrders->sum('total');
            $count = $overdueOrders->count();

            $insights[] = [
                'type' => BusinessInsight::TYPE_REVENUE_OPPORTUNITY,
                'priority' => $totalOverdue > 50000
                    ? BusinessInsight::PRIORITY_HIGH
                    : BusinessInsight::PRIORITY_MEDIUM,
                'title' => "{$count} pagos vencidos por cobrar",
                'description' => "Tenes \${$this->formatNumber($totalOverdue)} en pagos vencidos. Contacta a los clientes para acelerar la cobranza.",
                'metadata' => [
                    'overdue_count' => $count,
                    'overdue_total' => (float) $totalOverdue,
                    'order_ids' => $overdueOrders->pluck('id')->toArray(),
                    'golden_formula' => [
                        'what' => "{$count} pedidos tienen pagos vencidos por \${$this->formatNumber($totalOverdue)}",
                        'compared_to' => "Todos los pagos deberian estar al dia segun sus plazos",
                        'impact' => "Reduce tu flujo de caja y genera riesgo de incobrabilidad",
                        'action' => "Contacta a los clientes con pagos vencidos esta semana",
                    ],
                ],
                'action_label' => 'Ver pedidos',
                'action_route' => '/orders',
                'dedupe_key' => 'revenue_overdue_payments',
                'expires_at' => now()->addDays(3),
            ];
        }

        // 2. Comparar ventas este mes vs mes anterior
        $thisMonthSales = Order::where('user_id', $user->id)
            ->when($organizationId, fn ($q) => $q->where('organization_id', $organizationId))
            ->where('payment_status', 'paid')
            ->whereBetween('order_date', [now()->startOfMonth(), now()])
            ->sum('total');

        $lastMonthSales = Order::where('user_id', $user->id)
            ->when($organizationId, fn ($q) => $q->where('organization_id', $organizationId))
            ->where('payment_status', 'paid')
            ->whereBetween('order_date', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->sum('total');

        if ($lastMonthSales > 0) {
            $changePercent = (($thisMonthSales - $lastMonthSales) / $lastMonthSales) * 100;

            if ($changePercent >= 20) {
                $insights[] = [
                    'type' => BusinessInsight::TYPE_REVENUE_OPPORTUNITY,
                    'priority' => BusinessInsight::PRIORITY_LOW,
                    'title' => 'Ventas en alza este mes',
                    'description' => "Las ventas subieron " . round($changePercent) . "% respecto al mes anterior (\${$this->formatNumber($thisMonthSales)} vs \${$this->formatNumber($lastMonthSales)}). Buen momento para impulsar mas.",
                    'metadata' => [
                        'this_month' => (float) $thisMonthSales,
                        'last_month' => (float) $lastMonthSales,
                        'change_percent' => round($changePercent, 1),
                        'golden_formula' => [
                            'what' => "Facturaste \${$this->formatNumber($thisMonthSales)} este mes",
                            'compared_to' => "El mes anterior fueron \${$this->formatNumber($lastMonthSales)}",
                            'impact' => "Crecimiento de +" . round($changePercent) . "%, el negocio gana traccion",
                            'action' => "Aprovecha el impulso para captar mas clientes o lanzar upsells",
                        ],
                    ],
                    'action_label' => 'Ver ventas',
                    'action_route' => '/orders',
                    'dedupe_key' => 'revenue_sales_up_' . now()->format('Y-m'),
                    'expires_at' => now()->endOfMonth(),
                ];
            } elseif ($changePercent <= -20) {
                $insights[] = [
                    'type' => BusinessInsight::TYPE_REVENUE_OPPORTUNITY,
                    'priority' => BusinessInsight::PRIORITY_HIGH,
                    'title' => 'Ventas cayeron este mes',
                    'description' => "Las ventas bajaron " . abs(round($changePercent)) . "% respecto al mes anterior (\${$this->formatNumber($thisMonthSales)} vs \${$this->formatNumber($lastMonthSales)}). Considera promociones o acciones comerciales.",
                    'metadata' => [
                        'this_month' => (float) $thisMonthSales,
                        'last_month' => (float) $lastMonthSales,
                        'change_percent' => round($changePercent, 1),
                        'golden_formula' => [
                            'what' => "Facturaste \${$this->formatNumber($thisMonthSales)} este mes",
                            'compared_to' => "El mes anterior fueron \${$this->formatNumber($lastMonthSales)}",
                            'impact' => "Caida de " . abs(round($changePercent)) . "%, menos ingresos para cubrir costos",
                            'action' => "Lanza promociones, contacta clientes inactivos, revisa precios",
                        ],
                    ],
                    'action_label' => 'Ver ventas',
                    'action_route' => '/orders',
                    'dedupe_key' => 'revenue_sales_down_' . now()->format('Y-m'),
                    'expires_at' => now()->endOfMonth(),
                ];
            }
        }

        // 3. Pedidos pendientes de entrega
        $pendingDeliveries = Order::where('user_id', $user->id)
            ->when($organizationId, fn ($q) => $q->where('organization_id', $organizationId))
            ->where('status', 'pending')
            ->where('expected_delivery_date', '<', now())
            ->count();

        if ($pendingDeliveries > 0) {
            $insights[] = [
                'type' => BusinessInsight::TYPE_REVENUE_OPPORTUNITY,
                'priority' => BusinessInsight::PRIORITY_MEDIUM,
                'title' => "{$pendingDeliveries} entregas atrasadas",
                'description' => "Hay pedidos con fecha de entrega vencida. Las entregas a tiempo mejoran la retencion de clientes.",
                'metadata' => [
                    'pending_deliveries' => $pendingDeliveries,
                ],
                'action_label' => 'Ver pedidos',
                'action_route' => '/orders',
                'dedupe_key' => 'revenue_late_deliveries',
                'expires_at' => now()->addDays(3),
            ];
        }

        // 4. Clientes nuevos este mes
        $newClientsThisMonth = Order::where('user_id', $user->id)
            ->when($organizationId, fn ($q) => $q->where('organization_id', $organizationId))
            ->where('payment_status', 'paid')
            ->whereBetween('order_date', [now()->startOfMonth(), now()])
            ->whereNotNull('client_id')
            ->select('client_id')
            ->distinct()
            ->get()
            ->pluck('client_id');

        if ($newClientsThisMonth->isNotEmpty()) {
            // Clientes que compraron este mes pero nunca antes
            $previousClients = Order::where('user_id', $user->id)
                ->when($organizationId, fn ($q) => $q->where('organization_id', $organizationId))
                ->where('payment_status', 'paid')
                ->where('order_date', '<', now()->startOfMonth())
                ->whereNotNull('client_id')
                ->select('client_id')
                ->distinct()
                ->pluck('client_id');

            $newOnes = $newClientsThisMonth->diff($previousClients);

            if ($newOnes->count() >= 2) {
                $insights[] = [
                    'type' => BusinessInsight::TYPE_REVENUE_OPPORTUNITY,
                    'priority' => BusinessInsight::PRIORITY_LOW,
                    'title' => "{$newOnes->count()} clientes nuevos este mes",
                    'description' => "Conseguiste {$newOnes->count()} clientes nuevos. Es buen momento para fidelizarlos con una segunda compra.",
                    'metadata' => [
                        'new_client_count' => $newOnes->count(),
                        'new_client_ids' => $newOnes->values()->toArray(),
                    ],
                    'action_label' => 'Ver clientes',
                    'action_route' => '/clients',
                    'dedupe_key' => 'revenue_new_clients_' . now()->format('Y-m'),
                    'expires_at' => now()->endOfMonth(),
                ];
            }
        }

        return $insights;
    }

    private function formatNumber(float $number): string
    {
        return number_format($number, 0, ',', '.');
    }
}
