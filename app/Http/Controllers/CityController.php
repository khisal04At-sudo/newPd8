<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;

class CityController extends Controller
{
    // create a method to add a new city
    public function addCity(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
        ]);

        $city = new City();
        $city->name = $request->name;
        $city->save();

        return response()->json(['message' => 'City added successfully', 'city' => $city], 201);
    }
}
