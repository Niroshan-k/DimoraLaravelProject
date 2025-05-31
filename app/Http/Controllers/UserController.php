<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Resources\AdvertisementResource;
use Illuminate\Support\Facades\Auth;
use App\Models\Blog;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        // Eager load advertisements with property, house, and images
        $advertisements = $user
            ? $user->advertisements()->with('property.house', 'images')->get()
            : collect();

        // Get blogs for this seller (from MongoDB)
        $blogs = Blog::where('seller_id', $user->id)->orderBy('created_at', 'desc')->get();

        return view('dashboard', compact('advertisements', 'blogs'));
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
        $user = User::with('advertisements.property.house')->findOrFail($id);
        return new UserResource($user);
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
