@extends('layouts.dashboard')

@section('title', 'تعديل الفرصة: ' . $opportunity->title)

@section('content')
<div style="max-width: 1000px; margin: 0 auto;">
    <div style="margin-bottom: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 style="font-size: 1.8rem; font-weight: 800; color: #1e293b; margin: 0;">تعديل الفرصة</h2>
                <p style="color: #64748b; margin-top: 0.5rem;">قم بتحديث البيانات المطلوبة ثم اضغط حفظ التغييرات.</p>
            </div>
            <a href="{{ route('organization.opportunities.index') }}" style="padding: 0.75rem 1.5rem; background: #f1f5f9; color: #64748b; border-radius: 0.75rem; font-weight: 700; text-decoration: none; font-size: 0.9rem; transition: all 0.2s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                <i class="fas fa-arrow-right" style="margin-left: 0.5rem;"></i> العودة للقائمة
            </a>
        </div>
    </div>

    <!-- Modern Step Progress Header -->
    <div style="background: white; padding: 1.5rem 2rem; border-radius: 1.5rem; margin-bottom: 2rem; box-shadow: 0 4px 20px rgba(0,0,0,0.03); border: 1px solid #f1f5f9;">
        <div style="display: flex; justify-content: space-between; align-items: center; position: relative; max-width: 600px; margin: 0 auto 1.5rem;">
            <!-- Progress Line -->
            <div style="position: absolute; top: 50%; left: 0; right: 0; height: 3px; background: #f1f5f9; z-index: 1; transform: translateY(-50%);">
                <div id="progress-fill" style="height: 100%; background: linear-gradient(90deg, #3b82f6, #10b981); width: 33.33%; transition: all 0.5s ease;"></div>
            </div>
            
            <!-- Step Bubbles -->
            <div class="step-bubble active" data-step="1" style="z-index: 2; width: 40px; height: 40px; border-radius: 50%; background: white; border: 3px solid #3b82f6; display: flex; align-items: center; justify-content: center; font-weight: 800; color: #3b82f6; transition: all 0.3s; box-shadow: 0 4px 10px rgba(59, 130, 246, 0.2);">1</div>
            <div class="step-bubble" data-step="2" style="z-index: 2; width: 40px; height: 40px; border-radius: 50%; background: white; border: 3px solid #f1f5f9; display: flex; align-items: center; justify-content: center; font-weight: 800; color: #94a3b8; transition: all 0.3s;">2</div>
            <div class="step-bubble" data-step="3" style="z-index: 2; width: 40px; height: 40px; border-radius: 50%; background: white; border: 3px solid #f1f5f9; display: flex; align-items: center; justify-content: center; font-weight: 800; color: #94a3b8; transition: all 0.3s;">3</div>
        </div>
        <div style="text-align: center;">
            <h3 id="step-title" style="font-size: 1.25rem; font-weight: 800; color: #1e293b; margin: 0;">المعلومات الأساسية والتنفيذ</h3>
            <p id="step-desc" style="font-size: 0.9rem; color: #64748b; margin-top: 0.25rem;">أدخل البيانات الأساسية للفرصة ومكان وزمان التنفيذ</p>
        </div>
    </div>

    <!-- Admin Notes if exists -->
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

    <!-- Error Display -->
    @if ($errors->any())
        <div style="background: #fee2e2; color: #b91c1c; padding: 1.25rem; border-radius: 1rem; margin-bottom: 2rem;">
            <ul style="margin: 0; padding-right: 1.5rem; font-weight: 600;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="opportunity-form" action="{{ route('organization.opportunities.update', $opportunity) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <!-- Step 1: المعلومات الأساسية والتنفيذ -->
        <div class="form-step active" data-step="1">
            <div class="card" style="padding: 2rem; border-radius: 1.5rem; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.25rem;">
                    <div style="grid-column: span 2;">
                        <label class="form-label">عنوان الفرصة <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="title" id="title" class="form-input" value="{{ old('title', $opportunity->title) }}" placeholder="مثال: مدرب جرافيك للناشئين" data-required="true">
                        <div class="field-error"></div>
                    </div>

                    <div>
                        <label class="form-label">نوع الفرصة <span style="color: #ef4444;">*</span></label>
                        <select name="type" id="type" class="form-input" data-required="true">
                            <option value="">اختر نوع الفرصة</option>
                            <option value="volunteering" {{ old('type', $opportunity->type) == 'volunteering' ? 'selected' : '' }}>تطوعية</option>
                            <option value="training" {{ old('type', $opportunity->type) == 'training' ? 'selected' : '' }}>تدريبية</option>
                        </select>
                        <div class="field-error"></div>
                    </div>

                    <div>
                        <label class="form-label">الفئة <span style="color: #ef4444;">*</span></label>
                        <select name="category" id="category" class="form-input" data-required="true">
                            <option value="">اختر الفئة</option>
                            @php
                                $categories = ['بيئة', 'تكنولوجيا', 'تعليم', 'صحة', 'مساعدة إنسانية', 'ريادة أعمال', 'رياضة', 'فنون'];
                            @endphp
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ old('category', $opportunity->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                        <div class="field-error"></div>
                    </div>

                    <div>
                        <label class="form-label">الفئة الفرعية</label>
                        <select name="subcategory" id="subcategory" class="form-input">
                            <option value="{{ $opportunity->subcategory }}">{{ $opportunity->subcategory ?? 'اختر الفئة أولاً' }}</option>
                        </select>
                        <div class="field-error"></div>
                    </div>

                    <div>
                        <label class="form-label">طريقة التنفيذ <span style="color: #ef4444;">*</span></label>
                        <select name="execution_method" id="execution_method" class="form-input" data-required="true">
                            <option value="">اختر طريقة التنفيذ</option>
                            <option value="in_person" {{ (old('execution_method') ?? ($opportunity->city_id ? 'in_person' : '')) == 'in_person' ? 'selected' : '' }}>حضوري</option>
                            <option value="remote" {{ (old('execution_method') ?? ($opportunity->city_id ? '' : 'remote')) == 'remote' ? 'selected' : '' }}>عن بعد</option>
                        </select>
                        <div class="field-error"></div>
                    </div>

                    <div id="location-fields" style="{{ (old('execution_method') ?? ($opportunity->city_id ? 'in_person' : '')) == 'in_person' ? 'display: block;' : 'display: none;' }} grid-column: span 2;">
                        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 1.25rem;">
                            <div>
                                <label class="form-label">المدينة <span style="color: #ef4444;">*</span></label>
                                <select name="city_id" id="city_id" class="form-input">
                                    <option value="">اختر المدينة</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}" {{ old('city_id', $opportunity->city_id) == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                                    @endforeach
                                </select>
                                <div class="field-error"></div>
                            </div>
                            <div>
                                <label class="form-label">العنوان/الموقع (اختياري)</label>
                                <input type="text" name="address" id="address" class="form-input" value="{{ old('address', $opportunity->address) }}" placeholder="الحي، اسم المبنى، الطابق">
                                <div class="field-error"></div>
                            </div>
                        </div>
                    </div>

                    <div style="grid-column: span 2;">
                        <label class="form-label">أدوات التواصل <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="contact_info" id="contact_info" class="form-input" value="{{ old('contact_info', $opportunity->contact_info) }}" placeholder="مثال: البريد الإلكتروني، رقم الهاتف، رابط المنصة" data-required="true">
                        <div class="field-error"></div>
                    </div>

                    <div style="grid-column: span 2; display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.25rem;">
                        <div>
                            <label class="form-label">تاريخ البداية <span style="color: #ef4444;">*</span></label>
                            <input type="date" name="start_date" id="start_date" class="form-input" value="{{ old('start_date', $opportunity->start_date?->format('Y-m-d')) }}" data-required="true">
                            <div class="field-error"></div>
                        </div>
                        <div>
                            <label class="form-label">تاريخ النهاية <span style="color: #ef4444;">*</span></label>
                            <input type="date" name="end_date" id="end_date" class="form-input" value="{{ old('end_date', $opportunity->end_date?->format('Y-m-d')) }}" data-required="true">
                            <div class="field-error"></div>
                        </div>
                        <div>
                            <label class="form-label">إغلاق التقديم <span style="color: #ef4444;">*</span></label>
                            <input type="date" name="application_deadline" id="application_deadline" class="form-input" value="{{ old('application_deadline') ?? $opportunity->start_date?->subDays(2)->format('Y-m-d') }}" data-required="true">
                            <div class="field-error"></div>
                        </div>
                    </div>

                    <div style="grid-column: span 2;">
                        <label class="form-label">وصف الفرصة <span style="color: #ef4444;">*</span></label>
                        <textarea name="description" id="description" rows="3" class="form-input" placeholder="مقدمة بسيطة عن الفرصة..." data-required="true">{{ old('description', $opportunity->description) }}</textarea>
                        <div class="field-error"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 2: تفاصيل الفرصة والشروط -->
        <div class="form-step" data-step="2" style="display: none;">
            <div class="card" style="padding: 2rem; border-radius: 1.5rem; border: none;">
                <!-- Volunteering Fields Area -->
                <div id="volunteering-fields" style="{{ old('type', $opportunity->type) == 'volunteering' ? 'display: block;' : 'display: none;' }} margin-bottom: 2rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1rem;">
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.25rem;">
                        <div>
                            <label class="form-label">نوع التطوع <span style="color: #ef4444;">*</span></label>
                            <select name="volunteer_type" id="volunteer_type" class="form-input">
                                <option value="">اختر نوع التطوع</option>
                                <option value="individual" {{ old('volunteer_type') == 'individual' ? 'selected' : '' }}>فردي</option>
                                <option value="group" {{ old('volunteer_type') == 'group' ? 'selected' : '' }}>جماعي</option>
                            </select>
                            <div class="field-error"></div>
                        </div>
                        <div>
                            <label class="form-label">عدد المتطوعين <span style="color: #ef4444;">*</span></label>
                            <input type="number" name="seats" id="volunteer_seats" class="form-input" min="1" value="{{ old('seats', $opportunity->seats) }}" placeholder="مثال: 10">
                            <div class="field-error"></div>
                        </div>
                        <div>
                            <label class="form-label">الساعات اليومية</label>
                            <input type="number" name="daily_hours" id="volunteer_daily_hours" class="form-input" min="1" value="{{ old('daily_hours', $opportunity->daily_hours) }}" placeholder="مثال: 5">
                            <div class="field-error"></div>
                        </div>
                        <div>
                            <label class="form-label">إجمالي الساعات <span style="color: #ef4444;">*</span></label>
                            <input type="number" name="total_hours" id="volunteer_total_hours" class="form-input" min="1" value="{{ old('total_hours', $opportunity->total_hours) }}" placeholder="مثال: 40">
                            <div class="field-error"></div>
                        </div>
                    </div>
                </div>

                <!-- Training Fields Area -->
                <div id="training-fields" style="{{ old('type', $opportunity->type) == 'training' ? 'display: block;' : 'display: none;' }} margin-bottom: 2rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1rem;">
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.25rem;">
                        <div>
                            <label class="form-label">مجال التدريب <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="training_field" id="training_field" class="form-input" value="{{ old('training_field') }}" placeholder="مثال: التصميم الجرافيكي">
                            <div class="field-error"></div>
                        </div>
                        <div>
                            <label class="form-label">مدة التدريب <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="training_duration" id="training_duration" class="form-input" value="{{ old('training_duration') }}" placeholder="مثال: 3 أشهر">
                            <div class="field-error"></div>
                        </div>
                        <div>
                            <label class="form-label">هل التدريب معتمد؟ <span style="color: #ef4444;">*</span></label>
                            <select name="is_certified" id="is_certified" class="form-input">
                                <option value="">اختر</option>
                                <option value="yes" {{ old('is_certified') == 'yes' ? 'selected' : '' }}>نعم</option>
                                <option value="no" {{ old('is_certified') == 'no' ? 'selected' : '' }}>لا</option>
                            </select>
                            <div class="field-error"></div>
                        </div>
                        <div>
                            <label class="form-label">هل التدريب مدفوع؟ <span style="color: #ef4444;">*</span></label>
                            <select name="is_paid" id="is_paid" class="form-input">
                                <option value="">اختر</option>
                                <option value="yes" {{ old('is_paid') == 'yes' ? 'selected' : '' }}>نعم</option>
                                <option value="no" {{ old('is_paid') == 'no' ? 'selected' : '' }}>لا</option>
                            </select>
                            <div class="field-error"></div>
                        </div>
                        <div>
                            <label class="form-label">عدد المقاعد <span style="color: #ef4444;">*</span></label>
                            <input type="number" name="seats" id="training_seats" class="form-input" min="1" value="{{ old('seats', $opportunity->seats) }}" placeholder="مثال: 20">
                            <div class="field-error"></div>
                        </div>
                        <div>
                            <label class="form-label">إجمالي الساعات <span style="color: #ef4444;">*</span></label>
                            <input type="number" name="total_hours" id="training_total_hours" class="form-input" min="1" value="{{ old('total_hours', $opportunity->total_hours) }}" placeholder="مثال: 120">
                            <div class="field-error"></div>
                        </div>
                    </div>
                </div>

                <!-- Generic Requirements Grid -->
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.25rem;">
                    <div>
                        <label class="form-label">الفئة العمرية (اختياري)</label>
                        <input type="text" name="age_requirement" id="age_requirement" class="form-input" value="{{ old('age_requirement', $opportunity->age_requirement) }}" placeholder="مثال: 18 - 30 سنة">
                        <div class="field-error"></div>
                    </div>
                    <div>
                        <label class="form-label">المستوى التعليمي (اختياري)</label>
                        <select name="education_level" id="education_level" class="form-input">
                            <option value="">اختر المستوى التعليمي</option>
                            <option value="none" {{ old('education_level') == 'none' ? 'selected' : '' }}>غير محدد</option>
                            <option value="high_school" {{ old('education_level') == 'high_school' ? 'selected' : '' }}>ثانوي</option>
                            <option value="diploma" {{ old('education_level') == 'diploma' ? 'selected' : '' }}>دبلوم</option>
                            <option value="bachelor" {{ old('education_level') == 'bachelor' ? 'selected' : '' }}>بكالوريوس</option>
                            <option value="master" {{ old('education_level') == 'master' ? 'selected' : '' }}>ماجستير</option>
                        </select>
                        <div class="field-error"></div>
                    </div>
                    <div>
                        <label class="form-label">المهارات المطلوبة (اختياري)</label>
                        <input type="text" name="skills_requirement" id="skills_requirement" class="form-input" value="{{ old('skills_requirement', $opportunity->skills_requirement) }}" placeholder="مثال: إجادة اللغة الإنجليزية">
                        <div class="field-error"></div>
                    </div>
                    <div>
                        <label class="form-label">خبرة سابقة؟ (اختياري)</label>
                        <select name="previous_experience" id="previous_experience" class="form-input">
                            <option value="">اختر</option>
                            <option value="yes" {{ old('previous_experience') == 'yes' ? 'selected' : '' }}>نعم</option>
                            <option value="no" {{ old('previous_experience') == 'no' ? 'selected' : '' }}>لا</option>
                        </select>
                        <div class="field-error"></div>
                    </div>
                    <div style="grid-column: span 2;">
                        <label class="form-label">أهداف البرنامج</label>
                        <textarea name="objectives" id="objectives" rows="2" class="form-input" placeholder="ما هي أهداف هذه الفرصة؟">{{ old('objectives', $opportunity->objectives) }}</textarea>
                        <div class="field-error"></div>
                    </div>
                    <div style="grid-column: span 2;">
                        <label class="form-label">المهام المطلوبة</label>
                        <textarea name="tasks" id="tasks" rows="2" class="form-input" placeholder="ما هي الأنشطة اليومية المتوقعة؟">{{ old('tasks', $opportunity->tasks) }}</textarea>
                        <div class="field-error"></div>
                    </div>
                    <div id="training-outcomes-field" style="{{ old('type', $opportunity->type) == 'training' ? 'display: block;' : 'display: none;' }} grid-column: span 2;">
                        <label class="form-label">مخرجات التدريب (للمتدرب)</label>
                        <textarea name="training_outcomes" id="training_outcomes" rows="2" class="form-input" placeholder="ما المهارات التي سيكتسبها المتدرب؟">{{ old('training_outcomes', $opportunity->training_outcomes) }}</textarea>
                        <div class="field-error"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 3: التوثيق والارسال -->
        <div class="form-step" data-step="3" style="display: none;">
            <div class="card" style="padding: 2rem; border-radius: 1.5rem; border: none;">
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.25rem;">
                    <div>
                        <label class="form-label">هل توفر شهادات؟ <span style="color: #ef4444;">*</span></label>
                        <select name="provides_certificate" id="provides_certificate" class="form-input" data-required="true">
                            <option value="">اختر</option>
                            <option value="yes" {{ (old('provides_certificate') ?? ($opportunity->requires_certification ? 'yes' : '')) == 'yes' ? 'selected' : '' }}>نعم</option>
                            <option value="no" {{ (old('provides_certificate') ?? ($opportunity->requires_certification ? '' : 'no')) == 'no' ? 'selected' : '' }}>لا</option>
                        </select>
                        <div class="field-error"></div>
                    </div>

                    <div id="certificate-fields" style="{{ (old('provides_certificate') ?? ($opportunity->requires_certification ? 'yes' : '')) == 'yes' ? 'display: block;' : 'display: none;' }} grid-column: span 2;">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; background: #f8fafc; padding: 1.25rem; border-radius: 1rem;">
                            <div>
                                <label class="form-label">نوع الشهادة</label>
                                <input type="text" name="requires_certification" id="certificate_type" class="form-input" value="{{ old('requires_certification', $opportunity->requires_certification) }}" placeholder="مثال: شهادة تطوع معتمدة">
                                <div class="field-error"></div>
                            </div>
                            <div>
                                <label class="form-label">نموذج الشهادة (ملف جديد)</label>
                                <input type="file" name="certificate_file" id="certificate_file" accept=".pdf,image/*" class="form-input">
                                @if($opportunity->certificateFile)
                                    <div style="margin-top: 0.5rem;">
                                        <a href="{{ asset($opportunity->certificateFile->file_url) }}" target="_blank" style="font-size: 0.85rem; color: #4f46e5; text-decoration: none; font-weight: 600;">
                                            <i class="fas fa-file-pdf"></i> عرض النموذج الحالي
                                        </a>
                                    </div>
                                @endif
                                <div class="field-error"></div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="form-label">هل تحتاج رسالة تغطية؟ <span style="color: #ef4444;">*</span></label>
                        <select name="requires_cover_letter" id="requires_cover_letter" class="form-input" data-required="true">
                            <option value="">اختر</option>
                            <option value="yes" {{ old('requires_cover_letter') == 'yes' ? 'selected' : '' }}>نعم</option>
                            <option value="no" {{ old('requires_cover_letter') == 'no' ? 'selected' : '' }}>لا</option>
                        </select>
                        <div class="field-error"></div>
                    </div>

                    <div>
                        <label class="form-label">اسم المسؤول المباشر</label>
                        <input type="text" name="contact_name" id="contact_name" class="form-input" value="{{ old('contact_name', $opportunity->contact_name) }}" placeholder="أحمد علي">
                        <div class="field-error"></div>
                    </div>

                    <div style="grid-column: span 2; background: #eff6ff; padding: 1.5rem; border-radius: 1rem; border: 1px dashed #3b82f6;">
                         <p style="font-weight: 700; color: #1e40af; font-size: 0.95rem; margin-bottom: 0;">
                            <i class="fas fa-info-circle" style="margin-left: 0.5rem;"></i> سيتم إخطار الإدارة بالتعديلات الجديدة للمراجعة.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin: 2rem 0 4rem;">
            <button type="button" id="prev-btn" style="display: none; padding: 1rem 2.5rem; background: #f1f5f9; color: #64748b; border: none; border-radius: 1rem; font-weight: 700; cursor: pointer; transition: all 0.2s;">
                الرجوع للخلف
            </button>
            <div style="flex: 1;"></div>
            <div id="final-buttons" style="display: none; gap: 1rem;">
                <button type="submit" id="update-btn" style="padding: 1rem 3rem; background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; border: none; border-radius: 1rem; font-weight: 800; cursor: pointer; transition: all 0.2s; box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.3);">
                    حفظ التغييرات
                </button>
            </div>
            <button type="button" id="next-btn" style="padding: 1rem 4rem; background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; border: none; border-radius: 1rem; font-weight: 800; cursor: pointer; transition: all 0.2s; box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.3);">
                الخطوة التالية
            </button>
        </div>
    </form>
</div>

<style>
.form-label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 700;
    color: #475569;
}

.form-input {
    width: 100%;
    padding: 0.85rem;
    border: 1px solid #e2e8f0;
    border-radius: 0.75rem;
    outline: none;
    font-family: inherit;
    background: white;
    transition: border-color 0.2s;
}

.form-input:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-input.error {
    border-color: #ef4444;
}

.field-error {
    color: #ef4444;
    font-size: 0.875rem;
    margin-top: 0.5rem;
    font-weight: 600;
    min-height: 1.25rem;
}

.card {
    background: white;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}
</style>

<script>
// Subcategories data
const subcategories = {
    'بيئة': ['قص أشجار', 'تنظيف الشواطئ', 'التشجير والزراعة', 'التوعية البيئية', 'إعادة التدوير', 'حماية الحياة البرية', 'مراقبة بيئية', 'حملات النظافة العامة'],
    'تكنولوجيا': ['البرمجة وتطوير المواقع', 'تصميم الجرافيك', 'إدارة منصات التواصل', 'الدعم التقني', 'تحليل البيانات', 'الأمن السيبراني', 'الذكاء الاصطناعي'],
    'تعليم': ['محو الأمية', 'تدريس اللغات', 'دروس تقوية', 'التعليم المهني', 'تعليم الكبار', 'التعليم الإلكتروني'],
    'صحة': ['الإسعافات الأولية', 'التوعية الصحية', 'رعاية المرضى', 'الصحة النفسية', 'التغذية', 'اللياقة البدنية'],
    'مساعدة إنسانية': ['إغاثة الكوارث', 'مساعدة الأسر المحتاجة', 'رعاية الأيتام', 'دعم كبار السن', 'المساعدات الغذائية'],
    'ريادة أعمال': ['تطوير الأعمال', 'الاستشارات', 'التسويق', 'المبيعات', 'ريادة الأعمال الاجتماعية'],
    'رياضة': ['تدريب رياضي', 'تنظيم فعاليات رياضية', 'التحكيم', 'اللياقة البدنية', 'الرياضات الجماعية'],
    'فنون': ['الرسم والتلوين', 'الموسيقى', 'المسرح', 'الحرف اليدوية', 'التصوير الفوتوغرافي']
};

// Form state
let currentStep = 1;
const totalSteps = 3;

// Elements
const form = document.getElementById('opportunity-form');
const steps = document.querySelectorAll('.form-step');
const prevBtn = document.getElementById('prev-btn');
const nextBtn = document.getElementById('next-btn');
const finalButtons = document.getElementById('final-buttons');
const progressFill = document.getElementById('progress-fill');
const stepTitle = document.getElementById('step-title');
const stepDesc = document.getElementById('step-desc');

// Step metadata
const stepInfo = {
    1: { title: 'المعلومات الأساسية والتنفيذ', desc: 'أدخل البيانات الأساسية للفرصة ومكان وزمان التنفيذ' },
    2: { title: 'تفاصيل الفرصة والشروط', desc: 'حدد المقاعد، الساعات، والمتطلبات الخاصة بالمتقدمين' },
    3: { title: 'التوثيق والارسال', desc: 'أضف معلومات الشهادات وأنهِ عملية النشر' }
};

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    setupEventListeners();
    updateNavigationButtons();
});

