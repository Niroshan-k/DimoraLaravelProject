<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use App\Http\Resources\PropertyResource;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $property = Property::with('house')->paginate(10);
        return PropertyResource::collection($property);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'advertisement_id' => 'required|exists:advertisements,id',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric',
            'type' => 'required|string',
        ]);

        $property = Property::create($validated);

        return response()->json([
            'message' => 'Property created.',
            'property_id' => $property->id,
            'property' => $property,
        ], 201);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $property = Property::with('house')->findOrFail($id);
        return new PropertyResource($property);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
