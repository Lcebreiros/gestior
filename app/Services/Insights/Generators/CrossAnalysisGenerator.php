<?php

namespace App\Services\Insights\Generators;

use App\Models\BusinessInsight;
use App\Models\Client;
use App\Models\Expense;
use App\Models\Order;
use App\Models\SupplySchedule;
use App\Models\User;
use App\Services\Insights\InsightGeneratorInterface;
use Illuminate\Support\Facades\DB;

/**
 * Nivel 3: Inteligencia de Decisión.
 *
 * Conecta variables de múltiples categorías para generar insights cruzados.
 * Cada insight sigue la fórmula dorada:
 * Qué pasó + Comparado con qué + Impacto + Acción sugerida
 */
class CrossAnalysisGenerator implements InsightGeneratorInterface
{
    private float $thisMonthRevenue = 0;
    private float $lastMonthRevenue = 0;
    private float $thisMonthExpenses = 0;
    private float $lastMonthExpenses = 0;
    private int $outOfStockCount = 0;
    private int $lowStockCount = 0;
    private int $overduePaymentsCount = 0;
    private float $overduePaymentsTotal = 0;
    private int $overdueExpensesCount = 0;
    private float $overdueExpensesTotal = 0;
    private int $activeClientsThisMonth = 0;
    private int $activeClientsLastMonth = 0;
    private int $dormantClientsCount = 0;
    private float $dormantClientsRevenue = 0;

    public function generate(User $user, ?int $organizationId): array
    {
        $this->collectMetrics($user, $organizationId);

        $insights = [];

        $insights = array_merge(
            $insights,
            $this->marginSqueeze($user, $organizationId),
            $this->revenueLeakage($user, $organizationId),
            $this->stockRevenueImpact($user, $organizationId),
            $this->clientConcentrationRisk($user, $organizationId),
            $this->cashFlowPressure($user, $organizationId),
            $this->growthDiagnosis($user, $organizationId),
        );

        return $insights;
    }

