<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Services\CertificateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationManagementController extends Controller
{
    protected $certificateService;

    public function __construct(CertificateService $certificateService)
    {
        $this->certificateService = $certificateService;
    }

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

        // إذا تم قبول المتقدم، تحقق من اكتمال المقاعد لإغلاق الفرصة تلقائياً
        if ($request->status === 'accepted') {
            $opportunity = $application->opportunity;
            $acceptedCount = $opportunity->applications()->where('status', 'accepted')->count();
            
            if ($acceptedCount >= $opportunity->seats && $opportunity->status == \App\Models\Opportunity::STATUS_PUBLISHED) {
                $opportunity->update(['status' => \App\Models\Opportunity::STATUS_CLOSED]);
            }
        }

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
            'commitment_score' => 'nullable|integer|min:1|max:100',
            'evaluation_notes' => 'nullable|string|max:1000',
            'certificate_name' => 'nullable|string|max:255',
        ]);

        $updateData = [
            'attended_hours' => $request->attended_hours,
            'commitment_score' => $request->commitment_score,
            'evaluation_notes' => $request->evaluation_notes,
            'certificate_name' => $request->certificate_name,
        ];

        // إذا تم تغيير التقييم وكان هناك شهادة سابقة، قد نحتاج لتنبيه المستخدم أو تغيير الحالة
        if ($application->certificate_status === 'approved') {
            $updateData['certificate_status'] = 'under_review';
        } elseif ($application->certificate_status === 'draft') {
            $updateData['certificate_status'] = 'under_review';
        }

        $application->update($updateData);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم الحفظ تلقائياً',
                'certificate_status' => $application->certificate_status
            ]);
        }

        return back()->with('success', 'تم تحديث بيانات التتبع بنجاح. يمكنك الآن معاينة الشهادة أو اعتمادها.');
    }

    public function previewCertificate(Application $application)
    {
        if ($application->opportunity->organization_id !== Auth::user()->organization->id) {
            abort(403);
        }

        return $this->certificateService->preview($application)->stream('certificate-preview.pdf');
    }

    public function issueCertificate(Application $application)
    {
        if ($application->opportunity->organization_id !== Auth::user()->organization->id) {
            abort(403);
        }

        if (!$this->certificateService->isEligible($application)) {
            return back()->with('error', 'المتقدم غير مؤهل للحصول على شهادة (يجب حضور 70% على الأقل وتقييم 3 فأعلى).');
        }

        $certificate = $this->certificateService->generate($application);

        if ($certificate) {
            $application->update(['certificate_status' => 'approved']);
            return back()->with('success', 'تم اعتماد وإصدار الشهادة بنجاح.');
        }

        return back()->with('error', 'فشل إصدار الشهادة. يرجى مراجعة البيانات.');
    }

    public function rejectCertificate(Application $application)
    {
        if ($application->opportunity->organization_id !== Auth::user()->organization->id) {
            abort(403);
        }

        $application->update(['certificate_status' => 'rejected']);

        return back()->with('success', 'تم رفض إصدار الشهادة في حالتها الحالية.');
    }
}
