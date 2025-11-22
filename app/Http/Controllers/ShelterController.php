<?php

namespace App\Http\Controllers;

use App\Models\Shelter;
use Illuminate\Http\Request;

class ShelterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,data-entry,yfis')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Shelter::with(['district', 'affiliation'])->latest();
        
        // Filter by affiliation for YFIS users
        $user = auth()->user();
        if ($user && $user->role === 'yfis' && $user->affiliation_id) {
            $query->where('affiliation_id', $user->affiliation_id);
        }
        
        $shelters = $query->paginate(10);
        return view('shelters.index', compact('shelters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $districts = \App\Models\District::all();
        $affiliations = \App\Models\Affiliation::all();
        return view('shelters.create', compact('districts', 'affiliations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'district_id' => 'required|exists:districts,id',
            'affiliation_id' => 'required|exists:affiliations,id',
            'status' => 'required|in:open,closed',
            'capacity' => 'required|integer|min:0',
            'current_occupancy' => 'required|integer|min:0',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'contact_name' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
        ]);

        $data = $request->all();
        $data['is_kitchen'] = $request->has('is_kitchen');

        Shelter::create($data);

        return redirect()->route('shelters.index')->with('status', 'เพิ่มข้อมูลศูนย์พักพิงเรียบร้อยแล้ว');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shelter $shelter)
    {
        $this->authorize('update', $shelter);
        
        $districts = \App\Models\District::all();
        $affiliations = \App\Models\Affiliation::all();
        return view('shelters.edit', compact('shelter', 'districts', 'affiliations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shelter $shelter)
    {
        $this->authorize('update', $shelter);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'district_id' => 'required|exists:districts,id',
            'affiliation_id' => 'required|exists:affiliations,id',
            'status' => 'required|in:open,closed',
            'capacity' => 'required|integer|min:0',
            'current_occupancy' => 'required|integer|min:0',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'contact_name' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
        ]);

        $data = $request->all();
        $data['is_kitchen'] = $request->has('is_kitchen');

        $shelter->update($data);

        return redirect()->route('shelters.index')->with('status', 'แก้ไขข้อมูลศูนย์พักพิงเรียบร้อยแล้ว');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shelter $shelter)
    {
        $this->authorize('delete', $shelter);
        
        $shelter->delete();
        return redirect()->route('shelters.index')->with('status', 'ลบข้อมูลศูนย์พักพิงเรียบร้อยแล้ว');
    }
}
