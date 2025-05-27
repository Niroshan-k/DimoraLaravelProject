<?php

use Illuminate\Support\Facades\Route;

use App\http\Controllers\HouseController;
use App\Http\Controllers\PropertyController; 
use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishListItemController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ImageController;


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('property', PropertyController::class);
    Route::apiResource('house', HouseController::class);
    Route::apiResource('advertisement', AdvertisementController::class);
    Route::apiResource('notification', NotificationController::class);
    Route::apiResource('users', UserController::class);
    Route::apiResource('wishlist', WishListItemController::class);
    Route::post('image', [ImageController::class, 'store'])->name('images.store');
    Route::delete('image/{id}', [ImageController::class, 'destroy'])->name('images.destroy');
});

?>