<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\House;
use App\Http\Resources\HouseResource;

class HouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $houses = House::paginate(10);
        return HouseResource::collection($houses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'bedroom' => 'required|integer|min:0',
            'bathroom' => 'required|integer|min:0',
            'pool' => 'required|boolean',
            'area' => 'required|numeric',
            'parking' => 'required|boolean',
            'house_type' => 'required|in:modern,traditional,luxury', // <-- add this line
        ]);

        $house = House::create($validated);

        return response()->json([
            'message' => 'House created.',
            'house_id' => $house->id,
            'house' => $house,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $house = House::findOrFail($id);
        return new HouseResource($house);
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
