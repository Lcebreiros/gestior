<?php

namespace App\Services\Insights\Generators;

use App\Models\BusinessInsight;
use App\Models\Client;
use App\Models\Order;
use App\Models\User;
use App\Services\Insights\InsightGeneratorInterface;
use Illuminate\Support\Facades\DB;

class ClientRetentionGenerator implements InsightGeneratorInterface
{
    public function generate(User $user, ?int $organizationId): array
    {
        $insights = [];

        // Obtener clientes con métricas RFM básicas
        $clients = Client::where('user_id', $user->id)
            ->when($organizationId, fn ($q) => $q->where('organization_id', $organizationId))
            ->withCount(['orders' => fn ($q) => $q->where('payment_status', 'paid')])
            ->withSum(['orders' => fn ($q) => $q->where('payment_status', 'paid')], 'total')
            ->with(['orders' => fn ($q) => $q->where('payment_status', 'paid')->latest('order_date')->limit(1)])
            ->having('orders_count', '>', 0)
            ->get();

        if ($clients->isEmpty()) {
            return [];
        }

        // Identificar "clientes dormidos": compraron >2 veces pero última compra >30 días
        $dormantClients = $clients->filter(function ($client) {
            $lastOrder = $client->orders->first();
            if (!$lastOrder || !$lastOrder->order_date) return false;

            $daysSinceLastOrder = $lastOrder->order_date->diffInDays(now());
            return $client->orders_count >= 2 && $daysSinceLastOrder > 30;
        });

        foreach ($dormantClients->take(5) as $client) {
            $lastOrder = $client->orders->first();
            $daysSince = $lastOrder->order_date->diffInDays(now());
            $totalSpent = (float) ($client->orders_sum_total ?? 0);

            $priority = match (true) {
                $daysSince > 90 && $totalSpent > 50000 => BusinessInsight::PRIORITY_HIGH,
                $daysSince > 60 => BusinessInsight::PRIORITY_MEDIUM,
                default => BusinessInsight::PRIORITY_LOW,
            };

            $insights[] = [
                'type' => BusinessInsight::TYPE_CLIENT_RETENTION,
                'priority' => $priority,
                'title' => "Reactivar a {$client->name}",
                'description' => "Ultima compra hace {$daysSince} dias. Total historico: \${$this->formatNumber($totalSpent)} en {$client->orders_count} pedidos. Buen momento para contactarlo.",
                'metadata' => [
                    'client_id' => $client->id,
                    'client_name' => $client->name,
                    'days_since_last_order' => $daysSince,
                    'total_orders' => $client->orders_count,
                    'total_spent' => $totalSpent,
                    'last_order_date' => $lastOrder->order_date->toDateString(),
                ],
                'action_label' => 'Ver cliente',
                'action_route' => '/clients/' . $client->id,
                'dedupe_key' => "retention_dormant_{$client->id}",
                'expires_at' => now()->addDays(14),
            ];
        }

        // Insight resumen si hay muchos dormidos
        if ($dormantClients->count() >= 3) {
            $totalRevenue = $dormantClients->sum(fn ($c) => (float) ($c->orders_sum_total ?? 0));

            $insights[] = [
                'type' => BusinessInsight::TYPE_CLIENT_RETENTION,
                'priority' => BusinessInsight::PRIORITY_MEDIUM,
                'title' => "{$dormantClients->count()} clientes inactivos",
                'description' => "Clientes que compraban regularmente pero dejaron de hacerlo. Representan \${$this->formatNumber($totalRevenue)} en compras historicas.",
                'metadata' => [
                    'dormant_count' => $dormantClients->count(),
                    'total_historical_revenue' => $totalRevenue,
                ],
                'action_label' => 'Ver clientes',
                'action_route' => '/clients',
                'dedupe_key' => 'retention_summary',
                'expires_at' => now()->addDays(7),
            ];
        }

        // Mejores clientes activos (última compra <30 días, >3 pedidos)
        $topActive = $clients->filter(function ($client) {
            $lastOrder = $client->orders->first();
            if (!$lastOrder || !$lastOrder->order_date) return false;

            $daysSinceLastOrder = $lastOrder->order_date->diffInDays(now());
            return $client->orders_count >= 3 && $daysSinceLastOrder <= 30;
        })->sortByDesc('orders_sum_total')->take(3);

        if ($topActive->isNotEmpty()) {
            $names = $topActive->pluck('name')->implode(', ');
            $insights[] = [
                'type' => BusinessInsight::TYPE_CLIENT_RETENTION,
                'priority' => BusinessInsight::PRIORITY_LOW,
                'title' => 'Tus mejores clientes activos',
                'description' => "Los clientes mas valiosos este mes: {$names}. Considera premiarlos para fidelizarlos.",
                'metadata' => [
                    'top_clients' => $topActive->map(fn ($c) => [
                        'id' => $c->id,
                        'name' => $c->name,
                        'total' => (float) ($c->orders_sum_total ?? 0),
                        'orders' => $c->orders_count,
                    ])->values()->toArray(),
                ],
                'action_label' => 'Ver clientes',
                'action_route' => '/clients',
                'dedupe_key' => 'retention_top_active_' . now()->format('Y-m'),
                'expires_at' => now()->endOfMonth(),
            ];
        }

        return $insights;
    }

    private function formatNumber(float $number): string
    {
        return number_format($number, 0, ',', '.');
    }
}
