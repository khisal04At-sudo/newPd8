<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Opportunity;
use App\Models\Organization;
use App\Models\Certificate;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the welcome page with real-time statistics and opportunities.
     */
    public function index()
    {
        // Fetch real-time statistics
        $stats = [
            'opportunities_count' => Opportunity::count(),
            'partners_count' => Organization::count(),
            'certificates_count' => Certificate::count(),
            'organizations_count' => Organization::count(), // Same as partners but for institutional view
            'total_hours' => User::sum('volunteer_hours'),
        ];

        // Fetch latest active opportunities for the carousel
        // Assuming 'status' exists or just pulling latest
        $latestOpportunities = Opportunity::with('organization')
            ->latest()
            ->take(10)
            ->get();

        return view('welcome', compact('stats', 'latestOpportunities'));
    }
}
