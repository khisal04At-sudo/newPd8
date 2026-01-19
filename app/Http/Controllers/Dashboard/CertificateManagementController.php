<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Opportunity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CertificateManagementController extends Controller
{
    public function index()
    {
        $organizationId = Auth::user()->organization->id;
        $certificates = Certificate::whereHas('opportunity', function($query) use ($organizationId) {
            $query->where('organization_id', $organizationId);
        })->with(['user', 'opportunity'])->latest()->paginate(20);

        return view('dashboard.organization.certificates.index', compact('certificates'));
    }

    public function issue(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'opportunity_id' => 'required|exists:opportunities,id',
            'title' => 'required|string',
            'issue_date' => 'required|date',
        ]);

        $opportunity = Opportunity::findOrFail($request->opportunity_id);
        if ($opportunity->organization_id !== Auth::user()->organization->id) {
            abort(403);
        }

        Certificate::create([
            'user_id' => $request->user_id,
            'opportunity_id' => $request->opportunity_id,
            'title' => $request->title,
            'issue_date' => $request->issue_date,
            'certificate_number' => 'CERT-' . strtoupper(uniqid()),
        ]);

        return back()->with('success', 'تم إصدار الشهادة بنجاح.');
    }
}
