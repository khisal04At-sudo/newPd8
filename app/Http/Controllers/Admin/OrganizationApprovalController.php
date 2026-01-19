<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Notification;
use App\Models\City;
use App\Mail\OrganizationApprovedMail;
use App\Mail\OrganizationRejectedMail;
use App\Mail\RequestDocumentsMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrganizationApprovalController extends Controller
{
    /**
     * عرض قائمة المؤسسات التي تتطلب مراجعة
     */
    public function index(Request $request)
    {
        $query = Organization::with(['user', 'city', 'verificationDocuments']);

        // Filter by Status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by Sector (Legal Type)
        if ($request->filled('sector') && $request->sector !== 'all') {
            $query->where('sector', $request->sector);
        }

        // Filter by City
        if ($request->filled('city_id')) {
            $query->where('city_id', $request->city_id);
        }

        // Search by Name
        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        // Filter by Registration Date
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $organizations = $query->latest()->paginate(15);
        $cities = \App\Models\City::all();

        return view('admin.organizations.index', compact('organizations', 'cities'));
    }

    /**
     * عرض تفاصيل المؤسسة ومستنداتها
     */
    public function show(Organization $organization)
    {
        $organization->load(['user', 'city', 'verificationDocuments']);
        
        // Debug logging
        \Log::info('Organization Documents Debug', [
            'org_id' => $organization->id,
            'org_name' => $organization->name,
            'documents_count' => $organization->verificationDocuments->count(),
            'documents' => $organization->verificationDocuments->pluck('file_name'),
        ]);
        
        return view('admin.organizations.show', compact('organization'));
    }

    /**
     * الموافقة على المؤسسة
     */
    public function approve(Organization $organization)
    {
        $organization->update([
            'verified' => true,
            'verified_at' => now(),
            'status' => 'approved',
            'rejection_reason' => null,
            'requested_documents' => null,
        ]);

        // إرسال إشعار داخل النظام
        Notification::create([
            'user_id' => $organization->user_id,
            'title' => 'تم اعتماد مؤسستكم',
            'message' => 'تم اعتماد مؤسسة ' . $organization->name . ' بنجاح. يمكنكم الآن نشر الفرص التطوعية والبدء في استقبال المتطوعين.',
            'type' => 'system',
        ]);

        // إرسال إيميل
        try {
            Mail::to($organization->user->email)->send(new OrganizationApprovedMail($organization));
        } catch (\Exception $e) {
            \Log::error('Failed to send organization approved email: ' . $e->getMessage());
        }

        return redirect()
            ->route('admin.organizations.index')
            ->with('success', "تم اعتماد المؤسسة ({$organization->name}) بنجاح.");
    }

    /**
     * رفض المؤسسة
     */
    public function reject(Request $request, Organization $organization)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ], [
            'reason.required' => 'يجب إدخال سبب الرفض',
            'reason.max' => 'السبب طويل جداً (حد أقصى 500 حرف)',
        ]);

        $organization->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason,
        ]);

        // إرسال إشعار
        Notification::create([
            'user_id' => $organization->user_id,
            'title' => 'تحديث حول طلب الاعتماد',
            'message' => 'نأسف، تم رفض طلب اعتماد مؤسسة ' . $organization->name . '. السبب: ' . $request->reason,
            'type' => 'system',
        ]);

        // إرسال إيميل
        try {
            Mail::to($organization->user->email)->send(new OrganizationRejectedMail($organization, $request->reason));
        } catch (\Exception $e) {
            \Log::error('Failed to send organization rejected email: ' . $e->getMessage());
        }

        return redirect()
            ->route('admin.organizations.index')
            ->with('info', "تم رفض طلب اعتماد المؤسسة ({$organization->name}).");
    }

    /**
     * طلب مستندات إضافية
     */
    public function requestDocuments(Request $request, Organization $organization)
    {
        $request->validate([
            'requested_documents' => 'required|string|max:1000',
        ], [
            'requested_documents.required' => 'يجب تحديد المستندات المطلوبة',
            'requested_documents.max' => 'النص طويل جداً (حد أقصى 1000 حرف)',
        ]);

        $organization->update([
            'status' => 'needs_documents',
            'requested_documents' => $request->requested_documents,
        ]);

        // إرسال إشعار
        Notification::create([
            'user_id' => $organization->user_id,
            'title' => 'مستندات إضافية مطلوبة',
            'message' => 'يرجى تحميل المستندات التالية لإكمال عملية اعتماد مؤسستكم: ' . $request->requested_documents,
            'type' => 'system',
        ]);

        // إرسال إيميل
        try {
            Mail::to($organization->user->email)->send(new RequestDocumentsMail($organization, $request->requested_documents));
        } catch (\Exception $e) {
            \Log::error('Failed to send request documents email: ' . $e->getMessage());
        }

        return redirect()
            ->route('admin.organizations.index')
            ->with('info', "تم طلب مستندات إضافية من المؤسسة ({$organization->name}).");
    }
    public function toggleAutoPublish(Organization $organization)
    {
        $organization->update([
            'auto_publish_opportunities' => !$organization->auto_publish_opportunities
        ]);

        $status = $organization->auto_publish_opportunities ? 'تفعيل' : 'إيقاف';
        return back()->with('success', "تم {$status} خاصية النشر التلقائي لمؤسسة {$organization->name}.");
    }
}
