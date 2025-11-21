<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterDisasterReportRequest;
use App\Services\ExportService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class ExportController extends Controller
{
    public function __construct(private ExportService $exportService)
    {
        $this->middleware('auth')->except(['dashboardPdf', 'dashboardExcel']);
    }

    public function disasterReports(FilterDisasterReportRequest $request, string $type): BinaryFileResponse|JsonResponse
    {
        $filters = $request->filters();

        return match ($type) {
            'excel' => $this->exportService->exportExcel($filters),
            'csv' => $this->exportService->exportCsv($filters),
            'json' => $this->exportService->exportJson($filters),
            default => abort(404),
        };
    }

    public function dashboardPdf(FilterDisasterReportRequest $request): Response
    {
        return $this->exportService->exportDashboardPdf($request->filters());
    }

    public function dashboardExcel(FilterDisasterReportRequest $request): BinaryFileResponse
    {
        return $this->exportService->exportDashboardExcel($request->filters());
    }
}
