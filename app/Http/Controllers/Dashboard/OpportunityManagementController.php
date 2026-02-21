<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Opportunity;
use App\Models\City;
use App\Models\Application;
use App\Models\Notification;
use App\Models\UserInterest;
use App\Models\User;
use App\Helpers\FileUploadHelper;
use App\Services\CertificateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class OpportunityManagementController extends Controller
{
    protected $certificateService;

    public function __construct(CertificateService $certificateService)
    {
        $this->certificateService = $certificateService;
    }

    public function index()
    {
        $organization = Auth::user()->organization;
        $opportunities = Opportunity::where('organization_id', $organization->id)
            ->withCount('applications')
            ->latest()
            ->paginate(10);

        return view('dashboard.organization.opportunities.index', compact('opportunities'));
    }

    public function create()
    {
        if (Auth::user()->organization->status !== 'approved') {
            return redirect()->route('organization.opportunities.index')
                ->with('error', 'يجب اعتماد مؤسستكم من قبل الإدارة لتتمكنوا من إضافة فرص جديدة.');
        }

        $cities = City::all();
        return view('dashboard.organization.opportunities.create', compact('cities'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->organization->status !== 'approved') {
            return redirect()->route('organization.opportunities.index')
                ->with('error', 'يجب اعتماد مؤسستكم من قبل الإدارة لتتمكنوا من إضافة فرص جديدة.');
        }

        $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string',
            'objectives' => 'nullable|string',
            'tasks' => 'nullable|string',
            'training_outcomes' => 'nullable|string',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'application_deadline' => 'required|date|before_or_equal:start_date',
            'execution_method' => 'required|in:in_person,remote',
            'address' => 'nullable|required_if:execution_method,in_person|string',
            'city_id' => 'nullable|required_if:execution_method,in_person|exists:cities,id',
            'type' => 'required|in:volunteering,training',
            'category' => 'required|string',
            'subcategory' => 'nullable|string',
            'volunteer_type' => 'nullable|required_if:type,volunteering|string',
            'training_field' => 'nullable|required_if:type,training|string',
            'training_duration' => 'nullable|required_if:type,training|string',
            'is_certified' => 'nullable|required_if:type,training|string',
            'is_paid' => 'nullable|required_if:type,training|string',
            'seats' => 'required|integer|min:1',
            'total_hours' => 'required|integer|min:1',
            'daily_hours' => 'nullable|integer|min:1',
            'age_requirement' => 'nullable|string',
            'skills_requirement' => 'nullable|string',
            'education_level' => 'nullable|string',
            'previous_experience' => 'nullable|string',
            'requires_certification' => 'nullable|string',
            'requires_cover_letter' => 'required|in:yes,no',
            'contact_name' => 'nullable|string',
            'contact_info' => 'nullable|string',
        ], [
            'title.required' => 'يرجى إدخال عنوان الفرصة.',
            'title.max' => 'العنوان يجب ألا يتجاوز 100 حريف.',
            'description.required' => 'يرجى إدخال وصف الفرصة.',
            'start_date.required' => 'تاريخ البدء مطلوب.',
            'end_date.required' => 'تاريخ الانتهاء مطلوب.',
            'application_deadline.required' => 'تاريخ إغلاق التقديم مطلوب.',
            'execution_method.required' => 'يرجى اختيار طريقة التنفيذ.',
            'type.required' => 'يرجى اختيار نوع الفرصة.',
            'category.required' => 'يرجى اختيار الفئة.',
            'seats.required' => 'يرجى تحديد عدد المقاعد المتاحة.',
            'total_hours.required' => 'يرجى إدخال إجمالي عدد الساعات.',
            'requires_cover_letter.required' => 'يرجى تحديد ما إذا كان يتطلب رسالة تغطية.',
        ]);

        $organization = Auth::user()->organization;

        $status = $organization->auto_publish_opportunities ? 1 : 0;
        
        // التحقق من الملف
        $request->validate([
            'certificate_file' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048',
        ], [
            'certificate_file.max' => 'حجم الملف يجب ألا يتجاوز 2 ميجابايت.',
            'certificate_file.mimes' => 'يجب أن يكون الملف بصيغة PDF أو صورة (JPG, PNG).',
        ]);

        $opportunity = Opportunity::create([
            'organization_id' => $organization->id,
            'title' => $request->title,
            'description' => $request->description,
            'objectives' => $request->objectives,
            'tasks' => $request->tasks,
            'training_outcomes' => $request->training_outcomes,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'application_deadline' => $request->application_deadline,
            'execution_method' => $request->execution_method,
            'address' => $request->address,
            'city_id' => $request->city_id,
            'type' => $request->type,
            'category' => $request->category,
            'subcategory' => $request->subcategory,
            'volunteer_type' => $request->volunteer_type,
            'training_field' => $request->training_field,
            'training_duration' => $request->training_duration,
            'is_certified' => $request->is_certified,
            'is_paid' => $request->is_paid,
            'seats' => $request->seats,
            'total_hours' => $request->total_hours,
            'daily_hours' => $request->daily_hours,
            'age_requirement' => $request->age_requirement,
            'skills_requirement' => $request->skills_requirement,
            'education_level' => $request->education_level,
            'previous_experience' => $request->previous_experience,
            'provides_certificate' => true,
            'requires_certification' => $request->requires_certification,
            'requires_cover_letter' => $request->requires_cover_letter == 'yes',
            'is_practical' => $request->has('is_practical'),
            'has_stipend' => $request->has('has_stipend'),
            'attendance_required' => $request->has('attendance_required'),
            'pre_test_required' => $request->has('pre_test_required'),
            'contact_name' => $request->contact_name,
            'contact_info' => $request->contact_info,
            'status' => $status,
        ]);

        // رفع الملف إذا وجد
        if ($request->hasFile('certificate_file')) {
            FileUploadHelper::upload(
                $request->file('certificate_file'),
                'certificate',
                null,
                $organization->id,
                $opportunity->id
            );
        }

        $message = $status == 1 ? 'تمت إضافة ونشر الفرصة بنجاح.' : 'تمت إضافة الفرصة بنجاح وهي بانتظار مراجعة الإدارة.';

        // إشعار ذكي: أرسل إشعاراً للمستخدمين المهتمين بهذه الفئة
        if ($status == 1) {
            $this->notifyInterestedUsers($opportunity);
        }

        return redirect()->route('organization.opportunities.index')->with('success', $message);
    }

    /**
     * Send notifications to users interested in the same opportunity category.
     */
    private function notifyInterestedUsers(Opportunity $opportunity): void
    {
        $interestedUserIds = UserInterest::where('category', $opportunity->category)
            ->pluck('user_id');

        foreach ($interestedUserIds as $userId) {
            // Don't notify the organization owner themselves
            if ($userId === Auth::id()) continue;

            Notification::create([
                'user_id' => $userId,
                'title'   => '✨ فرصة مقترحة لك',
                'message' => 'تم نشر فرصة جديدة في مجال "' . $opportunity->category . '": ' . $opportunity->title,
                'type'    => 'suggested_opportunity',
                'data'    => json_encode(['opportunity_id' => $opportunity->id]),
                'is_read' => false,
            ]);
        }
    }

    public function edit(Opportunity $opportunity)
    {
        $this->authorizeOwner($opportunity);

        if ($opportunity->status == Opportunity::STATUS_COMPLETED) {
            return redirect()->route('organization.opportunities.index')
                ->with('error', 'لا يمكن تعديل فرصة مكتملة.');
        }

        $cities = City::all();
        return view('dashboard.organization.opportunities.edit', compact('opportunity', 'cities'));
    }

    public function update(Request $request, Opportunity $opportunity)
    {
        $this->authorizeOwner($opportunity);
        
        if ($opportunity->status == Opportunity::STATUS_COMPLETED) {
            return redirect()->route('organization.opportunities.index')
                ->with('error', 'لا يمكن تعديل فرصة مكتملة.');
        }
        
        $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string',
            'objectives' => 'nullable|string',
            'tasks' => 'nullable|string',
            'training_outcomes' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'application_deadline' => 'required|date|before_or_equal:start_date',
            'execution_method' => 'required|in:in_person,remote',
            'address' => 'nullable|required_if:execution_method,in_person|string',
            'city_id' => 'nullable|required_if:execution_method,in_person|exists:cities,id',
            'type' => 'required|in:volunteering,training',
            'category' => 'required|string',
            'subcategory' => 'nullable|string',
            'volunteer_type' => 'nullable|required_if:type,volunteering|string',
            'training_field' => 'nullable|required_if:type,training|string',
            'training_duration' => 'nullable|required_if:type,training|string',
            'is_certified' => 'nullable|required_if:type,training|string',
            'is_paid' => 'nullable|required_if:type,training|string',
            'seats' => 'required|integer|min:1',
            'total_hours' => 'required|integer|min:1',
            'daily_hours' => 'nullable|integer|min:1',
            'age_requirement' => 'nullable|string',
            'skills_requirement' => 'nullable|string',
            'education_level' => 'nullable|string',
            'previous_experience' => 'nullable|string',
            'requires_certification' => 'nullable|string',
            'requires_cover_letter' => 'required|in:yes,no',
            'contact_name' => 'nullable|string',
            'contact_info' => 'nullable|string',
        ], [
            'title.required' => 'يرجى إدخال عنوان الفرصة.',
            'title.max' => 'العنوان يجب ألا يتجاوز 100 حرف.',
            'description.required' => 'يرجى إدخال وصف الفرصة.',
            'start_date.required' => 'تاريخ البدء مطلوب.',
            'end_date.required' => 'تاريخ الانتهاء مطلوب.',
            'application_deadline.required' => 'تاريخ إغلاق التقديم مطلوب.',
            'execution_method.required' => 'يرجى اختيار طريقة التنفيذ.',
            'type.required' => 'يرجى اختيار نوع الفرصة.',
            'category.required' => 'يرجى اختيار الفئة.',
            'seats.required' => 'يرجى تحديد عدد المقاعد المتاحة.',
            'total_hours.required' => 'يرجى إدخال إجمالي عدد الساعات.',
            'requires_cover_letter.required' => 'يرجى تحديد ما إذا كان يتطلب رسالة تغطية.',
        ]);

        // التحقق من الملف
        $request->validate([
            'certificate_file' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048',
        ], [
            'certificate_file.max' => 'حجم الملف يجب ألا يتجاوز 2 ميجابايت.',
            'certificate_file.mimes' => 'يجب أن يكون الملف بصيغة PDF أو صورة (JPG, PNG).',
        ]);

        $updateData = [
            'title' => $request->title,
            'description' => $request->description,
            'objectives' => $request->objectives,
            'tasks' => $request->tasks,
            'training_outcomes' => $request->training_outcomes,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'application_deadline' => $request->application_deadline,
            'execution_method' => $request->execution_method,
            'address' => $request->address,
            'city_id' => $request->city_id,
            'type' => $request->type,
            'category' => $request->category,
            'subcategory' => $request->subcategory,
            'volunteer_type' => $request->volunteer_type,
            'training_field' => $request->training_field,
            'training_duration' => $request->training_duration,
            'is_certified' => $request->is_certified,
            'is_paid' => $request->is_paid,
            'seats' => $request->seats,
            'total_hours' => $request->total_hours,
            'daily_hours' => $request->daily_hours,
            'age_requirement' => $request->age_requirement,
            'skills_requirement' => $request->skills_requirement,
            'education_level' => $request->education_level,
            'previous_experience' => $request->previous_experience,
            'provides_certificate' => true,
            'requires_certification' => $request->requires_certification,
            'requires_cover_letter' => $request->requires_cover_letter == 'yes',
            'is_practical' => $request->has('is_practical'),
            'has_stipend' => $request->has('has_stipend'),
            'attendance_required' => $request->has('attendance_required'),
            'pre_test_required' => $request->has('pre_test_required'),
            'contact_name' => $request->contact_name,
            'contact_info' => $request->contact_info,
        ];

        // إذا كانت الفرصة بانتظار تعديل، تعود للمراجعة بعد الحفظ
        if ($opportunity->status == 2) {
            $updateData['status'] = 0;
        }

        $opportunity->update($updateData);

        // تحديث الملف إذا تم رفعه
        if ($request->hasFile('certificate_file')) {
            // حذف القديم إن وجد
            if ($opportunity->certificateFile) {
                FileUploadHelper::delete($opportunity->certificateFile->id);
            }

            FileUploadHelper::upload(
                $request->file('certificate_file'),
                'certificate',
                null,
                $opportunity->organization_id,
                $opportunity->id
            );
        }

        return redirect()->route('organization.opportunities.index')->with('success', 'تم تحديث الفرصة بنجاح.');
    }

    public function startExecution(Opportunity $opportunity)
    {
        $this->authorizeOwner($opportunity);

        if ($opportunity->status != Opportunity::STATUS_PUBLISHED && $opportunity->status != Opportunity::STATUS_CLOSED) {
            return back()->with('error', 'لا يمكن بدء تنفيذ هذه الفرصة في حالتها الحالية.');
        }

        $opportunity->update(['status' => Opportunity::STATUS_UNDER_IMPLEMENTATION]);

        // Update all accepted applications to executing
        $opportunity->applications()->where('status', Application::STATUS_ACCEPTED)
            ->update(['status' => Application::STATUS_EXECUTING]);

        return back()->with('success', 'تم بدء تنفيذ الفرصة بنجاح.');
    }

    public function completeExecution(Opportunity $opportunity)
    {
        $this->authorizeOwner($opportunity);

        if ($opportunity->status != Opportunity::STATUS_UNDER_IMPLEMENTATION) {
            return back()->with('error', 'لا يمكن إنهاء تنفيذ هذه الفرصة في حالتها الحالية.');
        }

        $opportunity->update(['status' => Opportunity::STATUS_COMPLETED]);

        // Update all executing applications to completed
        $applications = $opportunity->applications()->where('status', Application::STATUS_EXECUTING)->get();
        
        foreach ($applications as $app) {
            $app->update(['status' => Application::STATUS_COMPLETED]);
            
            // Try to generate certificate if they already have hours/score set
            if ($this->certificateService->isEligible($app)) {
                $this->certificateService->generate($app);
            }
        }

        return back()->with('success', 'تم إنهاء تنفيذ الفرصة بنجاح وتوليد الشهادات للمؤهلين.');
    }

    public function cancelOpportunity(Request $request, Opportunity $opportunity)
    {
        $this->authorizeOwner($opportunity);

        // يمكن إلغاء الفرص المنشورة أو قيد التنفيذ فقط
        if (!in_array($opportunity->status, [Opportunity::STATUS_PUBLISHED, Opportunity::STATUS_UNDER_IMPLEMENTATION])) {
            return back()->with('error', 'لا يمكن إلغاء هذه الفرصة في حالتها الحالية.');
        }

        $request->validate([
            'cancellation_reason' => 'required|string|min:10|max:500',
        ], [
            'cancellation_reason.required' => 'يجب إدخال سبب الإلغاء.',
            'cancellation_reason.min' => 'سبب الإلغاء يجب أن يكون 10 أحرف على الأقل.',
            'cancellation_reason.max' => 'سبب الإلغاء يجب ألا يتجاوز 500 حرف.',
        ]);

        $opportunity->update([
            'status' => Opportunity::STATUS_CANCELLED,
            'cancellation_reason' => $request->cancellation_reason,
            'cancelled_at' => now(),
        ]);

        // Update all related applications to cancelled
        $opportunity->applications()
            ->whereIn('status', [Application::STATUS_PENDING, Application::STATUS_ACCEPTED, Application::STATUS_EXECUTING])
            ->update(['status' => Application::STATUS_CANCELLED]);

        return back()->with('success', 'تم إلغاء الفرصة بنجاح.');
    }

    public function tracking(Opportunity $opportunity)
    {
        $this->authorizeOwner($opportunity);

        $applications = $opportunity->applications()
            ->whereIn('status', [Application::STATUS_ACCEPTED, Application::STATUS_EXECUTING, Application::STATUS_COMPLETED])
            ->with('user')
            ->get();

        return view('dashboard.organization.opportunities.tracking', compact('opportunity', 'applications'));
    }

    private function authorizeOwner(Opportunity $opportunity)
    {
        if ($opportunity->organization_id !== Auth::user()->organization->id) {
            abort(403);
        }
    }
}
