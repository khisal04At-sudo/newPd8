<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the dashboard home.
     */
    public function index()
    {
        $user = auth()->user();
        $stats = [];

        if ($user->user_type === 'organization') {
            $org = $user->organization;
            $stats = [
                'opportunities_count' => \App\Models\Opportunity::where('organization_id', $org->id)->count(),
                'active_opportunities' => \App\Models\Opportunity::where('organization_id', $org->id)->where('status', 1)->count(),
                'total_applicants' => \App\Models\Application::whereHas('opportunity', function($q) use ($org) {
                    $q->where('organization_id', $org->id);
                })->count(),
                'total_hours_provided' => \App\Models\Opportunity::where('organization_id', $org->id)
                    ->where('status', 9) // Assuming 9 is closed/completed
                    ->sum('total_hours'),
            ];
        }

        return view('dashboard.index', compact('stats'));
    }
}
