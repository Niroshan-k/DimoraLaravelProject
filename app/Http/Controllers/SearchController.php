<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->input('q');

        $results = \App\Models\Advertisement::where(function($q1) use ($query) {
            $q1->where('title', 'like', "%{$query}%")
                ->orWhereHas('property', function($q2) use ($query) {
                    $q2->where('location', 'like', "%{$query}%");
                });
        })->get();

        return view('search.results', compact('results', 'query'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }
}
