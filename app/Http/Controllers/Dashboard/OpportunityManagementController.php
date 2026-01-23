<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Opportunity;
use App\Models\City;
use App\Helpers\FileUploadHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OpportunityManagementController extends Controller
{
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
        $cities = City::all();
        return view('dashboard.organization.opportunities.create', compact('cities'));
    }

    public function store(Request $request)
    {
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
            'provides_certificate' => 'required|in:yes,no',
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
            'provides_certificate.required' => 'يرجى تحديد ما إذا كان هناك شهادات.',
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
            'provides_certificate' => $request->provides_certificate == 'yes',
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

        return redirect()->route('organization.opportunities.index')->with('success', $message);
    }

    public function edit(Opportunity $opportunity)
    {
        $this->authorizeOwner($opportunity);
        $cities = City::all();
        return view('dashboard.organization.opportunities.edit', compact('opportunity', 'cities'));
    }

    public function update(Request $request, Opportunity $opportunity)
    {
        $this->authorizeOwner($opportunity);
        
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
            'provides_certificate' => 'required|in:yes,no',
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
            'provides_certificate.required' => 'يرجى تحديد ما إذا كان هناك شهادات.',
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
            'provides_certificate' => $request->provides_certificate == 'yes',
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

    public function destroy(Opportunity $opportunity)
    {
        $this->authorizeOwner($opportunity);
        $opportunity->delete();
        return back()->with('success', 'تم حذف الفرصة بنجاح.');
    }

    private function authorizeOwner(Opportunity $opportunity)
    {
        if ($opportunity->organization_id !== Auth::user()->organization->id) {
            abort(403);
        }
    }
}
