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
            'address' => 'required|string',
            'type' => 'required|in:volunteering,training',
            'category' => 'required|string',
            'total_hours' => 'required|integer|min:1',
            'daily_hours' => 'nullable|integer|min:1',
            'city_id' => 'required|exists:cities,id',
            'seats' => 'required|integer|min:1',
            'age_requirement' => 'nullable|string',
            'skills_requirement' => 'nullable|string',
            'requires_certification' => 'nullable|string',
            'contact_name' => 'nullable|string',
            'contact_info' => 'nullable|string',
        ], [
            'title.required' => 'يرجى إدخال عنوان الفرصة.',
            'title.max' => 'العنوان يجب ألا يتجاوز 100 حرف.',
            'description.required' => 'يرجى إدخال وصف الفرصة.',
            'start_date.required' => 'تاريخ البدء مطلوب.',
            'start_date.after_or_equal' => 'تاريخ البدء يجب أن يكون اليوم أو في المستقبل.',
            'end_date.required' => 'تاريخ الانتهاء مطلوب.',
            'end_date.after_or_equal' => 'تاريخ الانتهاء يجب أن يكون بعد تاريخ البدء.',
            'address.required' => 'يرجى إدخال العنوان التفصيلي.',
            'total_hours.required' => 'يرجى إدخال إجمالي عدد الساعات.',
            'total_hours.min' => 'عدد الساعات يجب أن يكون ساعة واحدة على الأقل.',
            'city_id.required' => 'يرجى اختيار المدينة.',
            'seats.required' => 'يرجى تحديد عدد المقاعد المتاحة.',
            'seats.min' => 'عدد المقاعد يجب أن يكون 1 على الأقل.',
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
            'address' => $request->address,
            'type' => $request->type,
            'category' => $request->category,
            'total_hours' => $request->total_hours,
            'daily_hours' => $request->daily_hours,
            'city_id' => $request->city_id,
            'seats' => $request->seats,
            'age_requirement' => $request->age_requirement,
            'skills_requirement' => $request->skills_requirement,
            'requires_certification' => $request->requires_certification,
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
            'address' => 'required|string',
            'type' => 'required|in:volunteering,training',
            'category' => 'required|string',
            'total_hours' => 'required|integer|min:1',
            'daily_hours' => 'nullable|integer|min:1',
            'city_id' => 'required|exists:cities,id',
            'seats' => 'required|integer|min:1',
            'age_requirement' => 'nullable|string',
            'skills_requirement' => 'nullable|string',
            'requires_certification' => 'nullable|string',
            'contact_name' => 'nullable|string',
            'contact_info' => 'nullable|string',
        ], [
            'title.required' => 'يرجى إدخال عنوان الفرصة.',
            'title.max' => 'العنوان يجب ألا يتجاوز 100 حرف.',
            'description.required' => 'يرجى إدخال وصف الفرصة.',
            'start_date.required' => 'تاريخ البدء مطلوب.',
            'end_date.required' => 'تاريخ الانتهاء مطلوب.',
            'end_date.after_or_equal' => 'تاريخ الانتهاء يجب أن يكون بعد تاريخ البدء.',
            'address.required' => 'يرجى إدخال العنوان التفصيلي.',
            'total_hours.required' => 'يرجى إدخال إجمالي عدد الساعات.',
            'total_hours.min' => 'عدد الساعات يجب أن يكون ساعة واحدة على الأقل.',
            'city_id.required' => 'يرجى اختيار المدينة.',
            'seats.required' => 'يرجى تحديد عدد المقاعد المتاحة.',
            'seats.min' => 'عدد المقاعد يجب أن يكون 1 على الأقل.',
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
            'address' => $request->address,
            'type' => $request->type,
            'category' => $request->category,
            'total_hours' => $request->total_hours,
            'daily_hours' => $request->daily_hours,
            'city_id' => $request->city_id,
            'seats' => $request->seats,
            'age_requirement' => $request->age_requirement,
            'skills_requirement' => $request->skills_requirement,
            'requires_certification' => $request->requires_certification,
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
