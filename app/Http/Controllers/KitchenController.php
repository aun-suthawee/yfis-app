<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKitchenRequest;
use App\Http\Requests\UpdateKitchenRequest;
use App\Models\Kitchen;
use App\Models\District;
use App\Models\Affiliation;
use Illuminate\Http\Request;

class KitchenController extends Controller
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
        $query = Kitchen::with(['district', 'affiliation'])->latest();
        
        // Filter by affiliation for YFIS users
        $user = auth()->user();
        if ($user && $user->role === 'yfis' && $user->affiliation_id) {
            $query->where('affiliation_id', $user->affiliation_id);
        }
        
        $kitchens = $query->paginate(10);
        return view('kitchens.index', compact('kitchens'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $districts = District::orderBy('name')->get();
        $affiliations = Affiliation::orderBy('name')->get();
        return view('kitchens.create', compact('districts', 'affiliations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKitchenRequest $request)
    {
        $data = $request->validated();
        
        // Handle facilities checkboxes
        $data['facilities'] = [
            'water' => $request->has('facilities.water'),
            'food' => $request->has('facilities.food'),
            'restroom' => $request->has('facilities.restroom'),
        ];

        Kitchen::create($data);

        return redirect()->route('kitchens.index')->with('status', 'เพิ่มข้อมูลโรงครัวเรียบร้อยแล้ว');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kitchen $kitchen)
    {
        $kitchen->load(['district', 'affiliation']);
        return view('kitchens.show', compact('kitchen'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kitchen $kitchen)
    {
        $this->authorize('update', $kitchen);
        
        $districts = District::orderBy('name')->get();
        $affiliations = Affiliation::orderBy('name')->get();
        return view('kitchens.edit', compact('kitchen', 'districts', 'affiliations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKitchenRequest $request, Kitchen $kitchen)
    {
        $this->authorize('update', $kitchen);
        
        $data = $request->validated();
        
        // Handle facilities checkboxes
        $data['facilities'] = [
            'water' => $request->has('facilities.water'),
            'food' => $request->has('facilities.food'),
            'restroom' => $request->has('facilities.restroom'),
        ];

        $kitchen->update($data);

        return redirect()->route('kitchens.index')->with('status', 'แก้ไขข้อมูลโรงครัวเรียบร้อยแล้ว');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kitchen $kitchen)
    {
        $this->authorize('delete', $kitchen);
        
        $kitchen->delete();
        return redirect()->route('kitchens.index')->with('status', 'ลบข้อมูลโรงครัวเรียบร้อยแล้ว');
    }
}
