<?php

namespace App\Repositories;

use App\Models\DisasterReport;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class DisasterReportRepository
{
    /**
     * Base query with eager loading.
     */
    public function baseQuery(): Builder
    {
        return DisasterReport::query()->with(['district', 'affiliation']);
    }

    /**
     * Paginate disaster reports with optional filters.
     */
    public function paginateWithFilters(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->applyFilters($this->baseQuery(), $filters)
            ->orderByDesc('reported_at')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Retrieve a filtered collection.
     */
    public function getWithFilters(array $filters): Collection
    {
        return $this->applyFilters($this->baseQuery(), $filters)
            ->orderByDesc('reported_at')
            ->get();
    }

    /**
     * Store a new report.
     */
    public function create(array $data): DisasterReport
    {
        return DisasterReport::create($data);
    }

    /**
     * Update the given report.
     */
    public function update(DisasterReport $report, array $data): DisasterReport
    {
        $report->update($data);

        return $report->refresh();
    }

    /**
     * Delete the given report.
     */
    public function delete(DisasterReport $report): void
    {
        $report->delete();
    }

    /**
     * Run aggregate queries for dashboard metrics.
     */
    public function aggregateForDashboard(array $filters): array
    {
        $query = $this->applyFilters(DisasterReport::query(), $filters);

        $collection = (clone $query)->get([
            'organization_name',
            'affected_students',
            'injured_students',
            'dead_students',
            'affected_staff',
            'injured_staff',
            'dead_staff',
            'damage_building',
            'damage_equipment',
            'damage_material',
            'damage_total_request',
        ]);

        return [
            'affected_units' => $collection->pluck('organization_name')->unique()->count(),
            'total_students_affected' => (int) $collection->sum('affected_students'),
            'total_staff_affected' => (int) $collection->sum('affected_staff'),
            'total_damage' => (float) $collection->sum('damage_total_request'),
        ];
    }

    /**
     * Apply filter constraints.
     */
    private function applyFilters(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['organization_name'] ?? null, fn (Builder $builder, string $value) => $builder->where('organization_name', 'like', "%{$value}%"))
            ->when($filters['district_id'] ?? null, fn (Builder $builder, $value) => $builder->where('district_id', $value))
            ->when($filters['affiliation_id'] ?? null, fn (Builder $builder, $value) => $builder->where('affiliation_id', $value))
            ->when($filters['disaster_type'] ?? null, fn (Builder $builder, string $value) => $builder->where('disaster_type', $value))
            ->when($filters['current_status'] ?? null, fn (Builder $builder, string $value) => $builder->where('current_status', $value))
            ->when($filters['teaching_status'] ?? null, fn (Builder $builder, string $value) => $builder->where('teaching_status', $value))
            ->when($filters['reported_from'] ?? null, fn (Builder $builder, string $value) => $builder->whereDate('reported_at', '>=', $value))
            ->when($filters['reported_to'] ?? null, fn (Builder $builder, string $value) => $builder->whereDate('reported_at', '<=', $value))
            ->when(isset($filters['is_published']), fn (Builder $builder) => $builder->where('is_published', $filters['is_published']));
    }
}
