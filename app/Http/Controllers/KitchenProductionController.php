<?php

namespace App\Http\Controllers;

use App\Models\Kitchen;
use App\Models\KitchenProduction;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class KitchenProductionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,data-entry,yfis');
    }

    /**
     * Store a new production record
     */
    public function store(Request $request, Kitchen $kitchen): RedirectResponse
    {
        $validated = $request->validate([
            'production_date' => ['required', 'date', 'before_or_equal:today'],
            'water_bottles' => ['required', 'integer', 'min:0'],
            'food_boxes' => ['required', 'integer', 'min:0'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        // Check for duplicate date
        $exists = $kitchen->productions()
            ->where('production_date', $validated['production_date'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['production_date' => 'มีข้อมูลการผลิตวันที่นี้อยู่แล้ว กรุณาใช้ฟังก์ชันแก้ไขแทน']);
        }

        $kitchen->productions()->create($validated);

        return back()->with('status', 'บันทึกข้อมูลการผลิตเรียบร้อยแล้ว');
    }

    /**
     * Update an existing production record
     */
    public function update(Request $request, Kitchen $kitchen, KitchenProduction $production): RedirectResponse
    {
        // Ensure production belongs to this kitchen
        if ($production->kitchen_id !== $kitchen->id) {
            abort(404);
        }

        $validated = $request->validate([
            'production_date' => ['required', 'date', 'before_or_equal:today'],
            'water_bottles' => ['required', 'integer', 'min:0'],
            'food_boxes' => ['required', 'integer', 'min:0'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        // Check for duplicate date (excluding current record)
        $exists = $kitchen->productions()
            ->where('production_date', $validated['production_date'])
            ->where('id', '!=', $production->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['production_date' => 'มีข้อมูลการผลิตวันที่นี้อยู่แล้ว']);
        }

        $production->update($validated);

        return back()->with('status', 'ปรับปรุงข้อมูลการผลิตเรียบร้อยแล้ว');
    }

    /**
     * Delete a production record
     */
    public function destroy(Kitchen $kitchen, KitchenProduction $production): RedirectResponse
    {
        // Ensure production belongs to this kitchen
        if ($production->kitchen_id !== $kitchen->id) {
            abort(404);
        }

        $production->delete();

        return back()->with('status', 'ลบข้อมูลการผลิตเรียบร้อยแล้ว');
    }
}
