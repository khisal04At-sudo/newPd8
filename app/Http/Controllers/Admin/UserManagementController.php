<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserManagementController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $query = User::with(['city', 'organization'])
            ->where('user_type', '!=', 'admin');

        // Filter by user type
        if ($request->filled('type')) {
            $query->where('user_type', $request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by city
        if ($request->filled('city_id')) {
            $query->where('city_id', $request->city_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(20);
        $cities = \App\Models\City::all();

        return view('admin.users.index', compact('users', 'cities'));
    }

    /**
     * Show user profile with admin controls
     */
    public function show($userId)
    {
        $user = User::with(['city', 'certificates.file', 'skills', 'files', 'applications', 'achievements'])
            ->findOrFail($userId);

        // Get user statistics
        $stats = [
            'total_hours' => \App\Models\Application::where('user_id', $userId)
                ->where('applications.status', 'accepted')
                ->join('opportunities', 'applications.opportunity_id', '=', 'opportunities.id')
                ->sum('opportunities.total_hours'),
            'certificates_count' => \DB::table('certificates')
                ->where('user_id', $userId)
                ->count(),
            'achievements_count' => \DB::table('user_achievements')
                ->where('user_id', $userId)
                ->count(),
            'accepted_applications' => \App\Models\Application::where('user_id', $userId)
                ->where('applications.status', 'accepted')
                ->count(),
            'total_applications' => \App\Models\Application::where('user_id', $userId)->count(),
        ];

        // Get recent achievements
        $achievements = \DB::table('user_achievements')
            ->join('achievements', 'user_achievements.achievement_id', '=', 'achievements.id')
            ->where('user_achievements.user_id', $userId)
            ->select('achievements.*', 'user_achievements.earned_at')
            ->latest('user_achievements.earned_at')
            ->take(6)
            ->get();

        // Get recent activity
        $recentActivity = \App\Models\Application::where('user_id', $userId)
            ->where('status', 'accepted')
            ->with(['opportunity', 'opportunity.organization'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.users.show', compact('user', 'stats', 'achievements', 'recentActivity'));
    }

    /**
     * Ban a user
     */
    public function ban($userId)
    {
        $user = User::findOrFail($userId);

        if ($user->user_type === 'admin') {
            return back()->with('error', 'لا يمكن حظر مستخدم من نوع admin');
        }

        $user->ban();

        return back()->with('success', 'تم حظر المستخدم بنجاح');
    }

    /**
     * Unban a user
     */
    public function unban($userId)
    {
        $user = User::findOrFail($userId);
        $user->unban();

        return back()->with('success', 'تم إلغاء حظر المستخدم بنجاح');
    }

    /**
     * Toggle user active status
     */
    public function toggleActive($userId)
    {
        $user = User::findOrFail($userId);

        if ($user->user_type === 'admin') {
            return back()->with('error', 'لا يمكن تعطيل مستخدم من نوع admin');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'تفعيل' : 'تعطيل';
        return back()->with('success', "تم {$status} الحساب بنجاح");
    }

    /**
     * Update user rating
     */
    public function updateRating(Request $request, $userId)
    {
        $request->validate([
            'rating' => 'required|numeric|min:1|max:5'
        ]);

        $user = User::findOrFail($userId);
        $user->update(['admin_rating' => $request->rating]);

        return back()->with('success', 'تم تحديث التقييم بنجاح');
    }
}
