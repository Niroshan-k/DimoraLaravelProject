<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WishlistItem;
use App\Models\Advertisement;
use Illuminate\Support\Facades\Auth;

class WishListItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id ?? Auth::id();
        $wishlistAds = WishlistItem::where('user_id', $userId)->pluck('advertisement_id')->toArray();
        $advertisements = Advertisement::whereIn('id', $wishlistAds)->get();

        return view('wishlist.index', [
            'advertisements' => $advertisements,
            'wishlistedIds' => $wishlistAds
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'advertisement_id' => 'required|exists:advertisements,id',
        ]);

        WishlistItem::firstOrCreate([
            'user_id' => $request->user()->id, // Use $request->user() for API
            'advertisement_id' => $request->advertisement_id,
        ]);

        return response()->json(['message' => 'Added to wishlist!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        WishlistItem::where('user_id', $request->user()->id)
            ->where('advertisement_id', $id)
            ->delete();

        return response()->json(['message' => 'Removed from wishlist!']);
    }
}
