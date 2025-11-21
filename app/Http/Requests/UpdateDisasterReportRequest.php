<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDisasterReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasAnyRole(['admin', 'data-entry']) ?? false;
    }

    public function rules(): array
    {
        return [
            'reported_at' => ['required', 'date'],
            'disaster_type' => ['required', 'string', 'max:255'],
            'organization_name' => ['required', 'string', 'max:255'],
            'district_id' => ['required', 'integer', 'exists:districts,id'],
            'affiliation_id' => ['required', 'integer', 'exists:affiliations,id'],
            'current_status' => ['required', 'string', 'max:255'],
            'teaching_status' => ['required', 'in:open,closed'],
            'affected_students' => ['required', 'integer', 'min:0'],
            'injured_students' => ['required', 'integer', 'min:0'],
            'dead_students' => ['required', 'integer', 'min:0'],
            'dead_students_list' => ['nullable', 'string'],
            'affected_staff' => ['required', 'integer', 'min:0'],
            'injured_staff' => ['required', 'integer', 'min:0'],
            'dead_staff' => ['required', 'integer', 'min:0'],
            'dead_staff_list' => ['nullable', 'string'],
            'damage_building' => ['required', 'numeric', 'min:0'],
            'damage_equipment' => ['required', 'numeric', 'min:0'],
            'damage_material' => ['required', 'numeric', 'min:0'],
            'damage_total_request' => ['required', 'numeric', 'min:0'],
            'assistance_received' => ['nullable', 'string'],
            'contact_name' => ['required', 'string', 'max:255'],
            'contact_position' => ['required', 'string', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:32'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if (!$this->has('reported_at')) {
            // If reported_at is missing (e.g. removed from form), keep the existing one or default to now
            $report = $this->route('disaster_report');
            $this->merge(['reported_at' => $report ? $report->reported_at : now()]);
        }

        $this->merge([
            'damage_building' => $this->normalizeDecimal($this->input('damage_building')),
            'damage_equipment' => $this->normalizeDecimal($this->input('damage_equipment')),
            'damage_material' => $this->normalizeDecimal($this->input('damage_material')),
            'damage_total_request' => $this->normalizeDecimal($this->input('damage_total_request')),
        ]);
    }

    private function normalizeDecimal(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        return number_format((float) str_replace(',', '', (string) $value), 2, '.', '');
    }
}