function setupEventListeners() {
    // Category change
    document.getElementById('category').addEventListener('change', function() {
        const subSelect = document.getElementById('subcategory');
        const cat = this.value;
        subSelect.innerHTML = '<option value="">اختر الفئة الفرعية</option>';
        if (cat && subcategories[cat]) {
            subcategories[cat].forEach(sub => {
                const opt = document.createElement('option');
                opt.value = opt.textContent = sub;
                subSelect.appendChild(opt);
            });
        }
    });

    // Opportunity type change
    document.getElementById('type').addEventListener('change', function() {
        const vFields = document.getElementById('volunteering-fields');
        const tFields = document.getElementById('training-fields');
        const tOutField = document.getElementById('training-outcomes-field');
        if (this.value === 'volunteering') {
            vFields.style.display = 'block'; tFields.style.display = 'none'; tOutField.style.display = 'none';
        } else if (this.value === 'training') {
            vFields.style.display = 'none'; tFields.style.display = 'block'; tOutField.style.display = 'block';
        }
    });

    // Execution method change
    document.getElementById('execution_method').addEventListener('change', function() {
        const loc = document.getElementById('location-fields');
        loc.style.display = this.value === 'in_person' ? 'block' : 'none';
    });

    // Certificate provision
    document.getElementById('provides_certificate').addEventListener('change', function() {
        document.getElementById('certificate-fields').style.display = this.value === 'yes' ? 'block' : 'none';
    });

    // Navigation
    prevBtn.addEventListener('click', () => navigateStep(-1));
    nextBtn.addEventListener('click', () => navigateStep(1));
}

