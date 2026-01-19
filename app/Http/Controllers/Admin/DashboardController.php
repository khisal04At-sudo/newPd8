<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Opportunity;
use App\Models\Organization;
use App\Models\Certificate;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $stats = [
            'total_users' => User::where('user_type', 'user')->count(),
            'total_organizations' => Organization::count(),
            'pending_organizations' => Organization::where('verified', false)->count(),
            'total_opportunities' => Opportunity::count(),
            'total_certificates' => Certificate::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
