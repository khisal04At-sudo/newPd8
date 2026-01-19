<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\City;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::post('/cities', [\App\Http\Controllers\CityController::class, 'addCity']);

Route::post('/cities', function (Request $request) {
    $request->validate([
        'cities' => 'required|array',
        'cities.*' => 'required|string|max:255'
    ]);

    foreach ($request->cities as $city) {
        City::create(['name' => $city]);
    }

    return response()->json(['message' => 'Cities added successfully']);
});
