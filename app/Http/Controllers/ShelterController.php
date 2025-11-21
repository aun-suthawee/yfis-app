<?php

namespace App\Http\Controllers;

use App\Models\Shelter;
use Illuminate\Http\Request;

class ShelterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shelters = Shelter::latest()->paginate(10);
        return view('shelters.index', compact('shelters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('shelters.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'capacity' => 'required|integer|min:0',
        ]);

        Shelter::create($request->all());

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
        return view('shelters.edit', compact('shelter'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shelter $shelter)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'capacity' => 'required|integer|min:0',
        ]);

        $shelter->update($request->all());

        return redirect()->route('shelters.index')->with('status', 'แก้ไขข้อมูลศูนย์พักพิงเรียบร้อยแล้ว');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shelter $shelter)
    {
        $shelter->delete();
        return redirect()->route('shelters.index')->with('status', 'ลบข้อมูลศูนย์พักพิงเรียบร้อยแล้ว');
    }
}
