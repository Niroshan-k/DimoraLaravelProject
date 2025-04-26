<?php

use Illuminate\Support\Facades\Route;

use App\http\Controllers\HouseController;
use App\Http\Controllers\PropertyController; 
use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishListItemController;


Route::apiResource('properties', PropertyController::class);
Route::apiResource('houses', HouseController::class);
Route::apiResource('advertisements', AdvertisementController::class);
Route::apiResource('notifications', NotificationController::class);
Route::apiResource('users', UserController::class)->only(['index', 'show']);
Route::apiResource('wishlists', WishListItemController::class);


?>