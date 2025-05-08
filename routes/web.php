<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware as RoleMiddleware;

// Home route
Route::get('/', function () {
    return view('index');
});

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
        return view('dashboard');
    })->name('dashboard')->middleware(['auth', RoleMiddleware::class . ':seller']);
    
    // Index route
    Route::get('/index', function () {
        return view('index');
    })->name('index')->middleware(['auth']);
});

use App\Http\Controllers\AdvertisementController;

Route::get('/index', [AdvertisementController::class, 'index'])->name('advertisements.index');
Route::get('/advertisement/{id}', [AdvertisementController::class, 'show'])->name('advertisement.show');
Route::get('/', [AdvertisementController::class, 'index'])->name('advertisements.index');