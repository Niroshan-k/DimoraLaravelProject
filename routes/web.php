<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware as RoleMiddleware;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\HouseController;
use App\Http\Controllers\ImageController;
use App\Models\MongoInfo;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\WishListItemController;

// Test route for property advertisement
Route::get('test', function () {
    $property = App\Models\Property::find(8);

    if (!$property) {
        return "Property not found.";
    }

    return $property->advertisement; // Ensure 'advertisement' exists in your Property model
});

// User advertisements route
Route::get('user-advertisements/{userId}', function ($userId) {
    $user = App\Models\User::with('advertisements')->find($userId);

    if (!$user) {
        return "User not found.";
    }

    return $user->advertisements; // Returns all advertisements created by the user
});

// Protected routes
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Dashboard route for sellers only
    Route::get('/dashboard', function () {
        $user = Auth::user();
        $advertisements = $user
            ? $user->advertisements()->with('images')->get()
            : collect();

        $blogs = \App\Models\Blog::where('seller_id', $user->id)->orderBy('created_at', 'desc')->get();

        return view('dashboard', compact('advertisements', 'blogs'));
    })->name('dashboard')->middleware(['auth', RoleMiddleware::class . ':seller']);
    
    Route::get('/create', [AdvertisementController::class, 'create'])
        ->name('advertisement.create')
        ->middleware(['auth', RoleMiddleware::class . ':seller']);
    
    Route::get('/advertisement/{id}/edit', [AdvertisementController::class, 'edit'])
        ->name('advertisement.edit')
        ->middleware(['auth', RoleMiddleware::class . ':seller']);
    
    Route::middleware(['auth', RoleMiddleware::class . ':seller'])->group(function () {
        Route::get('/blogs/create', [BlogController::class, 'create'])->name('blogs.create');
        Route::post('/blogs', [BlogController::class, 'store'])->name('blogs.store');
        Route::get('/blogs/{id}/edit', [BlogController::class, 'edit'])->name('blogs.edit');
        Route::put('/blogs/{id}', [BlogController::class, 'update'])->name('blogs.update');
        Route::delete('/blogs/{id}', [BlogController::class, 'destroy'])->name('blogs.destroy');
    });
});

Route::get('/wishlist', [WishListItemController::class, 'index'])->middleware('auth')->name('userWishlist.index');

// Public routes
Route::get('/index', [AdvertisementController::class, 'index'])->name('index');
Route::get('/advertisement/{id}', [AdvertisementController::class, 'show'])->name('advertisement.show');
Route::get('/', [AdvertisementController::class, 'index'])->name('advertisements.index');
Route::resource('advertisements', AdvertisementController::class);
Route::get('/notifications', [\App\Http\Controllers\AdvertisementController::class, 'notifications'])
    ->middleware(['auth']);
Route::get('/search', [\App\Http\Controllers\SearchController::class, 'index'])->name('search');
Route::middleware(['auth'])->group(function () {
    Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
});


