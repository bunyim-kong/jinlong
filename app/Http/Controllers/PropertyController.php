<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Unit;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $properties = Property::with('units')->orderBy('created_at', 'desc')->paginate(9);
        
        $totalProperties = Property::count();
        
        // Remove the sum query that's causing the error
        // Just set to 0 or calculate from units instead
        $totalUnits = Unit::count(); // Count from units table instead
        
        $occupiedUnits = Unit::where('status', 'rented')->count();
        
        return view('pages.properties', compact('properties', 'totalProperties', 'totalUnits', 'occupiedUnits'));
    }

    public function create()
    {
        return view('pages.properties-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'address' => 'required|string',
            'total_unit' => 'required|integer|min:1',
        ]);

        Property::create([
            'name' => $request->name,
            'type' => $request->type,
            'address' => $request->address,
            'total_unit' => $request->total_unit,
        ]);
        
        return redirect()->route('properties.index')->with('success', 'Property created successfully');
    }

    public function show($id)
    {
        $property = Property::with('units')->findOrFail($id);
        return view('pages.properties-show', compact('property'));
    }

    public function edit($id)
    {
        $property = Property::findOrFail($id);
        return view('pages.properties-edit', compact('property'));
    }

    public function update(Request $request, $id)
    {
        $property = Property::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'address' => 'required|string',
            'total_unit' => 'required|integer|min:1',
        ]);
        
        $property->update([
            'name' => $request->name,
            'type' => $request->type,
            'address' => $request->address,
            'total_unit' => $request->total_unit,
        ]);
        
        return redirect()->route('properties.index')->with('success', 'Property updated successfully');
    }

    public function destroy($id)
    {
        $property = Property::findOrFail($id);
        $property->delete();
        return response()->json(['success' => true]);
    }
}