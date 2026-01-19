@extends('layouts.dashboard')

@section('title', 'تعديل الفرصة')

@section('content')
<div style="max-width: 1000px; margin: 0 auto;">
    <div style="margin-bottom: 2rem;">
        <h2 style="font-size: 1.8rem; font-weight: 800; color: #1e293b; margin: 0;">تعديل الفرصة: {{ $opportunity->title }}</h2>
        <p style="color: #64748b; margin-top: 0.5rem;">قم بتحديث البيانات المطلوبة ثم اضغط حفظ.</p>
    </div>

    @if ($opportunity->status == 2 && $opportunity->admin_notes)
        <div style="background: #eff6ff; border-right: 5px solid #3b82f6; color: #1e40af; padding: 1.5rem; border-radius: 1rem; margin-bottom: 2rem; box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.1);">
            <div style="display: flex; gap: 1rem; align-items: start;">
                <i class="fas fa-info-circle" style="font-size: 1.5rem; margin-top: 0.2rem;"></i>
                <div>
                    <strong style="display: block; font-size: 1.1rem; margin-bottom: 0.5rem;">ملاحظات الإدارة للمراجعة:</strong>
                    <p style="margin: 0; line-height: 1.6;">{{ $opportunity->admin_notes }}</p>
                </div>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div style="background: #fee2e2; color: #b91c1c; padding: 1.25rem; border-radius: 1rem; margin-bottom: 2rem;">
            <ul style="margin: 0; padding-right: 1.5rem; font-weight: 600;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('organization.opportunities.update', $opportunity) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <!-- Section 1: الأساسيات -->
        <div class="card" style="margin-bottom: 2rem; padding: 2.5rem; border-radius: 1.5rem;">
            <h3 style="margin-bottom: 2rem; color: #4f46e5; border-bottom: 2px solid #f1f5f9; padding-bottom: 1rem;">1. المعلومات الأساسية</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div style="grid-column: span 2;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #475569;">عنوان الفرصة</label>
                    <input type="text" name="title" value="{{ old('title', $opportunity->title) }}" required
                           style="width: 100%; padding: 0.85rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; outline: none;">
                </div>

                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #475569;">نوع الفرصة</label>
                    <select name="type" required id="opportunity_type" style="width: 100%; padding: 0.85rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; outline: none; background: white;">
                        <option value="volunteering" {{ old('type', $opportunity->type) == 'volunteering' ? 'selected' : '' }}>تطوعية</option>
                        <option value="training" {{ old('type', $opportunity->type) == 'training' ? 'selected' : '' }}>تدريبية</option>
                    </select>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #475569;">التصنيف العام</label>
                    <select name="category" required style="width: 100%; padding: 0.85rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; outline: none; background: white;">
                        @foreach(['help', 'education', 'environment', 'entrepreneurship', 'sports', 'arts', 'health', 'technology'] as $cat)
                            <option value="{{ $cat }}" {{ old('category', $opportunity->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="grid-column: span 2;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #475569;">وصف عام للفرصة</label>
                    <textarea name="description" rows="4" required style="width: 100%; padding: 1rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; outline: none; resize: vertical;">{{ old('description', $opportunity->description) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Section 2: التفاصيل العميقة -->
        <div class="card" style="margin-bottom: 2rem; padding: 2.5rem; border-radius: 1.5rem;">
            <h3 style="margin-bottom: 2rem; color: #4f46e5; border-bottom: 2px solid #f1f5f9; padding-bottom: 1rem;">2. التفاصيل والأهداف</h3>
            <div style="display: grid; gap: 1.5rem;">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #475569;">أهداف البرنامج</label>
                    <textarea name="objectives" rows="3" style="width: 100%; padding: 1rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; outline: none;">{{ old('objectives', $opportunity->objectives) }}</textarea>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #475569;">المهام المطلوبة</label>
                    <textarea name="tasks" rows="3" style="width: 100%; padding: 1rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; outline: none;">{{ old('tasks', $opportunity->tasks) }}</textarea>
                </div>
                <div id="training_outcomes_div" style="{{ old('type', $opportunity->type) == 'training' ? '' : 'display:none;' }}">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #475569;">مخرجات التدريب (للمتدرب)</label>
                    <textarea name="training_outcomes" rows="3" style="width: 100%; padding: 1rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; outline: none;">{{ old('training_outcomes', $opportunity->training_outcomes) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Section 3: المواعيد والمكان -->
        <div class="card" style="margin-bottom: 2rem; padding: 2.5rem; border-radius: 1.5rem;">
            <h3 style="margin-bottom: 2rem; color: #4f46e5; border-bottom: 2px solid #f1f5f9; padding-bottom: 1rem;">3. المكان والخدمات اللوجستية</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem;">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #475569;">المدينة</label>
                    <select name="city_id" required style="width: 100%; padding: 0.85rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; outline: none; background: white;">
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}" {{ old('city_id', $opportunity->city_id) == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="grid-column: span 2;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #475569;">العنوان التفصيلي</label>
                    <input type="text" name="address" value="{{ old('address', $opportunity->address) }}" required style="width: 100%; padding: 0.85rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; outline: none;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #475569;">تاريخ البدء</label>
                    <input type="date" name="start_date" value="{{ old('start_date', $opportunity->start_date?->format('Y-m-d')) }}" required style="width: 100%; padding: 0.85rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; outline: none;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #475569;">تاريخ الانتهاء</label>
                    <input type="date" name="end_date" value="{{ old('end_date', $opportunity->end_date?->format('Y-m-d')) }}" required style="width: 100%; padding: 0.85rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; outline: none;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #475569;">ساعات العمل يومياً</label>
                    <input type="number" name="daily_hours" value="{{ old('daily_hours', $opportunity->daily_hours) }}" style="width: 100%; padding: 0.85rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; outline: none;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #475569;">إجمالي عدد المقاعد</label>
                    <input type="number" name="seats" value="{{ old('seats', $opportunity->seats) }}" required min="1" style="width: 100%; padding: 0.85rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; outline: none;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #475569;">مجموع الساعات الكلي</label>
                    <input type="number" name="total_hours" value="{{ old('total_hours', $opportunity->total_hours) }}" required style="width: 100%; padding: 0.85rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; outline: none;">
                </div>
            </div>
        </div>

        <!-- Section 4: الشروط والمميزات -->
        <div class="card" style="margin-bottom: 2rem; padding: 2.5rem; border-radius: 1.5rem;">
            <h3 style="margin-bottom: 2rem; color: #4f46e5; border-bottom: 2px solid #f1f5f9; padding-bottom: 1rem;">4. الشروط والمزايا</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #475569;">العمر المطلوب</label>
                    <input type="text" name="age_requirement" value="{{ old('age_requirement', $opportunity->age_requirement) }}" style="width: 100%; padding: 0.85rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; outline: none;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #475569;">المهارات المطلوبة</label>
                    <input type="text" name="skills_requirement" value="{{ old('skills_requirement', $opportunity->skills_requirement) }}" style="width: 100%; padding: 0.85rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; outline: none;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem;">
                <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer; padding: 1rem; background: #f8fafc; border-radius: 0.75rem;">
                    <input type="checkbox" name="is_practical" value="1" {{ old('is_practical', $opportunity->is_practical) ? 'checked' : '' }}>
                    <span style="font-weight: 600;">تدريب عملي؟</span>
                </label>
                <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer; padding: 1rem; background: #f8fafc; border-radius: 0.75rem;">
                    <input type="checkbox" name="has_stipend" value="1" {{ old('has_stipend', $opportunity->has_stipend) ? 'checked' : '' }}>
                    <span style="font-weight: 600;">يوجد بدل مالي؟</span>
                </label>
                <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer; padding: 1rem; background: #f8fafc; border-radius: 0.75rem;">
                    <input type="checkbox" name="attendance_required" value="1" {{ old('attendance_required', $opportunity->attendance_required) ? 'checked' : '' }}>
                    <span style="font-weight: 600;">يتطلب تسجيل حضور؟</span>
                </label>
                <label style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer; padding: 1rem; background: #f8fafc; border-radius: 0.75rem;">
                    <input type="checkbox" name="pre_test_required" value="1" {{ old('pre_test_required', $opportunity->pre_test_required) ? 'checked' : '' }}>
                    <span style="font-weight: 600;">يوجد اختبار قبلي؟</span>
                </label>
            </div>

            <div style="margin-top: 1.5rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #475569;">تفاصيل الشهادة</label>
                    <textarea name="requires_certification" rows="3" style="width: 100%; padding: 1rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; outline: none;">{{ old('requires_certification', $opportunity->requires_certification) }}</textarea>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #475569;">نموذج الشهادة (ملف PDF أو صورة)</label>
                    <input type="file" name="certificate_file" accept=".pdf,image/*" 
                           style="width: 100%; padding: 0.85rem; border: 2px dashed #e2e8f0; border-radius: 0.75rem; outline: none; background: #fff;">
                    @if($opportunity->certificateFile)
                        <div style="margin-top: 0.5rem;">
                            <a href="{{ asset($opportunity->certificateFile->file_url) }}" target="_blank" style="font-size: 0.85rem; color: #4f46e5; text-decoration: none; font-weight: 600;">
                                <i class="fas fa-file-download"></i> عرض الملف الحالي
                            </a>
                        </div>
                    @else
                        <p style="font-size: 0.75rem; color: #64748b; margin-top: 0.5rem;">يرجى رفع ملف الشهادة في حال وجوده للمعاينة من الأدمن.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Section 5: مسؤول التواصل -->
        <div class="card" style="margin-bottom: 3rem; padding: 2.5rem; border-radius: 1.5rem;">
            <h3 style="margin-bottom: 2rem; color: #4f46e5; border-bottom: 2px solid #f1f5f9; padding-bottom: 1rem;">5. بيانات التواصل</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #475569;">اسم المسؤول</label>
                    <input type="text" name="contact_name" value="{{ old('contact_name', $opportunity->contact_name) }}" style="width: 100%; padding: 0.85rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; outline: none;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #475569;">بيانات التواصل (إيميل أو هاتف)</label>
                    <input type="text" name="contact_info" value="{{ old('contact_info', $opportunity->contact_info) }}" style="width: 100%; padding: 0.85rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; outline: none;">
                </div>
            </div>
        </div>

        <!-- Buttons -->
        <div style="display: flex; justify-content: flex-end; gap: 1.5rem; margin-bottom: 4rem;">
            <a href="{{ route('organization.opportunities.index') }}" style="padding: 1rem 2.5rem; background: #e2e8f0; color: #475569; border-radius: 1rem; font-weight: 700; text-decoration: none;">إلغاء</a>
            <button type="submit" style="padding: 1rem 4rem; background: #4f46e5; color: white; border: none; border-radius: 1rem; font-weight: 800; cursor: pointer; box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.4);">حفظ التغييرات</button>
        </div>
    </form>
</div>

<script>
    document.getElementById('opportunity_type').addEventListener('change', function() {
        const outcomesDiv = document.getElementById('training_outcomes_div');
        if (this.value === 'training') {
            outcomesDiv.style.display = 'block';
        } else {
            outcomesDiv.style.display = 'none';
        }
    });
</script>

@endsection
