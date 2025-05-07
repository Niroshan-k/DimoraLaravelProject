<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Advertisement;
use App\Http\Resources\AdvertisementResource;
use App\Models\Property;
use App\Models\House;
use App\Models\Image;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\DB;

class AdvertisementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get paginated advertisements with their properties, houses, and images
        $advertisements = Advertisement::with('property.house', 'images')->paginate(10);

        //Log::info('Advertisements:', $advertisements->toArray());

        // Return the view with the advertisements
        return view('index', compact('advertisements'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            // Advertisement fields
            'title' => 'required|string|max:255',
            'seller_id' => 'required|exists:users,id', // Ensure the seller exists
            'status' => 'required|string',
            'description' => 'nullable|string',

            // Property fields
            'location' => 'required|string|max:255',
            'price' => 'required|numeric',
            'type' => 'required|string', // e.g., 'house', 'apartment', etc.

            // House fields
            'bedroom' => 'required|integer|min:0',
            'bathroom' => 'required|integer|min:0',
            'pool' => 'required|boolean',
            'area' => 'required|numeric',
            'parking' => 'required|boolean',

            // Images
            'images' => 'required|array|size:4', // Ensure exactly 4 images are uploaded
            'images.*' => 'required|file|mimes:jpg,jpeg,png|max:2048', // Each image must be a file of type jpg, jpeg, or png, max size 2MB
        ]);

        // Start a database transaction
        DB::beginTransaction();

        try {
            // Step 1: Create the Advertisement
            $advertisement = Advertisement::create([
                'title' => $validatedData['title'],
                'seller_id' => $validatedData['seller_id'],
                'status' => $validatedData['status'],
                'description' => $validatedData['description'] ?? null,
            ]);

            // Step 2: Create the Property associated with the Advertisement
            $property = Property::create([
                'location' => $validatedData['location'],
                'price' => $validatedData['price'],
                'type' => $validatedData['type'],
                'advertisement_id' => $advertisement->id, // Link the property to the advertisement
            ]);

            // Step 3: Create the House associated with the Property
            House::create([
                'bedroom' => $validatedData['bedroom'],
                'bathroom' => $validatedData['bathroom'],
                'pool' => $validatedData['pool'],
                'area' => $validatedData['area'],
                'parking' => $validatedData['parking'],
                'property_id' => $property->id, // Link the house to the property
            ]);

            // Step 4: Save the Images Locally and Store Paths in the Database
            foreach ($validatedData['images'] as $image) {
                // Store the image in the 'public/images' directory
                $filePath = $image->store('images', 'public'); // Saves to 'storage/app/public/images'

                // Save the file path in the database
                Image::create([
                    'data' => $filePath, // Store the file path instead of Base64 or binary data
                    'advertisement_id' => $advertisement->id, // Link the image to the advertisement
                ]);
            }

            // Commit the transaction
            DB::commit();

            // Return a success response
            return response()->json([
                'message' => 'Advertisement, Property, House, and Images created successfully.',
                'advertisement' => $advertisement,
                'property' => $property,
            ], 201);

        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to create Advertisement, Property, House, and Images.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Retrieve the advertisement by ID, including related property, house, and images
        $advertisement = Advertisement::with(['property.house', 'images'])->findOrFail($id);

        // Return the advertisement details
        return response()->json([
            'advertisement' => new AdvertisementResource($advertisement),
        ]);
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
                // Delete existing images if new ones are provided
                $advertisement->images()->delete();

                // Save the new images
                foreach ($validatedData['images'] as $image) {
                    // Store the image in the 'public/images' directory
                    $filePath = $image->store('images', 'public'); // Saves to 'storage/app/public/images'

                    // Save the file path in the database
                    Image::create([
                        'data' => $filePath, // Store the file path instead of Base64 or binary data
                        'advertisement_id' => $advertisement->id, // Link the image to the advertisement
                    ]);
                }
            }

            // Commit the transaction
            DB::commit();

            // Return a success response
            return response()->json([
                'message' => 'Advertisement, Property, House, and Images updated successfully.',
                'advertisement' => $advertisement,
                'property' => $property,
                'house' => $house ?? null,
            ], 200);

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
        //
        return response()->json(['message' => 'not implemented yet.'], 200);
    }
}
