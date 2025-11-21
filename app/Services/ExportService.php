<?php

namespace App\Services;

use App\Exports\DisasterReportsExport;
use App\Repositories\DisasterReportRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Response as ResponseFactory;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportService
{
    public function __construct(private DisasterReportRepository $repository)
    {
    }

    public function exportExcel(array $filters): BinaryFileResponse
    {
        $dataset = $this->prepareDataset($filters);

        return Excel::download(
            new DisasterReportsExport($dataset),
            $this->makeFileName('yfis-disaster-reports', 'xlsx')
        );
    }

    public function exportCsv(array $filters): BinaryFileResponse
    {
        $dataset = $this->prepareDataset($filters);

        return Excel::download(
            new DisasterReportsExport($dataset),
            $this->makeFileName('yfis-disaster-reports', 'csv'),
            \Maatwebsite\Excel\Excel::CSV,
            [
                'Content-Type' => 'text/csv; charset=UTF-8',
            ]
        );
    }

    public function exportJson(array $filters): JsonResponse
    {
        $dataset = $this->prepareDataset($filters);

        return ResponseFactory::json($dataset)->withHeaders([
            'Content-Disposition' => 'attachment; filename=' . $this->makeFileName('yfis-disaster-reports', 'json'),
        ]);
    }

    public function exportDashboardPdf(array $filters): Response
    {
        $dataset = $this->prepareDataset($filters);
        $metrics = $this->repository->aggregateForDashboard($filters);

        $pdf = Pdf::loadView('disaster_reports.exports.dashboard', [
            'reports' => $dataset,
            'metrics' => $metrics,
        ]);

        return $pdf->download($this->makeFileName('yfis-dashboard-summary', 'pdf'));
    }

    public function exportDashboardExcel(array $filters): BinaryFileResponse
    {
        $dataset = $this->prepareDataset($filters);

        return Excel::download(
            new DisasterReportsExport($dataset),
            $this->makeFileName('yfis-dashboard-dataset', 'xlsx')
        );
    }

    private function prepareDataset(array $filters): Collection
    {
        return $this->repository->getWithFilters($filters)->map(function ($report) {
            return [
                'reported_at' => optional($report->reported_at)->format('Y-m-d H:i:s'),
                'disaster_type' => $report->disaster_type,
                'organization_name' => $report->organization_name,
                'district' => $report->district?->name,
                'affiliation' => $report->affiliation?->name,
                'current_status' => $report->current_status,
                'teaching_status' => $report->teaching_status,
                'affected_students' => $report->affected_students,
                'injured_students' => $report->injured_students,
                'dead_students' => $report->dead_students,
                'affected_staff' => $report->affected_staff,
                'injured_staff' => $report->injured_staff,
                'dead_staff' => $report->dead_staff,
                'damage_building' => (float) $report->damage_building,
                'damage_equipment' => (float) $report->damage_equipment,
                'damage_material' => (float) $report->damage_material,
                'damage_total_request' => (float) $report->damage_total_request,
                'assistance_received' => $report->assistance_received,
                'contact_name' => $report->contact_name,
                'contact_position' => $report->contact_position,
                'contact_phone' => $report->contact_phone,
                'latitude' => $report->latitude,
                'longitude' => $report->longitude,
            ];
        });
    }

    private function makeFileName(string $base, string $extension): string
    {
        return Str::of($base)
            ->append('-')
            ->append(Date::now()->format('Ymd_His'))
            ->append('.')
            ->append($extension)
            ->value();
    }
}