function navigateStep(dir) {
    if (dir === 1 && !validateCurrentStep()) return;
    currentStep += dir;
    updateStepDisplay();
    updateNavigationButtons();
    updateProgress();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function updateStepDisplay() {
    steps.forEach((s, i) => s.style.display = (i + 1 === currentStep) ? 'block' : 'none');
    document.querySelectorAll('.step-bubble').forEach((b, i) => {
        const num = i + 1;
        if (num < currentStep) {
            b.style.background = '#10b981'; b.style.borderColor = '#10b981'; b.style.color = 'white'; b.innerHTML = '✓';
        } else if (num === currentStep) {
            b.style.background = 'white'; b.style.borderColor = '#3b82f6'; b.style.color = '#3b82f6'; b.innerHTML = num;
            b.style.boxShadow = '0 4px 10px rgba(59, 130, 246, 0.2)';
        } else {
            b.style.background = 'white'; b.style.borderColor = '#f1f5f9'; b.style.color = '#94a3b8'; b.innerHTML = num;
            b.style.boxShadow = 'none';
        }
    });
    stepTitle.textContent = stepInfo[currentStep].title;
    stepDesc.textContent = stepInfo[currentStep].desc;
}

function updateNavigationButtons() {
    prevBtn.style.display = currentStep === 1 ? 'none' : 'block';
    nextBtn.style.display = currentStep === totalSteps ? 'none' : 'block';
    finalButtons.style.display = currentStep === totalSteps ? 'flex' : 'none';
}

function updateProgress() {
    progressFill.style.width = ((currentStep / totalSteps) * 100) + '%';
}

function validateCurrentStep() {
    const cur = document.querySelector(`.form-step[data-step="${currentStep}"]`);
    const reqs = cur.querySelectorAll('[data-required="true"]');
    let valid = true;
    reqs.forEach(f => {
        const err = f.parentElement.querySelector('.field-error');
        if (!f.value || f.value.trim() === '') {
            valid = false; f.classList.add('error'); err.textContent = 'هذا الحقل مطلوب';
        } else {
            f.classList.remove('error'); err.textContent = '';
        }
    });
    return valid;
}
</script>
@endsection

