<?php

namespace App\Services;

use App\Models\DisasterReport;
use App\Repositories\DisasterReportRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class DisasterReportService
{
    public function __construct(
        private DisasterReportRepository $repository,
        private ActivityLogger $activityLogger
    ) {
    }

    public function paginate(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $filters = $this->applyUserAffiliationFilter($filters);
        return $this->repository->paginateWithFilters($filters, $perPage);
    }

    public function dataset(array $filters): Collection
    {
        $filters['is_published'] = true;
        $filters = $this->applyUserAffiliationFilter($filters);
        return $this->repository->getWithFilters($filters);
    }

    public function dashboardData(array $filters): array
    {
        $filters['is_published'] = true;
        $filters = $this->applyUserAffiliationFilter($filters);
        
        // Base query for counts
        $baseQuery = $this->repository->getFilteredQuery($filters);

        // Severe impact: Flood + Damage > 0
        $severeCount = $baseQuery->clone()
            ->where('disaster_type', 'น้ำท่วม')
            ->whereRaw('(damage_building + damage_equipment + damage_material) > 0')
            ->count();

        $totalReports = $baseQuery->clone()->count();
        $closedCount = $baseQuery->clone()->where('teaching_status', 'closed')->count();
        
        $totalSchoolsBase = config('settings.total_schools_base', 1102);

        // Damage by Category
        $damageByCategory = $baseQuery->clone()
            ->selectRaw('sum(damage_building) as building, sum(damage_equipment) as equipment, sum(damage_material) as material')
            ->first()
            ->toArray();
            
        // Ensure floats
        $damageByCategory = array_map('floatval', $damageByCategory);

        return [
            'metrics' => $this->repository->aggregateForDashboard($filters),
            'summary' => [
                'total_affected' => $totalReports,
                'affected_percent' => ($totalReports / $totalSchoolsBase) * 100,
                'total_schools_base' => $totalSchoolsBase,
                'total_closed' => $closedCount,
                'closed_percent' => $totalReports > 0 ? ($closedCount / $totalReports) * 100 : 0,
                'severe_count' => $severeCount,
                'total_damage' => (float) ($damageByCategory['building'] + $damageByCategory['equipment'] + $damageByCategory['material']),
            ],
            'damageByCategory' => $damageByCategory,
            'disasterTypeTotals' => $baseQuery->clone()
                ->groupBy('disaster_type')
                ->selectRaw('disaster_type, count(*) as count')
                ->pluck('count', 'disaster_type'),
            'timeline' => $baseQuery->clone()
                ->selectRaw('DATE(reported_at) as date, count(*) as count')
                ->groupBy('date')
                ->orderBy('date')
                ->pluck('count', 'date'),
            'statusBreakdown' => $baseQuery->clone()
                ->groupBy('current_status')
                ->selectRaw('current_status, count(*) as count')
                ->pluck('count', 'current_status'),
            'teachingStatus' => $baseQuery->clone()
                ->groupBy('teaching_status')
                ->selectRaw('teaching_status, count(*) as count')
                ->pluck('count', 'teaching_status'),
            'humanImpact' => [
                'students' => $baseQuery->clone()->selectRaw('sum(affected_students) as affected, sum(injured_students) as injured, sum(dead_students) as dead')->first()->toArray(),
                'staff' => $baseQuery->clone()->selectRaw('sum(affected_staff) as affected, sum(injured_staff) as injured, sum(dead_staff) as dead')->first()->toArray(),
            ],
            'damageByDistrict' => $baseQuery->clone()
                ->join('districts', 'disaster_reports.district_id', '=', 'districts.id')
                ->groupBy('districts.name')
                ->selectRaw('districts.name, sum(damage_total_request) as total')
                ->orderByDesc('total')
                ->limit(10)
                ->pluck('total', 'districts.name'),
            'reportsByAffiliation' => $baseQuery->clone()
                ->join('affiliations', 'disaster_reports.affiliation_id', '=', 'affiliations.id')
                ->groupBy('affiliations.name')
                ->selectRaw('affiliations.name, count(*) as count')
                ->orderByDesc('count')
                ->limit(10)
                ->pluck('count', 'affiliations.name'),
            'mapPoints' => $baseQuery->clone()
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->get(['organization_name', 'current_status', 'damage_total_request as damage', 'latitude as lat', 'longitude as lng'])
                ->map(fn ($item) => [
                    'organization' => $item->organization_name,
                    'status' => $item->current_status,
                    'damage' => (float) $item->damage,
                    'lat' => (float) $item->lat,
                    'lng' => (float) $item->lng,
                ]),
            'sparklines' => $this->buildSparklineTimelines($filters),
        ];
    }

    public function store(array $data): DisasterReport
    {
        $sanitized = $this->sanitize($data);
        $sanitized['form_hash'] = $this->makeFormHash($sanitized);

        $this->guardAgainstDuplicate($sanitized['form_hash']);

        $report = $this->repository->create($sanitized);

        $this->activityLogger->log('disaster_report.created', $report, [
            'attributes' => $report->only(array_keys($sanitized)),
        ]);

        return $report;
    }

    public function update(DisasterReport $report, array $data): DisasterReport
    {
        $sanitized = $this->sanitize($data);
        $formHash = $this->makeFormHash($sanitized);

        $before = $report->only(array_keys($sanitized));

        if ($report->form_hash !== $formHash) {
            $this->guardAgainstDuplicate($formHash);
        }

        $sanitized['form_hash'] = $formHash;

        $updated = $this->repository->update($report, $sanitized);

        $this->activityLogger->log('disaster_report.updated', $updated, [
            'before' => $before,
            'after' => $updated->only(array_keys($sanitized)),
        ]);

        return $updated;
    }

    public function publish(DisasterReport $report): void
    {
        $this->repository->update($report, ['is_published' => true]);
        $this->activityLogger->log('disaster_report.published', $report);
    }

    public function unpublish(DisasterReport $report): void
    {
        $this->repository->update($report, ['is_published' => false]);
        $this->activityLogger->log('disaster_report.unpublished', $report);
    }

    public function delete(DisasterReport $report): void
    {
        $snapshot = $report->toArray();
        $this->repository->delete($report);

        $this->activityLogger->log('disaster_report.deleted', $report, [
            'attributes' => $snapshot,
        ]);
    }

    private function guardAgainstDuplicate(string $hash): void
    {
        if (DisasterReport::where('form_hash', $hash)->exists()) {
            throw ValidationException::withMessages([
                'form_hash' => __('ระบบพบว่ารายการนี้ถูกบันทึกไว้แล้ว กรุณาตรวจสอบข้อมูลก่อนส่งซ้ำอีกครั้ง'),
            ]);
        }
    }

    private function makeFormHash(array $data): string
    {
        $fields = [
            $data['reported_at'] ?? null,
            $data['disaster_type'] ?? null,
            $data['organization_name'] ?? null,
            $data['district_id'] ?? null,
            $data['affiliation_id'] ?? null,
            $data['current_status'] ?? null,
            $data['teaching_status'] ?? null,
            $data['damage_total_request'] ?? null,
        ];

        return hash('sha256', json_encode($fields, JSON_THROW_ON_ERROR));
    }

    private function sanitize(array $data): array
    {
        $validator = Validator::make($data, [
            'contact_phone' => ['nullable', 'string'],
        ]);
        $validator->validate();

        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $data[$key] = trim(strip_tags($value));
            }
        }

        if (! empty($data['contact_phone'])) {
            $data['contact_phone'] = preg_replace('/[^0-9+]/', '', $data['contact_phone']);
        }

        return $data;
    }

    /**
     * Build sparkline timeline data for the last 7 days
     */
    /**
     * Build sparkline timeline data for the last 7 days
     */
    private function buildSparklineTimelines(array $filters): array
    {
        // Get last 7 days dates
        $dates = collect();
        for ($i = 6; $i >= 0; $i--) {
            $dates->push(now()->subDays($i)->format('Y-m-d'));
        }

        $baseQuery = $this->repository->getFilteredQuery($filters);
        $startDate = now()->subDays(6)->startOfDay();
        $endDate = now()->endOfDay();

        // Get aggregated data grouped by date
        $dailyStats = $baseQuery->clone()
            ->whereBetween('reported_at', [$startDate, $endDate])
            ->selectRaw('
                DATE(reported_at) as date,
                count(*) as affected_institutions,
                sum(case when teaching_status = "closed" then 1 else 0 end) as closed_institutions,
                sum(affected_students) as students_affected,
                sum(dead_students) as students_dead,
                sum(affected_staff) as staff_affected,
                sum(dead_staff) as staff_dead,
                sum(damage_building) as damage_building,
                sum(damage_equipment) as damage_equipment,
                sum(damage_material) as damage_material,
                sum(case when disaster_type = "น้ำท่วม" AND (damage_building + damage_equipment + damage_material) > 0 then 1 else 0 end) as severe_impact
            ')
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        // Map to last 7 days with zero padding
        $result = [
            'affected_institutions' => [],
            'closed_institutions' => [],
            'students_affected' => [],
            'students_dead' => [],
            'staff_affected' => [],
            'staff_dead' => [],
            'damage_building' => [],
            'damage_equipment' => [],
            'damage_material' => [],
            'severe_impact' => [],
        ];

        foreach ($dates as $date) {
            $stats = $dailyStats->get($date);
            
            $result['affected_institutions'][] = (int) ($stats->affected_institutions ?? 0);
            $result['closed_institutions'][] = (int) ($stats->closed_institutions ?? 0);
            $result['students_affected'][] = (int) ($stats->students_affected ?? 0);
            $result['students_dead'][] = (int) ($stats->students_dead ?? 0);
            $result['staff_affected'][] = (int) ($stats->staff_affected ?? 0);
            $result['staff_dead'][] = (int) ($stats->staff_dead ?? 0);
            $result['damage_building'][] = (float) ($stats->damage_building ?? 0);
            $result['damage_equipment'][] = (float) ($stats->damage_equipment ?? 0);
            $result['damage_material'][] = (float) ($stats->damage_material ?? 0);
            $result['severe_impact'][] = (int) ($stats->severe_impact ?? 0);
        }

        return $result;
    }

    /**
     * Apply affiliation filter for YFIS users.
     */
    private function applyUserAffiliationFilter(array $filters): array
    {
        $user = auth()->user();
        
        // If user is YFIS role and has an affiliation, filter by their affiliation
        if ($user && $user->role === 'yfis' && $user->affiliation_id) {
            $filters['affiliation_id'] = $user->affiliation_id;
        }
        
        return $filters;
    }
}
