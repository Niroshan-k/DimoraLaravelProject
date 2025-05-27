<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Advertisement;
use App\Http\Resources\AdvertisementResource;
use App\Models\Property;
use App\Models\House;
use App\Models\Image;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

class AdvertisementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Advertisement::with(['property.house', 'images']);

        $advertisementsLatest = (clone $query)
            ->orderBy('created_at', 'desc')
            ->paginate(8, ['*'], 'latest_page');

        $advertisementsLuxury = (clone $query)
            ->whereHas('property.house', function ($query) {
                $query->where('house_type', 'luxury');
            })
            ->paginate(8, ['*'], 'luxury_page');

        $advertisementsModern = (clone $query)
            ->whereHas('property.house', function ($query) {
                $query->where('house_type', 'modern');
            })
            ->paginate(8, ['*'], 'modern_page');

        $advertisementsTraditional = (clone $query)
            ->whereHas('property.house', function ($query) {
                $query->where('house_type', 'traditional');
            })
            ->paginate(8, ['*'], 'traditional_page');
        
        return view('/index', compact('advertisementsLatest', 'advertisementsLuxury', 'advertisementsModern', 'advertisementsTraditional'));
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            if ($request->expectsJson() || $request->ajax()) {
                // Custom validation for AJAX/JSON requests
                $validator = \Validator::make($request->all(), [
                    'title' => 'required|string|max:255',
                    'status' => 'required|string',
                    'description' => 'nullable|string',
                ]);

                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()], 422);
                }

                $validated = $validator->validated();
            } else {
                $validated = $request->validate([
                    'title' => 'required|string|max:255',
                    'status' => 'required|string',
                    'description' => 'nullable|string',
                ]);
            }

            $advertisement = Advertisement::create([
                ...$validated,
                'seller_id' => auth()->id(),
            ]);

            // --- Create notification in MongoDB ---
            $seller = auth()->user();
            try {
                DB::connection('mongodb')
                    ->selectCollection('notifications')
                    ->insertOne([
                        'advertisement_id' => $advertisement->id,
                        'seller_id' => $seller->id,
                        'seller_name' => $seller->name,
                        'message' => "New advertisement '{$advertisement->title}' created by {$seller->name}.",
                        'created_at' => now()->toDateTimeString(),
                    ]);
            } catch (\Exception $e) {
                \Log::error('MongoDB notification insert failed: ' . $e->getMessage());
            }
            // --- End notification creation ---

            return response()->json([
                'message' => 'Advertisement created.',
                'advertisement_id' => $advertisement->id,
                'advertisement' => $advertisement,
            ], 201);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Retrieve the advertisement by ID, including related property, house, and images
        $advertisement = Advertisement::with(['property.house', 'images'])->findOrFail($id);
        return view('master', compact('advertisement'));
    }

    /**
     * Update the specified resource in storage.
     */
    
    public function update(Request $request, string $id)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            // Advertisement fields
            'title' => 'nullable|string|max:255', // Optional
            'seller_id' => 'required|exists:users,id', // Ensure the seller exists
            'status' => 'required|string',
            'description' => 'nullable|string', // Optional

            // Property fields
            'location' => 'nullable|string|max:255', // Optional
            'price' => 'nullable|numeric', // Optional
            'type' => 'nullable|string', // Optional

            // House fields
            'bedroom' => 'nullable|integer|min:1', // Optional
            'bathroom' => 'nullable|integer|min:1', // Optional
            'pool' => 'nullable|boolean', // Optional
            'area' => 'nullable|numeric', // Optional
            'parking' => 'nullable|boolean', // Optional

            // Images
            'images' => 'nullable|array|max:4', // Allow up to 4 images
            'images.*' => 'nullable|file|mimes:jpg,jpeg,png|max:2048', // Each image must be a file of type jpg, jpeg, or png, max size 2MB
        ]);

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Step 1: Find the Advertisement
            $advertisement = Advertisement::findOrFail($id);

            // Step 2: Update the Advertisement
            $advertisement->update([
                'title' => $validatedData['title'] ?? $advertisement->title, // Preserve existing value if not provided
                'seller_id' => $validatedData['seller_id'],
                'status' => $validatedData['status'],
                'description' => $validatedData['description'] ?? $advertisement->description,
            ]);

            // Step 3: Find the Property associated with the Advertisement
            $property = $advertisement->property;
            if ($property) {
                // Step 4: Update the Property
                $property->update([
                    'location' => $validatedData['location'] ?? $property->location,
                    'price' => $validatedData['price'] ?? $property->price,
                    'type' => $validatedData['type'] ?? $property->type,
                ]);

                // Step 5: Find the House associated with the Property
                $house = $property->house;
                if ($house) {
                    // Step 6: Update the House
                    $house->update([
                        'bedroom' => $validatedData['bedroom'] ?? $house->bedroom,
                        'bathroom' => $validatedData['bathroom'] ?? $house->bathroom,
                        'pool' => $validatedData['pool'] ?? $house->pool,
                        'area' => $validatedData['area'] ?? $house->area,
                        'parking' => $validatedData['parking'] ?? $house->parking,
                    ]);
                }
            }

            // Step 7: Handle Image Updates
            if (isset($validatedData['images'])) {
                // Count current images
                $currentCount = $advertisement->images()->count();
                $maxImages = 4;
                $canAdd = $maxImages - $currentCount;

                $imagesToAdd = array_slice($validatedData['images'], 0, $canAdd);

                foreach ($imagesToAdd as $image) {
                    $filePath = $image->store('images', 'public');
                    Image::create([
                        'data' => $filePath,
                        'advertisement_id' => $advertisement->id,
                    ]);
                }
            }

            // Commit the transaction
            DB::commit();

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'message' => 'Advertisement, Property, House, and Images updated successfully.',
                    'advertisement' => $advertisement,
                    'property' => $property,
                    'house' => $house ?? null,
                ], 200);
            }

            // For normal form submissions, redirect to dashboard
            return redirect()->route('dashboard')->with('success', 'Advertisement updated successfully.');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Rollback the transaction if the Advertisement is not found
            DB::rollBack();
            return response()->json([
                'message' => 'Advertisement not found.',
                'error' => $e->getMessage(),
            ], 404);

        } catch (\Exception $e) {
            // Rollback the transaction in case of other errors
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to update Advertisement, Property, House, and Images.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $advertisement = Advertisement::with('images')->findOrFail($id);

        // Delete image files from storage
        foreach ($advertisement->images as $image) {
            if (\Storage::disk('public')->exists($image->data)) {
                \Storage::disk('public')->delete($image->data);
            }
        }

        // This will delete the advertisement and, if set up, all related records
        $advertisement->delete();

        return response()->json(['message' => 'Advertisement and related data deleted.'], 200);
    }

    public function create()
    {
        return view('advertisements.create');
    }

    public function edit(string $id)
    {
        $advertisement = Advertisement::with(['property.house', 'images'])->findOrFail($id);
        return view('advertisements.edit', compact('advertisement'));
    }

    public function notifications()
    {
        $user = auth()->user();

        // Fetch notifications for the current user (seller)
        $cursor = \Illuminate\Support\Facades\DB::connection('mongodb')
            ->selectCollection('notifications')
            ->find(['seller_id' => $user->id]);

        $notifications = [];
        foreach ($cursor as $doc) {
            $notifications[] = json_decode(json_encode($doc), true);
        }

        // Pass notifications to the view
        return view('advertisements.notification', compact('notifications'));
    }
}