    /**
     * Recolecta todas las métricas base en una sola pasada.
     */
    private function collectMetrics(User $user, ?int $organizationId): void
    {
        $orderBase = Order::where('user_id', $user->id)
            ->when($organizationId, fn ($q) => $q->where('organization_id', $organizationId));

        $expenseBase = Expense::where('user_id', $user->id)
            ->when($organizationId, fn ($q) => $q->where('organization_id', $organizationId));

        $stockBase = SupplySchedule::where('user_id', $user->id)
            ->when($organizationId, fn ($q) => $q->where('organization_id', $organizationId));

        // Revenue
        $this->thisMonthRevenue = (float) (clone $orderBase)
            ->where('payment_status', 'paid')
            ->whereBetween('order_date', [now()->startOfMonth(), now()])
            ->sum('total');

        $this->lastMonthRevenue = (float) (clone $orderBase)
            ->where('payment_status', 'paid')
            ->whereBetween('order_date', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->sum('total');

        // Expenses
        $this->thisMonthExpenses = (float) (clone $expenseBase)
            ->whereBetween('expense_date', [now()->startOfMonth(), now()])
            ->sum('amount');

        $this->lastMonthExpenses = (float) (clone $expenseBase)
            ->whereBetween('expense_date', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->sum('amount');

        // Stock
        $this->outOfStockCount = (clone $stockBase)->outOfStock()->count();
        $this->lowStockCount = (clone $stockBase)->lowStock()->count();

        // Overdue payments (receivable)
        $overdueOrders = (clone $orderBase)
            ->where('payment_status', '!=', 'paid')
            ->where('payment_deadline', '<', now())
            ->get();
        $this->overduePaymentsCount = $overdueOrders->count();
        $this->overduePaymentsTotal = (float) $overdueOrders->sum('total');

        // Overdue expenses (payable)
        $overdueExpenses = (clone $expenseBase)->overdue()->get();
        $this->overdueExpensesCount = $overdueExpenses->count();
        $this->overdueExpensesTotal = (float) $overdueExpenses->sum('amount');

        // Clients
        $this->activeClientsThisMonth = (clone $orderBase)
            ->where('payment_status', 'paid')
            ->whereBetween('order_date', [now()->startOfMonth(), now()])
            ->whereNotNull('client_id')
            ->distinct('client_id')
            ->count('client_id');

        $this->activeClientsLastMonth = (clone $orderBase)
            ->where('payment_status', 'paid')
            ->whereBetween('order_date', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->whereNotNull('client_id')
            ->distinct('client_id')
            ->count('client_id');

        // Dormant clients
        $dormant = DB::table('orders')
            ->select('client_id', DB::raw('SUM(total) as total_spent'))
            ->where('user_id', $user->id)
            ->when($organizationId, fn ($q) => $q->where('organization_id', $organizationId))
            ->where('payment_status', 'paid')
            ->whereNotNull('client_id')
            ->groupBy('client_id')
            ->havingRaw('COUNT(*) >= 2')
            ->havingRaw('MAX(order_date) < ?', [now()->subDays(30)->toDateString()])
            ->get();

        $this->dormantClientsCount = $dormant->count();
        $this->dormantClientsRevenue = (float) $dormant->sum('total_spent');
    }

    /**
     * Cruce: Ventas suben + Gastos suben más = Margen comprimido.
     */
    private function marginSqueeze(User $user, ?int $organizationId): array
    {
        if ($this->lastMonthRevenue <= 0 || $this->lastMonthExpenses <= 0) {
            return [];
        }

        $revenueGrowth = (($this->thisMonthRevenue - $this->lastMonthRevenue) / $this->lastMonthRevenue) * 100;
        $expenseGrowth = (($this->thisMonthExpenses - $this->lastMonthExpenses) / $this->lastMonthExpenses) * 100;

        $lastMargin = $this->lastMonthRevenue > 0
            ? (($this->lastMonthRevenue - $this->lastMonthExpenses) / $this->lastMonthRevenue) * 100
            : 0;

        $currentMargin = $this->thisMonthRevenue > 0
            ? (($this->thisMonthRevenue - $this->thisMonthExpenses) / $this->thisMonthRevenue) * 100
            : 0;

        $marginDelta = $currentMargin - $lastMargin;

        // Solo si los gastos crecen más que los ingresos y el margen se reduce
        if ($expenseGrowth <= $revenueGrowth || $marginDelta >= -3) {
            return [];
        }

        $severity = abs($marginDelta);
        $priority = $severity > 15
            ? BusinessInsight::PRIORITY_HIGH
            : ($severity > 8 ? BusinessInsight::PRIORITY_MEDIUM : BusinessInsight::PRIORITY_LOW);

        return [[
            'type' => BusinessInsight::TYPE_TREND,
            'priority' => $priority,
            'title' => 'Margen comprimido: gastos crecen mas que ventas',
            'description' => "Ventas " . ($revenueGrowth >= 0 ? 'subieron' : 'bajaron') . ' '
                . abs(round($revenueGrowth)) . '% pero gastos subieron '
                . round($expenseGrowth) . '%. El margen paso de '
                . round($lastMargin) . '% a ' . round($currentMargin) . '%. '
                . 'Cada venta te deja menos ganancia que el mes pasado.',
            'metadata' => [
                'revenue_growth' => round($revenueGrowth, 1),
                'expense_growth' => round($expenseGrowth, 1),
                'margin_last_month' => round($lastMargin, 1),
                'margin_this_month' => round($currentMargin, 1),
                'margin_delta' => round($marginDelta, 1),
                'cross_type' => 'margin_squeeze',
            ],
            'action_label' => 'Ver gastos',
            'action_route' => '/expenses',
            'dedupe_key' => 'cross_margin_squeeze_' . now()->format('Y-m'),
            'expires_at' => now()->endOfMonth(),
        ]];
    }

    /**
     * Cruce: Pagos vencidos + Gastos vencidos = Doble presión sobre flujo de caja.
     */
    private function cashFlowPressure(User $user, ?int $organizationId): array
    {
        if ($this->overduePaymentsCount === 0 || $this->overdueExpensesCount === 0) {
            return [];
        }

        $totalExposed = $this->overduePaymentsTotal + $this->overdueExpensesTotal;

        $priority = $totalExposed > 100000
            ? BusinessInsight::PRIORITY_HIGH
            : BusinessInsight::PRIORITY_MEDIUM;

        return [[
            'type' => BusinessInsight::TYPE_COST_WARNING,
            'priority' => $priority,
            'title' => 'Presion doble sobre el flujo de caja',
            'description' => "Tenes \${$this->fmt($this->overduePaymentsTotal)} en cobros pendientes de clientes "
                . "Y \${$this->fmt($this->overdueExpensesTotal)} en gastos vencidos por pagar. "
                . "El descalce total es \${$this->fmt($totalExposed)}. "
                . "Prioriza cobrar para poder cubrir los gastos.",
            'metadata' => [
                'overdue_receivable' => $this->overduePaymentsTotal,
                'overdue_payable' => $this->overdueExpensesTotal,
                'total_exposed' => $totalExposed,
                'overdue_orders_count' => $this->overduePaymentsCount,
                'overdue_expenses_count' => $this->overdueExpensesCount,
                'cross_type' => 'cash_flow_pressure',
            ],
            'action_label' => 'Ver pedidos',
            'action_route' => '/orders',
            'dedupe_key' => 'cross_cashflow_pressure',
            'expires_at' => now()->addDays(5),
        ]];
    }

    /**
     * Cruce: Stock bajo/agotado + ventas cayendo = posible pérdida de ventas por faltante.
     */
    private function stockRevenueImpact(User $user, ?int $organizationId): array
    {
        if ($this->outOfStockCount === 0 || $this->lastMonthRevenue <= 0) {
            return [];
        }

        $revenueGrowth = (($this->thisMonthRevenue - $this->lastMonthRevenue) / $this->lastMonthRevenue) * 100;

        // Solo si las ventas bajan y hay items sin stock
        if ($revenueGrowth >= -5) {
            return [];
        }

        $totalStockIssues = $this->outOfStockCount + $this->lowStockCount;
        $revenueLost = abs($this->thisMonthRevenue - $this->lastMonthRevenue);

        return [[
            'type' => BusinessInsight::TYPE_STOCK_ALERT,
            'priority' => BusinessInsight::PRIORITY_HIGH,
            'title' => 'Faltantes de stock pueden estar frenando ventas',
            'description' => "Las ventas cayeron " . abs(round($revenueGrowth)) . "% (\${$this->fmt($revenueLost)} menos) "
                . "y tenes {$this->outOfStockCount} insumos agotados"
                . ($this->lowStockCount > 0 ? " y {$this->lowStockCount} con stock bajo" : '') . '. '
                . 'La falta de stock podria ser una causa directa de la caida en ventas.',
            'metadata' => [
                'revenue_drop_percent' => round($revenueGrowth, 1),
                'revenue_lost' => $revenueLost,
                'out_of_stock' => $this->outOfStockCount,
                'low_stock' => $this->lowStockCount,
                'cross_type' => 'stock_revenue_impact',
            ],
            'action_label' => 'Ver inventario',
            'action_route' => '/stock',
            'dedupe_key' => 'cross_stock_revenue_' . now()->format('Y-m'),
            'expires_at' => now()->addDays(7),
        ]];
    }

    /**
     * Cruce: Clientes perdidos + Ventas cayendo = diagnóstico de la caída.
     */
    private function revenueLeakage(User $user, ?int $organizationId): array
    {
        if ($this->activeClientsLastMonth === 0 || $this->lastMonthRevenue <= 0) {
            return [];
        }

        $revenueGrowth = (($this->thisMonthRevenue - $this->lastMonthRevenue) / $this->lastMonthRevenue) * 100;
        $clientGrowth = (($this->activeClientsThisMonth - $this->activeClientsLastMonth) / $this->activeClientsLastMonth) * 100;

        // Solo si ventas y clientes bajan juntos
        if ($revenueGrowth >= -5 || $clientGrowth >= -5) {
            return [];
        }

        $lostClients = $this->activeClientsLastMonth - $this->activeClientsThisMonth;

        // Estimar revenue por cliente perdido
        $avgRevenuePerClient = $this->lastMonthRevenue / $this->activeClientsLastMonth;
        $estimatedLostRevenue = $avgRevenuePerClient * $lostClients;

        return [[
            'type' => BusinessInsight::TYPE_CLIENT_RETENTION,
            'priority' => BusinessInsight::PRIORITY_HIGH,
            'title' => 'Caida de ventas conectada con perdida de clientes',
            'description' => "Perdiste {$lostClients} clientes activos (" . abs(round($clientGrowth)) . "% menos) "
                . "y las ventas cayeron " . abs(round($revenueGrowth)) . "%. "
                . "Estimamos que esos clientes representaban ~\${$this->fmt($estimatedLostRevenue)}/mes. "
                . "La retencion de clientes es clave para frenar la caida.",
            'metadata' => [
                'revenue_drop_percent' => round($revenueGrowth, 1),
                'client_drop_percent' => round($clientGrowth, 1),
                'lost_clients' => $lostClients,
                'estimated_lost_revenue' => round($estimatedLostRevenue, 2),
                'avg_revenue_per_client' => round($avgRevenuePerClient, 2),
                'cross_type' => 'revenue_leakage',
            ],
            'action_label' => 'Ver clientes',
            'action_route' => '/clients',
            'dedupe_key' => 'cross_revenue_leakage_' . now()->format('Y-m'),
            'expires_at' => now()->endOfMonth(),
        ]];
    }

    /**
     * Cruce: Alta concentración de ventas en pocos clientes + clientes dormidos.
     */
    private function clientConcentrationRisk(User $user, ?int $organizationId): array
    {
        if ($this->thisMonthRevenue <= 0 || $this->activeClientsThisMonth < 2) {
            return [];
        }

        // Top 3 clientes y su participación
        $topClients = DB::table('orders')
            ->select('client_id', 'client_name', DB::raw('SUM(total) as total'))
            ->where('user_id', $user->id)
            ->when($organizationId, fn ($q) => $q->where('organization_id', $organizationId))
            ->where('payment_status', 'paid')
            ->whereBetween('order_date', [now()->startOfMonth(), now()])
            ->whereNotNull('client_id')
            ->groupBy('client_id', 'client_name')
            ->orderByDesc('total')
            ->limit(3)
            ->get();

        if ($topClients->isEmpty()) {
            return [];
        }

        $topRevenue = (float) $topClients->sum('total');
        $topConcentration = round(($topRevenue / $this->thisMonthRevenue) * 100);

        // Solo alertar si top 3 concentran +70% Y hay clientes dormidos
        if ($topConcentration < 70 || $this->dormantClientsCount < 2) {
            return [];
        }

        $topNames = $topClients->pluck('client_name')
            ->map(fn ($n) => $n ?? 'Sin nombre')
            ->implode(', ');

        return [[
            'type' => BusinessInsight::TYPE_CLIENT_RETENTION,
            'priority' => BusinessInsight::PRIORITY_MEDIUM,
            'title' => 'Alto riesgo de concentracion de clientes',
            'description' => "Tus 3 principales clientes ({$topNames}) representan el {$topConcentration}% de las ventas "
                . "y tenes {$this->dormantClientsCount} clientes recurrentes dormidos que antes aportaban "
                . "\${$this->fmt($this->dormantClientsRevenue)} en total. "
                . "Reactivar clientes dormidos reduce el riesgo de depender de pocos.",
            'metadata' => [
                'top_3_concentration' => $topConcentration,
                'top_clients' => $topClients->map(fn ($c) => [
                    'name' => $c->client_name,
                    'total' => (float) $c->total,
                ])->toArray(),
                'dormant_clients' => $this->dormantClientsCount,
                'dormant_historical_revenue' => $this->dormantClientsRevenue,
                'cross_type' => 'client_concentration_risk',
            ],
            'action_label' => 'Ver clientes',
            'action_route' => '/clients',
            'dedupe_key' => 'cross_client_concentration_' . now()->format('Y-m'),
            'expires_at' => now()->endOfMonth(),
        ]];
    }

    /**
     * Diagnóstico integral de crecimiento: conecta ingresos, clientes, margen.
     */
    private function growthDiagnosis(User $user, ?int $organizationId): array
    {
        if ($this->lastMonthRevenue <= 0 || $this->lastMonthExpenses <= 0) {
            return [];
        }

        $revenueGrowth = (($this->thisMonthRevenue - $this->lastMonthRevenue) / $this->lastMonthRevenue) * 100;
        $clientGrowth = $this->activeClientsLastMonth > 0
            ? (($this->activeClientsThisMonth - $this->activeClientsLastMonth) / $this->activeClientsLastMonth) * 100
            : 0;

        $currentMargin = $this->thisMonthRevenue > 0
            ? (($this->thisMonthRevenue - $this->thisMonthExpenses) / $this->thisMonthRevenue) * 100
            : 0;

        $lastMargin = (($this->lastMonthRevenue - $this->lastMonthExpenses) / $this->lastMonthRevenue) * 100;

        // Clasificar estado estratégico
        $state = $this->classifyGrowthState($revenueGrowth, $clientGrowth, $currentMargin, $lastMargin);

        if ($state === null) {
            return [];
        }

        return [[
            'type' => BusinessInsight::TYPE_PREDICTION,
            'priority' => $state['priority'],
            'title' => $state['title'],
            'description' => $state['description'],
            'metadata' => [
                'revenue_growth' => round($revenueGrowth, 1),
                'client_growth' => round($clientGrowth, 1),
                'current_margin' => round($currentMargin, 1),
                'last_margin' => round($lastMargin, 1),
                'strategic_state' => $state['state'],
                'cross_type' => 'growth_diagnosis',
            ],
            'action_label' => $state['action_label'],
            'action_route' => $state['action_route'],
            'dedupe_key' => 'cross_growth_diagnosis_' . now()->format('Y-m'),
            'expires_at' => now()->endOfMonth(),
        ]];
    }

    /**
     * Clasifica el estado estratégico del negocio combinando múltiples señales.
     */
    private function classifyGrowthState(
        float $revenueGrowth,
        float $clientGrowth,
        float $currentMargin,
        float $lastMargin
    ): ?array {
        $marginDelta = $currentMargin - $lastMargin;

        // Escenario 1: Crecimiento sano (ventas suben, clientes suben, margen estable o sube)
        if ($revenueGrowth > 10 && $clientGrowth > 0 && $marginDelta >= -3) {
            return [
                'state' => 'healthy_growth',
                'priority' => BusinessInsight::PRIORITY_LOW,
                'title' => 'Diagnostico: Crecimiento saludable',
                'description' => "Ventas +" . round($revenueGrowth) . "%, clientes +" . round($clientGrowth)
                    . "% y margen " . ($marginDelta >= 0 ? 'estable' : 'apenas ajustado')
                    . " en " . round($currentMargin) . "%. "
                    . "El negocio crece de forma sostenible. "
                    . "Es buen momento para invertir en captacion o expandir oferta.",
                'action_label' => 'Ver ventas',
                'action_route' => '/orders',
            ];
        }

        // Escenario 2: Crecimiento riesgoso (ventas suben, margen baja)
        if ($revenueGrowth > 10 && $marginDelta < -5) {
            return [
                'state' => 'risky_growth',
                'priority' => BusinessInsight::PRIORITY_MEDIUM,
                'title' => 'Diagnostico: Crecimiento a costa de rentabilidad',
                'description' => "Las ventas crecen +" . round($revenueGrowth) . "% pero el margen cayo "
                    . abs(round($marginDelta)) . " puntos (" . round($lastMargin) . "% -> " . round($currentMargin) . "%). "
                    . "Estas vendiendo mas pero ganando menos por venta. "
                    . "Revisa si hay descuentos excesivos o costos ocultos.",
                'action_label' => 'Ver gastos',
                'action_route' => '/expenses',
            ];
        }

        // Escenario 3: Estancamiento (ventas planas, clientes planos)
        if (abs($revenueGrowth) < 5 && abs($clientGrowth) < 5) {
            return [
                'state' => 'stagnation',
                'priority' => BusinessInsight::PRIORITY_MEDIUM,
                'title' => 'Diagnostico: Negocio estancado',
                'description' => "Ventas " . ($revenueGrowth >= 0 ? '+' : '') . round($revenueGrowth)
                    . "% y clientes " . ($clientGrowth >= 0 ? '+' : '') . round($clientGrowth) . "%. "
                    . "Sin crecimiento ni caida. "
                    . "El estancamiento prolongado suele preceder a la caida. "
                    . "Considera lanzar promociones o explorar nuevos canales.",
                'action_label' => 'Ver clientes',
                'action_route' => '/clients',
            ];
        }

        // Escenario 4: Caída con margen presionado (ventas bajan, margen baja)
        if ($revenueGrowth < -10 && $currentMargin < 15) {
            return [
                'state' => 'decline',
                'priority' => BusinessInsight::PRIORITY_HIGH,
                'title' => 'Diagnostico: Negocio en retroceso',
                'description' => "Ventas " . round($revenueGrowth) . "% y margen en " . round($currentMargin)
                    . "% (era " . round($lastMargin) . "%). "
                    . "La combinacion de menos ventas y menos margen requiere accion inmediata. "
                    . "Prioriza: 1) Cobrar pendientes, 2) Reducir gastos no esenciales, 3) Contactar clientes top.",
                'action_label' => 'Ver pedidos',
                'action_route' => '/orders',
            ];
        }

        // Escenario 5: Pérdida de clientes con ventas estables = ticket subiendo pero riesgo
        if ($clientGrowth < -15 && $revenueGrowth > -5) {
            return [
                'state' => 'client_erosion',
                'priority' => BusinessInsight::PRIORITY_MEDIUM,
                'title' => 'Diagnostico: Perdida silenciosa de clientes',
                'description' => "Las ventas se mantienen (" . ($revenueGrowth >= 0 ? '+' : '') . round($revenueGrowth)
                    . "%) pero perdiste " . abs(round($clientGrowth)) . "% de clientes activos. "
                    . "Esto significa que pocos clientes compran mas, pero la base se achica. "
                    . "Si un cliente grande deja de comprar, el impacto sera fuerte.",
                'action_label' => 'Ver clientes',
                'action_route' => '/clients',
            ];
        }

        return null;
    }

    private function fmt(float $n): string
    {
        return number_format($n, 0, ',', '.');
    }
}
