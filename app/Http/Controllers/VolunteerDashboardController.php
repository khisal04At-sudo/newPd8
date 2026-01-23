<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\SavedOpportunity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VolunteerDashboardController extends Controller
{
    /**
     * Display volunteer dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get statistics
        $stats = [
            'total_applications' => Application::where('user_id', $user->id)->count(),
            'pending' => Application::where('user_id', $user->id)->where('status', 'pending')->count(),
            'accepted' => Application::where('user_id', $user->id)->where('status', 'accepted')->count(),
            'rejected' => Application::where('user_id', $user->id)->where('status', 'rejected')->count(),
            'saved_count' => SavedOpportunity::where('user_id', $user->id)->count(),
        ];
        
        // Get recent applications
        $recentApplications = Application::where('user_id', $user->id)
            ->with(['opportunity', 'opportunity.organization'])
            ->latest()
            ->take(5)
            ->get();
        
        // Get recent saved opportunities
        $recentSaved = SavedOpportunity::where('user_id', $user->id)
            ->with(['opportunity', 'opportunity.organization', 'opportunity.city'])
            ->latest()
            ->take(3)
            ->get();
        
        return view('dashboard.volunteer.index', compact('stats', 'recentApplications', 'recentSaved'));
    }

    /**
     * Display all applications
     */
    public function applications(Request $request)
    {
        $user = Auth::user();
        
        $query = Application::where('user_id', $user->id)
            ->with(['opportunity', 'opportunity.organization', 'opportunity.city']);
        
        // Filter by status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        // Filter by type
        if ($request->has('type') && $request->type != 'all') {
            $query->whereHas('opportunity', function($q) use ($request) {
                $q->where('type', $request->type);
            });
        }
        
        $applications = $query->latest()->paginate(12);
        
        return view('dashboard.volunteer.applications', compact('applications'));
    }

    /**
     * Display saved opportunities
     */
    public function saved()
    {
        $user = Auth::user();
        
        $savedOpportunities = SavedOpportunity::where('user_id', $user->id)
            ->with(['opportunity', 'opportunity.organization', 'opportunity.city'])
            ->latest()
            ->paginate(12);
        
        return view('dashboard.volunteer.saved', compact('savedOpportunities'));
    }

    /**
     * Save an opportunity
     */
    public function saveOpportunity(Request $request, $opportunityId)
    {
        $user = Auth::user();
        
        SavedOpportunity::firstOrCreate([
            'user_id' => $user->id,
            'opportunity_id' => $opportunityId,
        ]);
        
        return back()->with('success', 'تم حفظ الفرصة بنجاح');
    }

    /**
     * Unsave an opportunity
     */
    public function unsaveOpportunity($opportunityId)
    {
        $user = Auth::user();
        
        SavedOpportunity::where('user_id', $user->id)
            ->where('opportunity_id', $opportunityId)
            ->delete();
        
        return back()->with('success', 'تم إلغاء حفظ الفرصة');
    }
}
