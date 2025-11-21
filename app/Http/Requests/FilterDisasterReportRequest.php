<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilterDisasterReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'organization_name' => ['nullable', 'string', 'max:255'],
            'district_id' => ['nullable', 'integer', 'exists:districts,id'],
            'affiliation_id' => ['nullable', 'integer', 'exists:affiliations,id'],
            'disaster_type' => ['nullable', 'string', 'max:255'],
            'current_status' => ['nullable', 'string', 'max:255'],
            'teaching_status' => ['nullable', 'in:open,closed'],
            'is_published' => ['nullable', 'boolean'],
            'reported_from' => ['nullable', 'date'],
            'reported_to' => ['nullable', 'date', 'after_or_equal:reported_from'],
        ];
    }

    public function filters(): array
    {
        return array_filter($this->validated(), fn ($value) => $value !== null && $value !== '');
    }
}
