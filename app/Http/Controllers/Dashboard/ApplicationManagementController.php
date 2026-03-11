<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Notification;
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
        $tab = $request->input('tab', 'requests'); // requests, active, history
        
        $query = Application::whereHas('opportunity', function($q) use ($organizationId) {
            $q->where('organization_id', $organizationId);
        })->with(['user', 'opportunity.applications']);
        
        // Filter by Opportunity
        if ($request->has('opportunity_id') && $request->input('opportunity_id') != '') {
            $opportunityId = $request->input('opportunity_id');
            $query->where('opportunity_id', $opportunityId);
            
            $opportunity = \App\Models\Opportunity::where('id', $opportunityId)
                ->where('organization_id', $organizationId)
                ->withCount('applications')
                ->first();
                
            if (!$opportunity) {
                abort(404, 'الفرصة غير موجودة');
            }
        } else {
            $opportunity = null;
        }

        // Tab Filtering Logic
        if ($tab === 'requests') {
            $query->where('status', 'pending');
        } elseif ($tab === 'active') {
            $query->where('status', 'accepted');
        } elseif ($tab === 'history') {
            $query->whereIn('status', ['rejected', 'completed', 'cancelled']);
        }
        
        $applications = $query->latest()->paginate(20);
        
        // Calculate Statistics for Tabs
        $statsQuery = Application::whereHas('opportunity', function($q) use ($organizationId) {
            $q->where('organization_id', $organizationId);
        });
        
        if ($opportunity) {
            $statsQuery->where('opportunity_id', $opportunity->id);
        }
        
        $counts = [
            'requests' => (clone $statsQuery)->where('status', 'pending')->count(),
            'active' => (clone $statsQuery)->where('status', 'accepted')->count(),
            'history' => (clone $statsQuery)->whereIn('status', ['rejected', 'completed', 'cancelled'])->count(),
        ];
        
        // جلب جميع فرص المؤسسة للفلتر
        $opportunities = \App\Models\Opportunity::where('organization_id', $organizationId)
            ->withCount('applications')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.organization.applications.index', compact(
            'applications', 
            'opportunity', 
            'opportunities', 
            'tab', 
            'counts'
        ));
    }

    public function updateStatus(Request $request, Application $application)
    {
        if ($application->opportunity->organization_id !== Auth::user()->organization->id) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:accepted,rejected'
        ]);

        $newStatus = $request->status;

        // Seat Validation for Acceptance
        if ($newStatus === 'accepted') {
            if ($application->opportunity->isFull()) {
                return back()->with('error', 'نعتذر، لا يمكن قبول المزيد من المشاركين. لقد تم استيفاء جميع المقاعد المتاحة (' . $application->opportunity->seats . ') لهذه الفرصة.');
            }
        }

        $application->update([
            'status' => $newStatus,
            'decision_by' => Auth::id(),
            'decision_at' => now()
        ]);

        // ===== إشعار المتطوع بقبول أو رفض طلبه =====
        if ($newStatus === 'accepted') {
            Notification::create([
                'user_id' => $application->user_id,
                'title'   => '✅ تم قبول طلبك',
                'message' => 'تهانينا! تم قبول طلبك للمشاركة في فرصة "' . $application->opportunity->title . '".',
                'type'    => 'application_status',
                'data'    => json_encode(['opportunity_id' => $application->opportunity_id, 'application_id' => $application->id]),
                'is_read' => false,
            ]);
        } elseif ($newStatus === 'rejected') {
            Notification::create([
                'user_id' => $application->user_id,
                'title'   => '❌ تم رفض طلبك',
                'message' => 'نأسف لإخبارك بأنه تم رفض طلبك للمشاركة في فرصة "' . $application->opportunity->title . '".',
                'type'    => 'application_status',
                'data'    => json_encode(['opportunity_id' => $application->opportunity_id, 'application_id' => $application->id]),
                'is_read' => false,
            ]);
        }

        // Auto-close opportunity if seats are full after this acceptance
        if ($newStatus === 'accepted') {
            $opportunity = $application->opportunity;
            if ($opportunity->isFull() && $opportunity->status == \App\Models\Opportunity::STATUS_PUBLISHED) {
                $opportunity->update(['status' => \App\Models\Opportunity::STATUS_CLOSED]);
            }

            // ===== إشعار اكتمال العدد المطلوب لجميع المقبولين =====
            if ($opportunity->isFull()) {
                $acceptedUserIds = $opportunity->applications()
                    ->where('status', 'accepted')
                    ->pluck('user_id');

                foreach ($acceptedUserIds as $userId) {
                    Notification::create([
                        'user_id' => $userId,
                        'title'   => '🎉 اكتمل عدد المتطوعين',
                        'message' => 'اكتمل العدد المطلوب من المتطوعين لفرصة "' . $opportunity->title . '". سيتم التواصل معكم قريباً.',
                        'type'    => 'opportunity',
                        'data'    => json_encode(['opportunity_id' => $opportunity->id]),
                        'is_read' => false,
                    ]);
                }
            }
        }

        $message = $newStatus === 'accepted' ? 'تم قبول المتقدم بنجاح.' : 'تم رفض الطلب بنجاح.';
        return back()->with('success', $message);
    }

    public function addToWaitlist(Application $application)
    {
        if ($application->opportunity->organization_id !== Auth::user()->organization->id) {
            abort(403);
        }

        if (!in_array($application->status, [Application::STATUS_PENDING])) {
            return back()->with('error', 'لا يمكن إضافة هذا الطلب لقائمة الانتظار في حالته الحالية.');
        }

        $application->update(['status' => Application::STATUS_WAITLISTED]);

        Notification::create([
            'user_id' => $application->user_id,
            'title'   => '⏳ أنت في قائمة الانتظار',
            'message' => 'تمت إضافتك إلى قائمة انتظار فرصة "' . $application->opportunity->title . '". سيتم إشعارك عند توفر مقعد.',
            'type'    => 'waitlisted',
            'data'    => json_encode(['opportunity_id' => $application->opportunity_id]),
            'is_read' => false,
        ]);

        return back()->with('success', 'تمت إضافة المتقدم لقائمة الانتظار.');
    }

    public function promoteFromWaitlist(Application $application)
    {
        if ($application->opportunity->organization_id !== Auth::user()->organization->id) {
            abort(403);
        }

        if ($application->status !== Application::STATUS_WAITLISTED) {
            return back()->with('error', 'هذا المتقدم ليس في قائمة الانتظار.');
        }

        if ($application->opportunity->isFull()) {
            return back()->with('error', 'لا تزال المقاعد ممتلئة، لا يمكن ترقية المتقدم.');
        }

        $application->update([
            'status'      => Application::STATUS_ACCEPTED,
            'decision_by' => Auth::id(),
            'decision_at' => now(),
        ]);

        Notification::create([
            'user_id' => $application->user_id,
            'title'   => '✅ تمت ترقيتك من قائمة الانتظار',
            'message' => 'تهانينا! تم قبولك في فرصة "' . $application->opportunity->title . '" بعد توفر مقعد.',
            'type'    => 'accepted',
            'data'    => json_encode(['opportunity_id' => $application->opportunity_id]),
            'is_read' => false,
        ]);

        return back()->with('success', 'تمت ترقية المتقدم من قائمة الانتظار بنجاح وتم إشعاره.');
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
