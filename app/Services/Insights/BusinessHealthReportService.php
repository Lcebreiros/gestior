<?php

namespace App\Services\Insights;

use App\Models\Client;
use App\Models\Expense;
use App\Models\Order;
use App\Models\SupplySchedule;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class BusinessHealthReportService
{
    /**
     * Genera un reporte de salud completo del negocio.
     *
     * Cada categoría sigue la fórmula dorada:
     * Qué pasó + Comparado con qué + Impacto + Acción sugerida
     */
    public function generate(User $user, ?int $organizationId = null): array
    {
        $revenue = $this->revenueHealth($user, $organizationId);
        $inventory = $this->inventoryHealth($user, $organizationId);
        $clients = $this->clientHealth($user, $organizationId);
        $costs = $this->costHealth($user, $organizationId);

        // Score ponderado: Revenue 35%, Clients 25%, Inventory 20%, Costs 20%
        $overallScore = round(
            ($revenue['score'] * 0.35) +
            ($clients['score'] * 0.25) +
            ($inventory['score'] * 0.20) +
            ($costs['score'] * 0.20)
        );

        $status = $this->overallStatus($overallScore);

        return [
            'overall_score' => $overallScore,
            'status' => $status['label'],
            'status_color' => $status['color'],
            'status_emoji' => $status['emoji'],
            'summary' => $this->buildSummary($overallScore, $revenue, $clients, $inventory, $costs),
            'categories' => [
                'revenue' => $revenue,
                'inventory' => $inventory,
                'clients' => $clients,
                'costs' => $costs,
            ],
            'generated_at' => now()->toIso8601String(),
        ];
    }

    /**
     * Salud de ingresos: tendencia de ventas, cobranza, crecimiento.
     */
    private function revenueHealth(User $user, ?int $organizationId): array
    {
        $baseQuery = Order::where('user_id', $user->id)
            ->when($organizationId, fn ($q) => $q->where('organization_id', $organizationId));

        // Ventas este mes vs mes anterior
        $thisMonth = (float) (clone $baseQuery)->where('payment_status', 'paid')
            ->whereBetween('order_date', [now()->startOfMonth(), now()])
            ->sum('total');

        $lastMonth = (float) (clone $baseQuery)->where('payment_status', 'paid')
            ->whereBetween('order_date', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->sum('total');

        // Pedidos este mes vs mes anterior
        $ordersThisMonth = (clone $baseQuery)->where('payment_status', 'paid')
            ->whereBetween('order_date', [now()->startOfMonth(), now()])
            ->count();

        $ordersLastMonth = (clone $baseQuery)->where('payment_status', 'paid')
            ->whereBetween('order_date', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->count();

        // Tasa de cobranza (pagados vs total)
        $totalOrders = (clone $baseQuery)
            ->whereBetween('order_date', [now()->startOfMonth(), now()])
            ->count();

        $paidOrders = (clone $baseQuery)->where('payment_status', 'paid')
            ->whereBetween('order_date', [now()->startOfMonth(), now()])
            ->count();

        $collectionRate = $totalOrders > 0 ? round(($paidOrders / $totalOrders) * 100) : 100;

        // Monto vencido pendiente
        $overdueAmount = (float) (clone $baseQuery)
            ->where('payment_status', '!=', 'paid')
            ->where('payment_deadline', '<', now())
            ->sum('total');

        // Calcular growth %
        $growthPercent = $lastMonth > 0
            ? round((($thisMonth - $lastMonth) / $lastMonth) * 100, 1)
            : ($thisMonth > 0 ? 100 : 0);

        // Score: basado en crecimiento (40%), cobranza (35%), sin vencidos (25%)
        $growthScore = $this->clampScore($growthPercent >= 0 ? min(100, 50 + $growthPercent) : max(0, 50 + $growthPercent));
        $collectionScore = $collectionRate;
        $overdueScore = $thisMonth > 0
            ? $this->clampScore(100 - min(100, ($overdueAmount / max($thisMonth, 1)) * 100))
            : ($overdueAmount > 0 ? 20 : 100);

        $score = round(($growthScore * 0.40) + ($collectionScore * 0.35) + ($overdueScore * 0.25));

        // Construir diagnóstico con fórmula dorada
        $indicators = [];

        // Indicador 1: Crecimiento de ventas
        $indicators[] = [
            'label' => 'Crecimiento de ventas',
            'value' => $growthPercent,
            'value_formatted' => ($growthPercent >= 0 ? '+' : '') . $growthPercent . '%',
            'what' => $thisMonth > 0
                ? "Facturaste \${$this->fmt($thisMonth)} este mes"
                : "No hay ventas registradas este mes",
            'compared_to' => $lastMonth > 0
                ? "El mes anterior fueron \${$this->fmt($lastMonth)}"
                : "No hubo ventas el mes anterior",
            'impact' => $this->revenueGrowthImpact($growthPercent),
            'action' => $this->revenueGrowthAction($growthPercent),
            'trend' => $growthPercent > 0 ? 'up' : ($growthPercent < 0 ? 'down' : 'stable'),
        ];

        // Indicador 2: Tasa de cobranza
        $indicators[] = [
            'label' => 'Tasa de cobranza',
            'value' => $collectionRate,
            'value_formatted' => $collectionRate . '%',
            'what' => "Cobraste {$paidOrders} de {$totalOrders} pedidos este mes",
            'compared_to' => "El ideal es mantener +90% de cobranza",
            'impact' => $collectionRate >= 90
                ? "Excelente cobranza, el flujo de caja es saludable"
                : ($collectionRate >= 70
                    ? "Hay margen de mejora en la cobranza"
                    : "La baja cobranza afecta tu flujo de caja"),
            'action' => $collectionRate < 90
                ? "Revisa los pedidos pendientes de pago y contacta a los clientes"
                : "Mantene el seguimiento actual de cobranza",
            'trend' => $collectionRate >= 90 ? 'up' : ($collectionRate >= 70 ? 'stable' : 'down'),
        ];

        // Indicador 3: Monto vencido
        if ($overdueAmount > 0) {
            $indicators[] = [
                'label' => 'Pagos vencidos',
                'value' => $overdueAmount,
                'value_formatted' => '$' . $this->fmt($overdueAmount),
                'what' => "Tenes \${$this->fmt($overdueAmount)} en pagos vencidos",
                'compared_to' => "Este monto deberia estar en cero",
                'impact' => "Reduce tu capital disponible y genera riesgo de incobrabilidad",
                'action' => "Prioriza contactar a los clientes con pagos vencidos",
                'trend' => 'down',
            ];
        }

        return [
            'score' => $this->clampScore($score),
            'label' => 'Ingresos',
            'icon' => 'trending_up',
            'color' => $this->scoreColor($score),
            'indicators' => $indicators,
            'metrics' => [
                'this_month_revenue' => $thisMonth,
                'last_month_revenue' => $lastMonth,
                'growth_percent' => $growthPercent,
                'orders_this_month' => $ordersThisMonth,
                'orders_last_month' => $ordersLastMonth,
                'collection_rate' => $collectionRate,
                'overdue_amount' => $overdueAmount,
            ],
        ];
    }

    /**
     * Salud del inventario: stock bajo, sin stock, eficiencia de reorden.
     */
    private function inventoryHealth(User $user, ?int $organizationId): array
    {
        $baseQuery = SupplySchedule::where('user_id', $user->id)
            ->when($organizationId, fn ($q) => $q->where('organization_id', $organizationId));

        $totalItems = (clone $baseQuery)->count();

        if ($totalItems === 0) {
            return [
                'score' => 100,
                'label' => 'Inventario',
                'icon' => 'inventory',
                'color' => '#22C55E',
                'indicators' => [[
                    'label' => 'Sin inventario registrado',
                    'value' => 0,
                    'value_formatted' => 'N/A',
                    'what' => "No tenes insumos registrados en el sistema",
                    'compared_to' => "Registrar insumos permite monitorear tu stock",
                    'impact' => "Sin seguimiento de inventario no podemos alertarte sobre faltantes",
                    'action' => "Agrega tus insumos principales para comenzar el seguimiento",
                    'trend' => 'stable',
                ]],
                'metrics' => ['total_items' => 0],
            ];
        }

        $outOfStock = (clone $baseQuery)->outOfStock()->count();
        $lowStock = (clone $baseQuery)->lowStock()->count();
        $healthyStock = $totalItems - $outOfStock - $lowStock;

        // Items por vencer pronto (proximos 7 dias)
        $expiringSoon = (clone $baseQuery)
            ->whereNotNull('expiration_date')
            ->whereBetween('expiration_date', [now(), now()->addDays(7)])
            ->count();

        // Score: basado en % saludable (50%), sin out-of-stock (30%), sin vencimientos (20%)
        $healthyPercent = round(($healthyStock / $totalItems) * 100);
        $outOfStockPenalty = min(100, ($outOfStock / $totalItems) * 300); // Peso triple
        $expiringPenalty = min(30, ($expiringSoon / $totalItems) * 100);

        $score = round(
            ($healthyPercent * 0.50) +
            ((100 - $outOfStockPenalty) * 0.30) +
            ((100 - $expiringPenalty) * 0.20)
        );

        $indicators = [];

        // Indicador 1: Estado general del stock
        $indicators[] = [
            'label' => 'Estado del stock',
            'value' => $healthyPercent,
            'value_formatted' => $healthyPercent . '%',
            'what' => "{$healthyStock} de {$totalItems} insumos tienen stock saludable",
            'compared_to' => "El ideal es mantener +85% de insumos en buen nivel",
            'impact' => $healthyPercent >= 85
                ? "Tu inventario esta bien abastecido"
                : ($healthyPercent >= 60
                    ? "Algunos insumos necesitan atencion"
                    : "El inventario esta en estado critico"),
            'action' => $healthyPercent < 85
                ? "Revisa los {$lowStock} insumos con stock bajo y planifica reposicion"
                : "Mantene el ritmo de reposicion actual",
            'trend' => $healthyPercent >= 85 ? 'up' : ($healthyPercent >= 60 ? 'stable' : 'down'),
        ];

        // Indicador 2: Items sin stock
        if ($outOfStock > 0) {
            $indicators[] = [
                'label' => 'Sin stock',
                'value' => $outOfStock,
                'value_formatted' => $outOfStock . ' items',
                'what' => "{$outOfStock} insumos estan sin stock",
                'compared_to' => "Este numero deberia ser cero",
                'impact' => "Puede frenar la produccion o entregas si no se repone rapidamente",
                'action' => "Contacta a proveedores para reponer los items criticos",
                'trend' => 'down',
            ];
        }

        // Indicador 3: Por vencer
        if ($expiringSoon > 0) {
            $indicators[] = [
                'label' => 'Por vencer',
                'value' => $expiringSoon,
                'value_formatted' => $expiringSoon . ' items',
                'what' => "{$expiringSoon} insumos vencen en los proximos 7 dias",
                'compared_to' => "Verificar fechas de vencimiento es critico para la calidad",
                'impact' => "Usar insumos vencidos afecta la calidad del producto y la confianza del cliente",
                'action' => "Revisa y usa primero los insumos proximos a vencer",
                'trend' => 'down',
            ];
        }

        return [
            'score' => $this->clampScore($score),
            'label' => 'Inventario',
            'icon' => 'inventory',
            'color' => $this->scoreColor($score),
            'indicators' => $indicators,
            'metrics' => [
                'total_items' => $totalItems,
                'healthy_stock' => $healthyStock,
                'low_stock' => $lowStock,
                'out_of_stock' => $outOfStock,
                'expiring_soon' => $expiringSoon,
                'healthy_percent' => $healthyPercent,
            ],
        ];
    }

    /**
     * Salud de clientes: retención, nuevos clientes, diversificación.
     */
    private function clientHealth(User $user, ?int $organizationId): array
    {
        $baseQuery = Order::where('user_id', $user->id)
            ->when($organizationId, fn ($q) => $q->where('organization_id', $organizationId))
            ->where('payment_status', 'paid');

        // Clientes activos este mes
        $activeClientsThisMonth = (clone $baseQuery)
            ->whereBetween('order_date', [now()->startOfMonth(), now()])
            ->whereNotNull('client_id')
            ->distinct('client_id')
            ->count('client_id');

        // Clientes activos mes anterior
        $activeClientsLastMonth = (clone $baseQuery)
            ->whereBetween('order_date', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->whereNotNull('client_id')
            ->distinct('client_id')
            ->count('client_id');

        // Clientes nuevos (compraron este mes, nunca antes)
        $clientsThisMonth = (clone $baseQuery)
            ->whereBetween('order_date', [now()->startOfMonth(), now()])
            ->whereNotNull('client_id')
            ->distinct()
            ->pluck('client_id');

        $previousClients = (clone $baseQuery)
            ->where('order_date', '<', now()->startOfMonth())
            ->whereNotNull('client_id')
            ->distinct()
            ->pluck('client_id');

        $newClients = $clientsThisMonth->diff($previousClients)->count();
        $returningClients = $clientsThisMonth->intersect($previousClients)->count();

        // Concentración: % de ventas del top cliente
        $topClientRevenue = DB::table('orders')
            ->where('user_id', $user->id)
            ->when($organizationId, fn ($q) => $q->where('organization_id', $organizationId))
            ->where('payment_status', 'paid')
            ->whereBetween('order_date', [now()->startOfMonth(), now()])
            ->whereNotNull('client_id')
            ->groupBy('client_id')
            ->select(DB::raw('SUM(total) as total'))
            ->orderByDesc('total')
            ->first();

        $totalRevenue = (float) (clone $baseQuery)
            ->whereBetween('order_date', [now()->startOfMonth(), now()])
            ->sum('total');

        $topClientConcentration = ($topClientRevenue && $totalRevenue > 0)
            ? round(($topClientRevenue->total / $totalRevenue) * 100)
            : 0;

        // Clientes dormidos (>30 dias sin comprar, con historial de +2 compras)
        $dormantClients = DB::table('orders')
            ->where('user_id', $user->id)
            ->when($organizationId, fn ($q) => $q->where('organization_id', $organizationId))
            ->where('payment_status', 'paid')
            ->whereNotNull('client_id')
            ->groupBy('client_id')
            ->havingRaw('COUNT(*) >= 2')
            ->havingRaw('MAX(order_date) < ?', [now()->subDays(30)->toDateString()])
            ->count();

        // Score
        $retentionScore = $activeClientsLastMonth > 0
            ? $this->clampScore(round(($returningClients / $activeClientsLastMonth) * 100))
            : ($activeClientsThisMonth > 0 ? 60 : 50);

        $diversityScore = $topClientConcentration > 0
            ? $this->clampScore(100 - $topClientConcentration) // Menor concentración = mejor
            : 70; // Sin datos = neutral

        $growthScore = $activeClientsLastMonth > 0
            ? $this->clampScore(50 + (($activeClientsThisMonth - $activeClientsLastMonth) / max($activeClientsLastMonth, 1)) * 50)
            : ($activeClientsThisMonth > 0 ? 70 : 40);

        $score = round(($retentionScore * 0.40) + ($growthScore * 0.30) + ($diversityScore * 0.30));

        $indicators = [];

        // Indicador 1: Clientes activos
        $clientGrowth = $activeClientsLastMonth > 0
            ? round((($activeClientsThisMonth - $activeClientsLastMonth) / $activeClientsLastMonth) * 100)
            : 0;

        $indicators[] = [
            'label' => 'Clientes activos',
            'value' => $activeClientsThisMonth,
            'value_formatted' => (string) $activeClientsThisMonth,
            'what' => "{$activeClientsThisMonth} clientes compraron este mes",
            'compared_to' => "El mes anterior fueron {$activeClientsLastMonth}",
            'impact' => $clientGrowth > 0
                ? "La base de clientes esta creciendo (+{$clientGrowth}%)"
                : ($clientGrowth < 0
                    ? "Estas perdiendo clientes activos ({$clientGrowth}%)"
                    : "La base de clientes se mantiene estable"),
            'action' => $clientGrowth < 0
                ? "Contacta clientes inactivos con ofertas personalizadas"
                : "Buen ritmo, enfocate en fidelizar los clientes nuevos",
            'trend' => $clientGrowth > 0 ? 'up' : ($clientGrowth < 0 ? 'down' : 'stable'),
        ];

        // Indicador 2: Retención
        if ($activeClientsLastMonth > 0) {
            $retentionRate = round(($returningClients / $activeClientsLastMonth) * 100);
            $indicators[] = [
                'label' => 'Retencion',
                'value' => $retentionRate,
                'value_formatted' => $retentionRate . '%',
                'what' => "{$returningClients} clientes del mes pasado volvieron a comprar",
                'compared_to' => "Una retencion sana esta por encima del 60%",
                'impact' => $retentionRate >= 60
                    ? "Buena fidelizacion, los clientes confian en vos"
                    : "Baja retencion indica que los clientes no vuelven",
                'action' => $retentionRate < 60
                    ? "Implementa un programa de fidelizacion o contacto post-venta"
                    : "Segui cultivando la relacion con tus clientes frecuentes",
                'trend' => $retentionRate >= 60 ? 'up' : 'down',
            ];
        }

        // Indicador 3: Concentración
        if ($topClientConcentration > 50) {
            $indicators[] = [
                'label' => 'Concentracion',
                'value' => $topClientConcentration,
                'value_formatted' => $topClientConcentration . '%',
                'what' => "Un solo cliente representa el {$topClientConcentration}% de tus ventas",
                'compared_to' => "Lo ideal es que ningun cliente supere el 30% del total",
                'impact' => "Alta dependencia de un solo cliente genera riesgo si deja de comprar",
                'action' => "Diversifica tu cartera captando nuevos clientes activamente",
                'trend' => 'down',
            ];
        }

        // Indicador 4: Clientes dormidos
        if ($dormantClients > 0) {
            $indicators[] = [
                'label' => 'Clientes dormidos',
                'value' => $dormantClients,
                'value_formatted' => (string) $dormantClients,
                'what' => "{$dormantClients} clientes recurrentes no compran hace +30 dias",
                'compared_to' => "Estos clientes compraban regularmente antes",
                'impact' => "Perder clientes recurrentes es mas costoso que adquirir nuevos",
                'action' => "Contactalos con un mensaje personalizado o una oferta especial",
                'trend' => 'down',
            ];
        }

        return [
            'score' => $this->clampScore($score),
            'label' => 'Clientes',
            'icon' => 'people',
            'color' => $this->scoreColor($score),
            'indicators' => $indicators,
            'metrics' => [
                'active_this_month' => $activeClientsThisMonth,
                'active_last_month' => $activeClientsLastMonth,
                'new_clients' => $newClients,
                'returning_clients' => $returningClients,
                'dormant_clients' => $dormantClients,
                'top_client_concentration' => $topClientConcentration,
            ],
        ];
    }

    /**
     * Salud de costos: control de gastos, vencimientos, tendencia.
     */
    private function costHealth(User $user, ?int $organizationId): array
    {
        $baseQuery = Expense::where('user_id', $user->id)
            ->when($organizationId, fn ($q) => $q->where('organization_id', $organizationId));

        // Gastos este mes vs mes anterior
        $thisMonthExpenses = (float) (clone $baseQuery)
            ->whereBetween('expense_date', [now()->startOfMonth(), now()])
            ->sum('amount');

        $lastMonthExpenses = (float) (clone $baseQuery)
            ->whereBetween('expense_date', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->sum('amount');

        // Gastos vencidos sin pagar
        $overdueExpenses = (clone $baseQuery)->overdue()->count();
        $overdueAmount = (float) (clone $baseQuery)->overdue()->sum('amount');

        // Gastos recurrentes proximos (7 dias)
        $upcomingRecurring = (clone $baseQuery)
            ->recurring()
            ->where('payment_status', '!=', 'paid')
            ->whereBetween('next_occurrence_date', [now(), now()->addDays(7)])
            ->count();

        $upcomingAmount = (float) (clone $baseQuery)
            ->recurring()
            ->where('payment_status', '!=', 'paid')
            ->whereBetween('next_occurrence_date', [now(), now()->addDays(7)])
            ->sum('amount');

        // Margen: ingresos vs gastos
        $thisMonthRevenue = (float) Order::where('user_id', $user->id)
            ->when($organizationId, fn ($q) => $q->where('organization_id', $organizationId))
            ->where('payment_status', 'paid')
            ->whereBetween('order_date', [now()->startOfMonth(), now()])
            ->sum('total');

        $marginPercent = $thisMonthRevenue > 0
            ? round((($thisMonthRevenue - $thisMonthExpenses) / $thisMonthRevenue) * 100, 1)
            : ($thisMonthExpenses > 0 ? -100 : 0);

        $expenseGrowth = $lastMonthExpenses > 0
            ? round((($thisMonthExpenses - $lastMonthExpenses) / $lastMonthExpenses) * 100, 1)
            : 0;

        // Score
        $marginScore = $this->clampScore($marginPercent > 0 ? min(100, 40 + $marginPercent) : max(0, 40 + $marginPercent));
        $overdueScore = $this->clampScore(100 - min(100, $overdueExpenses * 15));
        $controlScore = $expenseGrowth <= 10
            ? $this->clampScore(80 + (10 - $expenseGrowth))
            : $this->clampScore(max(20, 80 - ($expenseGrowth - 10) * 2));

        $score = round(($marginScore * 0.40) + ($overdueScore * 0.30) + ($controlScore * 0.30));

        $indicators = [];

        // Indicador 1: Margen
        $indicators[] = [
            'label' => 'Margen operativo',
            'value' => $marginPercent,
            'value_formatted' => $marginPercent . '%',
            'what' => "Ingresos: \${$this->fmt($thisMonthRevenue)} / Gastos: \${$this->fmt($thisMonthExpenses)}",
            'compared_to' => "Un margen saludable esta por encima del 20%",
            'impact' => $marginPercent >= 20
                ? "Buen margen, el negocio tiene espacio para invertir"
                : ($marginPercent >= 0
                    ? "Margen ajustado, controla los gastos para ganar aire"
                    : "Estas operando a perdida este mes"),
            'action' => $marginPercent < 20
                ? "Revisa gastos no esenciales y busca formas de aumentar ingresos"
                : "Mantene el equilibrio actual entre ingresos y gastos",
            'trend' => $marginPercent >= 20 ? 'up' : ($marginPercent >= 0 ? 'stable' : 'down'),
        ];

        // Indicador 2: Tendencia de gastos
        $indicators[] = [
            'label' => 'Tendencia de gastos',
            'value' => $expenseGrowth,
            'value_formatted' => ($expenseGrowth >= 0 ? '+' : '') . $expenseGrowth . '%',
            'what' => "Gastaste \${$this->fmt($thisMonthExpenses)} este mes",
            'compared_to' => "El mes anterior fueron \${$this->fmt($lastMonthExpenses)}",
            'impact' => $expenseGrowth > 20
                ? "Los gastos subieron significativamente, puede reducir tu margen"
                : ($expenseGrowth < -10
                    ? "Buena reduccion de gastos, mejora tu rentabilidad"
                    : "Los gastos se mantienen controlados"),
            'action' => $expenseGrowth > 20
                ? "Identifica que gastos nuevos aparecieron y evalua si son necesarios"
                : "Segui monitoreando los gastos periodicamente",
            'trend' => $expenseGrowth <= 0 ? 'up' : ($expenseGrowth <= 20 ? 'stable' : 'down'),
        ];

        // Indicador 3: Gastos vencidos
        if ($overdueExpenses > 0) {
            $indicators[] = [
                'label' => 'Gastos vencidos',
                'value' => $overdueExpenses,
                'value_formatted' => $overdueExpenses . ' pagos',
                'what' => "{$overdueExpenses} gastos vencidos por \${$this->fmt($overdueAmount)}",
                'compared_to' => "Todos los gastos deberian estar al dia",
                'impact' => "Los pagos atrasados generan intereses y danian la relacion con proveedores",
                'action' => "Prioriza el pago de los gastos vencidos mas urgentes",
                'trend' => 'down',
            ];
        }

        // Indicador 4: Proximos pagos recurrentes
        if ($upcomingRecurring > 0) {
            $indicators[] = [
                'label' => 'Pagos proximos',
                'value' => $upcomingRecurring,
                'value_formatted' => $upcomingRecurring . ' pagos',
                'what' => "{$upcomingRecurring} pagos recurrentes vencen esta semana (\${$this->fmt($upcomingAmount)})",
                'compared_to' => "Tener liquidez para pagos recurrentes es clave",
                'impact' => "Asegurate de tener el flujo necesario para cubrir estos pagos",
                'action' => "Verifica tu saldo disponible y agenda los pagos",
                'trend' => 'stable',
            ];
        }

        return [
            'score' => $this->clampScore($score),
            'label' => 'Costos',
            'icon' => 'account_balance_wallet',
            'color' => $this->scoreColor($score),
            'indicators' => $indicators,
            'metrics' => [
                'this_month_expenses' => $thisMonthExpenses,
                'last_month_expenses' => $lastMonthExpenses,
                'expense_growth' => $expenseGrowth,
                'overdue_expenses' => $overdueExpenses,
                'overdue_amount' => $overdueAmount,
                'margin_percent' => $marginPercent,
                'this_month_revenue' => $thisMonthRevenue,
                'upcoming_recurring' => $upcomingRecurring,
                'upcoming_amount' => $upcomingAmount,
            ],
        ];
    }

    // --- Helpers ---

    private function overallStatus(int $score): array
    {
        return match (true) {
            $score >= 80 => ['label' => 'Excelente', 'color' => '#22C55E', 'emoji' => '🟢'],
            $score >= 60 => ['label' => 'Bueno', 'color' => '#3B82F6', 'emoji' => '🔵'],
            $score >= 40 => ['label' => 'Atencion', 'color' => '#F59E0B', 'emoji' => '🟡'],
            $score >= 20 => ['label' => 'Riesgo', 'color' => '#F97316', 'emoji' => '🟠'],
            default => ['label' => 'Critico', 'color' => '#EF4444', 'emoji' => '🔴'],
        };
    }

    private function buildSummary(int $score, array $revenue, array $clients, array $inventory, array $costs): string
    {
        $parts = [];

        // Encontrar la categoría más fuerte y la más débil
        $categories = [
            'Ingresos' => $revenue['score'],
            'Clientes' => $clients['score'],
            'Inventario' => $inventory['score'],
            'Costos' => $costs['score'],
        ];

        arsort($categories);
        $strongest = array_key_first($categories);

        asort($categories);
        $weakest = array_key_first($categories);
        $weakestScore = $categories[$weakest];

        if ($score >= 80) {
            $parts[] = "Tu negocio esta en excelente forma.";
            $parts[] = "{$strongest} es tu punto mas fuerte.";
            if ($weakestScore < 70) {
                $parts[] = "Podes mejorar en {$weakest} para alcanzar el maximo potencial.";
            }
        } elseif ($score >= 60) {
            $parts[] = "Tu negocio esta en buena forma con areas de mejora.";
            $parts[] = "{$strongest} es lo que mejor funciona.";
            $parts[] = "Enfocate en mejorar {$weakest} para subir el nivel general.";
        } elseif ($score >= 40) {
            $parts[] = "Tu negocio necesita atencion en algunas areas.";
            $parts[] = "{$weakest} es el area que mas preocupa.";
            $parts[] = "Prioriza las acciones sugeridas para mejorar rapidamente.";
        } else {
            $parts[] = "Tu negocio esta en una situacion delicada.";
            $parts[] = "Varias areas necesitan atencion inmediata, especialmente {$weakest}.";
            $parts[] = "Actua sobre las recomendaciones criticas lo antes posible.";
        }

        return implode(' ', $parts);
    }

    private function revenueGrowthImpact(float $percent): string
    {
        return match (true) {
            $percent >= 30 => "Crecimiento acelerado, excelente momento para el negocio",
            $percent >= 10 => "Crecimiento sostenido, las ventas mejoran gradualmente",
            $percent >= 0 => "Ventas estables, no hay retroceso pero tampoco avance significativo",
            $percent >= -15 => "Leve caida, puede ser estacional o temporal",
            $percent >= -30 => "Caida importante, necesita atencion y accion",
            default => "Caida critica en ventas, requiere accion inmediata",
        };
    }

    private function revenueGrowthAction(float $percent): string
    {
        return match (true) {
            $percent >= 20 => "Aprovecha el impulso para lanzar nuevos productos o captar mas clientes",
            $percent >= 0 => "Busca formas de escalar: promociones, nuevos canales, upselling",
            $percent >= -15 => "Revisa tu estrategia comercial y contacta clientes inactivos",
            default => "Acciones urgentes: promociones agresivas, revision de precios, contacto directo con clientes",
        };
    }

    private function scoreColor(int $score): string
    {
        return match (true) {
            $score >= 80 => '#22C55E',
            $score >= 60 => '#3B82F6',
            $score >= 40 => '#F59E0B',
            $score >= 20 => '#F97316',
            default => '#EF4444',
        };
    }

    private function clampScore(float $score): int
    {
        return (int) max(0, min(100, round($score)));
    }

    private function fmt(float $n): string
    {
        return number_format($n, 0, ',', '.');
    }
}
