<?php

namespace App\Services;

use App\Models\DisasterReport;
use App\Repositories\DisasterReportRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class DisasterReportService
{
    public function __construct(
        private DisasterReportRepository $repository,
        private ActivityLogger $activityLogger
    ) {
    }

    public function paginate(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginateWithFilters($filters, $perPage);
    }

    public function dataset(array $filters): Collection
    {
        $filters['is_published'] = true;
        return $this->repository->getWithFilters($filters);
    }

    public function dashboardData(array $filters): array
    {
        $filters['is_published'] = true;
        $reports = $this->repository->getWithFilters($filters);

        return [
            'metrics' => $this->repository->aggregateForDashboard($filters),
            'damageByCategory' => [
                'building' => (float) $reports->sum('damage_building'),
                'equipment' => (float) $reports->sum('damage_equipment'),
                'material' => (float) $reports->sum('damage_material'),
            ],
            'disasterTypeTotals' => $reports->groupBy('disaster_type')->map(fn ($items) => $items->count()),
            'timeline' => $reports->groupBy(fn ($item) => optional($item->reported_at)->format('Y-m-d'))
                ->map(fn ($items) => $items->count())
                ->sortKeys(),
            'statusBreakdown' => $reports->groupBy('current_status')->map(fn ($items) => $items->count()),
            'teachingStatus' => $reports->groupBy('teaching_status')->map(fn ($items) => $items->count()),
            'humanImpact' => [
                'students' => [
                    'injured' => (int) $reports->sum('injured_students'),
                    'dead' => (int) $reports->sum('dead_students'),
                ],
                'staff' => [
                    'injured' => (int) $reports->sum('injured_staff'),
                    'dead' => (int) $reports->sum('dead_staff'),
                ],
            ],
            'damageByDistrict' => $reports->groupBy(fn($r) => $r->district->name ?? 'ไม่ระบุ')
                ->map(fn ($items) => $items->sum('damage_total_request'))
                ->sortDesc()
                ->take(10),
            'reportsByAffiliation' => $reports->groupBy(fn($r) => $r->affiliation->name ?? 'ไม่ระบุ')
                ->map(fn ($items) => $items->count())
                ->sortDesc()
                ->take(10),
            'mapPoints' => $reports
                ->filter(fn ($item) => $item->latitude && $item->longitude)
                ->map(fn ($item) => [
                    'organization' => $item->organization_name,
                    'status' => $item->current_status,
                    'damage' => (float) $item->damage_total_request,
                    'lat' => (float) $item->latitude,
                    'lng' => (float) $item->longitude,
                ])->values(),
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
}
