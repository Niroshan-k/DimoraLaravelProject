<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use App\Http\Resources\ImageResource;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'advertisement_id' => 'required|exists:advertisements,id',
            'images' => 'required|array|max:4',
            'images.*' => 'required|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        $paths = [];
        foreach ($validated['images'] as $image) {
            $filePath = $image->store('images', 'public');
            $img = Image::create([
                'data' => $filePath,
                'advertisement_id' => $validated['advertisement_id'],
            ]);
            $paths[] = $img;
        }

        return response()->json([
            'message' => 'Images uploaded.',
            'images' => $paths,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        $image = Image::findOrFail($id);
        if (\Storage::disk('public')->exists($image->data)) {
            \Storage::disk('public')->delete($image->data);
        }
        $image->delete();
        return response()->json(['message' => 'Image deleted']);
    }
}
