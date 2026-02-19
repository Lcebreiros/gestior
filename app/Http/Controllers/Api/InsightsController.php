<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Insights\InsightsService;
use Illuminate\Http\Request;

class InsightsController extends Controller
{
    public function __construct(
        protected InsightsService $insightsService
    ) {}

    /**
     * Listar insights activos con filtros opcionales.
     *
     * GET /insights?type=stock_alert&priority=high&limit=20
     */
    public function index(Request $request)
    {
        $request->validate([
            'type' => 'nullable|string|in:stock_alert,revenue_opportunity,cost_warning,client_retention,trend,prediction,reminder',
            'priority' => 'nullable|string|in:critical,high,medium,low',
            'limit' => 'nullable|integer|min:1|max:50',
        ]);

        $user = $request->user();
        $organizationId = $request->input('organization_id');

        $insights = $this->insightsService->getInsights(
            user: $user,
            organizationId: $organizationId,
            type: $request->input('type'),
            priority: $request->input('priority'),
            limit: $request->input('limit', 20),
        );

        return response()->json([
            'success' => true,
            'data' => $insights,
        ]);
    }

    /**
     * Generar nuevos insights ejecutando todos los generadores.
     *
     * POST /insights/generate
     */
    public function generate(Request $request)
    {
        $request->validate([
            'clear_existing' => 'nullable|boolean',
        ]);

        $user = $request->user();
        $organizationId = $request->input('organization_id');

        $insights = $this->insightsService->generateInsights(
            user: $user,
            organizationId: $organizationId,
            clearExisting: $request->boolean('clear_existing', false),
        );

        return response()->json([
            'success' => true,
            'data' => [
                'insights' => $insights,
                'generated_at' => now()->toIso8601String(),
                'count' => count($insights),
            ],
        ]);
    }

    /**
     * Descartar un insight.
     *
     * PATCH /insights/{id}/dismiss
     */
    public function dismiss(Request $request, int $id)
    {
        $user = $request->user();
        $organizationId = $request->input('organization_id');

        $this->insightsService->dismissInsight(
            user: $user,
            insightId: $id,
            organizationId: $organizationId,
        );

        return response()->json([
            'success' => true,
            'message' => 'Insight descartado correctamente',
        ]);
    }

    /**
     * Estadísticas de insights activos.
     *
     * GET /insights/stats
     */
    public function stats(Request $request)
    {
        $user = $request->user();
        $organizationId = $request->input('organization_id');

        $stats = $this->insightsService->getStats(
            user: $user,
            organizationId: $organizationId,
        );

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }
}
