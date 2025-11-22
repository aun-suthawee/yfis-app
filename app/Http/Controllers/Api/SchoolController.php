<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    /**
     * Search schools for autocomplete.
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');
        
        if (empty($query)) {
            return response()->json([]);
        }

        $schools = School::with(['affiliation'])
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('code', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get()
            ->map(function ($school) {
                return [
                    'id' => $school->id,
                    'code' => $school->code,
                    'name' => $school->name,
                    'province' => $school->province,
                    'district' => $school->district,
                    'affiliation_id' => $school->affiliation_id,
                    'affiliation_name' => $school->affiliation?->name,
                    'latitude' => $school->latitude,
                    'longitude' => $school->longitude,
                    'display' => $school->name . ' (' . $school->code . ')',
                ];
            });

        return response()->json($schools);
    }
}
