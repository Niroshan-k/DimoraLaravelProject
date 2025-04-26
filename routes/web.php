<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('test', function (){
    $property = App\Models\Property::find(8);
    return $add->advertisement;
});

Route::get('user-advertisements/{userId}', function ($userId) {
    $user = App\Models\User::find($userId);

    if (!$user) {
        return "User not found.";
    }

    return $user->advertisements; // Returns all advertisements created by the user
});

//user_id	notification_id	is_read	created_at	updated_at
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
