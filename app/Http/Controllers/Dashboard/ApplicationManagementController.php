<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationManagementController extends Controller
{
    public function index(Request $request)
    {
        $organizationId = Auth::user()->organization->id;
        
        $query = Application::whereHas('opportunity', function($q) use ($organizationId) {
            $q->where('organization_id', $organizationId);
        })->with(['user', 'opportunity']);
        
        // تصفية حسب الفرصة إذا تم تحديدها
        if ($request->has('opportunity_id') && $request->input('opportunity_id') != '') {
            $opportunityId = $request->input('opportunity_id');
            $query->where('opportunity_id', $opportunityId);
            
            // جلب معلومات الفرصة للعرض
            $opportunity = \App\Models\Opportunity::where('id', $opportunityId)
                ->where('organization_id', $organizationId)
                ->first();
                
            if (!$opportunity) {
                abort(404, 'الفرصة غير موجودة');
            }
        } else {
            $opportunity = null;
        }
        
        $applications = $query->latest()->paginate(20);
        
        // جلب جميع فرص المؤسسة للفلتر
        $opportunities = \App\Models\Opportunity::where('organization_id', $organizationId)
            ->withCount('applications')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.organization.applications.index', compact('applications', 'opportunity', 'opportunities'));
    }

    public function updateStatus(Request $request, Application $application)
    {
        if ($application->opportunity->organization_id !== Auth::user()->organization->id) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,accepted,rejected'
        ]);

        $application->update([
            'status' => $request->status,
            'decision_by' => Auth::id(),
            'decision_at' => now()
        ]);

        // Logic to notify user could be added here

        return back()->with('success', 'تم تحديث حالة الطلب بنجاح.');
    }
    public function updateTracking(Request $request, Application $application)
    {
        if ($application->opportunity->organization_id !== Auth::user()->organization->id) {
            abort(403);
        }

        $request->validate([
            'attended_hours' => 'required|integer|min:0',
            'commitment_score' => 'nullable|integer|min:1|max:5',
            'evaluation_notes' => 'nullable|string|max:1000',
        ]);

        $application->update([
            'attended_hours' => $request->attended_hours,
            'commitment_score' => $request->commitment_score,
            'evaluation_notes' => $request->evaluation_notes,
        ]);

        return back()->with('success', 'تم تحديث بيانات التتبع بنجاح.');
    }
}
