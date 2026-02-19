<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Insights\BusinessHealthReportService;
use Illuminate\Http\Request;

class HealthReportController extends Controller
{
    public function __construct(
        protected BusinessHealthReportService $healthReportService
    ) {}

    /**
     * Generar reporte de salud del negocio.
     *
     * GET /insights/health-report
     */
    public function show(Request $request)
    {
        $user = $request->user();
        $organizationId = $request->input('organization_id');

        $report = $this->healthReportService->generate(
            user: $user,
            organizationId: $organizationId,
        );

        return response()->json([
            'success' => true,
            'data' => $report,
        ]);
    }
}
