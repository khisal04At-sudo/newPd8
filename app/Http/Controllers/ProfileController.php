<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Organization;
use App\Models\Application;
use App\Models\Opportunity;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Show user profile
     */
    public function showUser($userId)
    {
        $user = User::with(['city', 'certificates.file', 'skills', 'files'])->findOrFail($userId);
        
        // Get user statistics
        $stats = [
            'total_hours' => Application::where('user_id', $userId)
                ->where('applications.status', 'accepted')
                ->join('opportunities', 'applications.opportunity_id', '=', 'opportunities.id')
                ->sum('opportunities.total_hours'),
            'certificates_count' => \DB::table('user_achievements')
                ->where('user_id', $userId)
                ->count(),
            'accepted_applications' => Application::where('user_id', $userId)
                ->where('applications.status', 'accepted')
                ->count(),
            'total_applications' => Application::where('user_id', $userId)->count(),
        ];
        
        // Get recent achievements
        $achievements = \DB::table('user_achievements')
            ->join('achievements', 'user_achievements.achievement_id', '=', 'achievements.id')
            ->where('user_achievements.user_id', $userId)
            ->select('achievements.*', 'user_achievements.earned_at')
            ->latest('user_achievements.earned_at')
            ->take(6)
            ->get();
        
        // Get recent accepted applications
        $recentActivity = Application::where('user_id', $userId)
            ->where('applications.status', 'accepted')
            ->with(['opportunity', 'opportunity.organization'])
            ->latest()
            ->take(5)
            ->get();
        
        return view('profiles.user', compact('user', 'stats', 'achievements', 'recentActivity'));
    }

    /**
     * Show organization profile
     */
    public function showOrganization($orgId)
    {
        $organization = Organization::with(['city', 'user'])->findOrFail($orgId);
        
        // Get organization statistics
        $stats = [
            'total_opportunities' => Opportunity::where('organization_id', $orgId)
                ->where('status', 1) // published
                ->count(),
            'total_volunteers' => Application::whereHas('opportunity', function($q) use ($orgId) {
                    $q->where('organization_id', $orgId);
                })
                ->where('status', 'accepted')
                ->distinct('user_id')
                ->count('user_id'),
            'total_hours' => Opportunity::where('organization_id', $orgId)
                ->where('status', 1)
                ->sum('total_hours'),
        ];
        
        // Get active opportunities
        $activeOpportunities = Opportunity::where('organization_id', $orgId)
            ->where('status', 1)
            ->where('application_deadline', '>=', now())
            ->with(['city'])
            ->latest()
            ->take(6)
            ->get();
        
        // Get past/completed opportunities
        $pastOpportunities = Opportunity::where('organization_id', $orgId)
            ->where('status', 1)
            ->where('end_date', '<', now())
            ->with(['city'])
            ->latest('end_date')
            ->take(6)
            ->get();
        
        return view('profiles.organization', compact('organization', 'stats', 'activeOpportunities', 'pastOpportunities'));
    }
}
