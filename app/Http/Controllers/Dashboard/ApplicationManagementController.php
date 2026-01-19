<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationManagementController extends Controller
{
    public function index()
    {
        $organizationId = Auth::user()->organization->id;
        
        $applications = Application::whereHas('opportunity', function($query) use ($organizationId) {
            $query->where('organization_id', $organizationId);
        })->with(['user', 'opportunity'])->latest()->paginate(20);

        return view('dashboard.organization.applications.index', compact('applications'));
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
}
