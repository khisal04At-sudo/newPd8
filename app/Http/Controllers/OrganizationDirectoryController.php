<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\City;
use Illuminate\Http\Request;

class OrganizationDirectoryController extends Controller
{
    /**
     * Display a listing of verified organizations.
     */
    public function index(Request $request)
    {
        $query = Organization::where('verified', true)
            ->where('status', 'approved')
            ->with(['city', 'user'])
            ->withCount('opportunities');

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by City
        if ($request->filled('city_id')) {
            $query->where('city_id', $request->city_id);
        }

        // Filter by Sector/Type
        if ($request->filled('sector')) {
            $query->where('sector', $request->sector);
        }

        $organizations = $query->latest()->paginate(12)->withQueryString();
        $cities = City::all();
        
        // Get unique sectors for filtering
        $sectors = Organization::where('verified', true)
            ->whereNotNull('sector')
            ->distinct()
            ->pluck('sector');

        return view('organizations.index', compact('organizations', 'cities', 'sectors'));
    }
}
