<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKitchenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasAnyRole(['admin', 'data-entry', 'yfis']) ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'district_id' => 'required|exists:districts,id',
            'affiliation_id' => 'required|exists:affiliations,id',
            'status' => 'required|in:open,closed',
            'contact_name' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'facilities' => 'nullable|array',
            'facilities.*' => 'boolean',
            'notes' => 'nullable|string',
            // Production quantity fields
            'water_bottles' => 'required|integer|min:0',
            'food_boxes' => 'required|integer|min:0',
        ];
    }
}
