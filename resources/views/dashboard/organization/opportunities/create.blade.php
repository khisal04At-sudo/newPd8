@extends('layouts.dashboard')

@section('title', 'نشر فرصة جديدة')

@section('content')
<div style="max-width: 1000px; margin: 0 auto;">
    <div style="margin-bottom: 2rem;">
        <h2 style="font-size: 1.8rem; font-weight: 800; color: #1e293b; margin: 0;">نشر فرصة جديدة</h2>
        <p style="color: #64748b; margin-top: 0.5rem;">املأ البيانات التالية بدقة لجذب أفضل المتطوعين والمتدربين.</p>
    </div>

    <!-- Progress Bar -->
    <div style="background: white; padding: 2rem; border-radius: 1.5rem; margin-bottom: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <div style="flex: 1; height: 4px; background: #e2e8f0; border-radius: 2px; margin: 0 0.5rem; position: relative;">
                <div id="progress-fill" style="height: 100%; background: linear-gradient(90deg, #4f46e5, #7c3aed); border-radius: 2px; width: 20%; transition: width 0.3s ease;"></div>
            </div>
        </div>
        <div style="text-align: center; color: #64748b; font-weight: 600;">
            <span id="step-indicator">الخطوة 1 من 5</span> - <span id="step-title">المعلومات الأساسية</span>
        </div>
        <div id="auto-save-indicator" style="text-align: center; color: #10b981; font-size: 0.875rem; margin-top: 0.5rem; opacity: 0; transition: opacity 0.3s;">
            ✓ تم الحفظ تلقائياً
        </div>
    </div>

    <!-- Error Display -->
    <div id="error-container" style="display: none; background: #fee2e2; color: #b91c1c; padding: 1.25rem; border-radius: 1rem; margin-bottom: 2rem;">
        <ul id="error-list" style="margin: 0; padding-right: 1.5rem; font-weight: 600;"></ul>
    </div>

    <form id="opportunity-form" action="{{ route('organization.opportunities.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="status" id="form-status" value="pending">

        <!-- Step 1: المعلومات الأساسية -->
        <div class="form-step active" data-step="1">
            <div class="card" style="padding: 2.5rem; border-radius: 1.5rem;">
                <h3 style="margin-bottom: 2rem; color: #4f46e5; border-bottom: 2px solid #f1f5f9; padding-bottom: 1rem;">1. المعلومات الأساسية</h3>
                
                <div style="display: grid; gap: 1.5rem;">
                    <div>
                        <label class="form-label">عنوان الفرصة <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="title" id="title" class="form-input" placeholder="مثال: مدرب جرافيك للناشئين" data-required="true">
                        <div class="field-error"></div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div>
                            <label class="form-label">نوع الفرصة <span style="color: #ef4444;">*</span></label>
                            <select name="type" id="type" class="form-input" data-required="true">
                                <option value="">اختر نوع الفرصة</option>
                                <option value="volunteering">تطوعية</option>
                                <option value="training">تدريبية</option>
                            </select>
                            <div class="field-error"></div>
                        </div>

                        <div>
                            <label class="form-label">الفئة <span style="color: #ef4444;">*</span></label>
                            <select name="category" id="category" class="form-input" data-required="true">
                                <option value="">اختر الفئة</option>
                                <option value="environment">بيئة</option>
                                <option value="technology">تكنولوجيا</option>
                                <option value="education">تعليم</option>
                                <option value="health">صحة</option>
                                <option value="help">مساعدة إنسانية</option>
                                <option value="entrepreneurship">ريادة أعمال</option>
                                <option value="sports">رياضة</option>
                                <option value="arts">فنون</option>
                            </select>
                            <div class="field-error"></div>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div>
                            <label class="form-label">الفئة الفرعية</label>
                            <select name="subcategory" id="subcategory" class="form-input" disabled>
                                <option value="">اختر الفئة أولاً</option>
                            </select>
                            <div class="field-error"></div>
                        </div>

                        <div>
                            <label class="form-label">نوع النشاط</label>
                            <select name="activity_type" id="activity_type" class="form-input">
                                <option value="">اختر نوع النشاط</option>
                                <option value="cleaning">تنظيف</option>
                                <option value="design">تصميم</option>
                                <option value="teaching">تدريس</option>
                                <option value="programming">برمجة</option>
                                <option value="awareness">توعية</option>
                                <option value="support">دعم</option>
                                <option value="organization">تنظيم</option>
                                <option value="other">أخرى</option>
                            </select>
                            <div class="field-error"></div>
                        </div>
                    </div>

                    <div>
                        <label class="form-label">وصف الفرصة <span style="color: #ef4444;">*</span></label>
                        <textarea name="description" id="description" rows="4" class="form-input" placeholder="مقدمة بسيطة عن الفرصة..." data-required="true"></textarea>
                        <div class="field-error"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 2: تفاصيل التنفيذ -->
        <div class="form-step" data-step="2" style="display: none;">
            <div class="card" style="padding: 2.5rem; border-radius: 1.5rem;">
                <h3 style="margin-bottom: 2rem; color: #4f46e5; border-bottom: 2px solid #f1f5f9; padding-bottom: 1rem;">2. تفاصيل التنفيذ</h3>
                
                <div style="display: grid; gap: 1.5rem;">
                    <div>
                        <label class="form-label">طريقة التنفيذ <span style="color: #ef4444;">*</span></label>
                        <select name="execution_method" id="execution_method" class="form-input" data-required="true">
                            <option value="">اختر طريقة التنفيذ</option>
                            <option value="in_person">حضوري</option>
                            <option value="remote">عن بعد</option>
                        </select>
                        <div class="field-error"></div>
                    </div>

                    <div id="location-fields" style="display: none;">
                        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 1.5rem;">
                            <div>
                                <label class="form-label">المدينة <span style="color: #ef4444;">*</span></label>
                                <select name="city_id" id="city_id" class="form-input">
                                    <option value="">اختر المدينة</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}">{{ $city->name }}</option>
                                    @endforeach
                                </select>
                                <div class="field-error"></div>
                            </div>

                            <div>
                                <label class="form-label">العنوان/الموقع (اختياري)</label>
                                <input type="text" name="address" id="address" class="form-input" placeholder="الحي، اسم المبنى، الطابق">
                                <div class="field-error"></div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="form-label">أدوات التواصل <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="contact_info" id="contact_info" class="form-input" placeholder="مثال: البريد الإلكتروني، رقم الهاتف، رابط المنصة" data-required="true">
                        <div class="field-error"></div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem;">
                        <div>
                            <label class="form-label">تاريخ البداية <span style="color: #ef4444;">*</span></label>
                            <input type="date" name="start_date" id="start_date" class="form-input" data-required="true">
                            <div class="field-error"></div>
                        </div>

                        <div>
                            <label class="form-label">تاريخ النهاية <span style="color: #ef4444;">*</span></label>
                            <input type="date" name="end_date" id="end_date" class="form-input" data-required="true">
                            <div class="field-error"></div>
                        </div>

                        <div>
                            <label class="form-label">تاريخ إغلاق التقديم <span style="color: #ef4444;">*</span></label>
                            <input type="date" name="application_deadline" id="application_deadline" class="form-input" data-required="true">
                            <div class="field-error"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 3: معلومات خاصة بنوع الفرصة -->
        <div class="form-step" data-step="3" style="display: none;">
            <div class="card" style="padding: 2.5rem; border-radius: 1.5rem;">
                <h3 style="margin-bottom: 2rem; color: #4f46e5; border-bottom: 2px solid #f1f5f9; padding-bottom: 1rem;">3. معلومات خاصة بنوع الفرصة</h3>
                
                <!-- Volunteering Fields -->
                <div id="volunteering-fields" style="display: none;">
                    <div style="display: grid; gap: 1.5rem;">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                            <div>
                                <label class="form-label">نوع التطوع <span style="color: #ef4444;">*</span></label>
                                <select name="volunteer_type" id="volunteer_type" class="form-input">
                                    <option value="">اختر نوع التطوع</option>
                                    <option value="individual">فردي</option>
                                    <option value="group">جماعي</option>
                                </select>
                                <div class="field-error"></div>
                            </div>

                            <div>
                                <label class="form-label">عدد المتطوعين المطلوب <span style="color: #ef4444;">*</span></label>
                                <input type="number" name="seats" id="volunteer_seats" class="form-input" min="1" placeholder="مثال: 10">
                                <div class="field-error"></div>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                            <div>
                                <label class="form-label">عدد الساعات اليومية</label>
                                <input type="number" name="daily_hours" id="volunteer_daily_hours" class="form-input" min="1" placeholder="مثال: 5">
                                <div class="field-error"></div>
                            </div>

                            <div>
                                <label class="form-label">إجمالي الساعات <span style="color: #ef4444;">*</span></label>
                                <input type="number" name="total_hours" id="volunteer_total_hours" class="form-input" min="1" placeholder="مثال: 40">
                                <div class="field-error"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Training Fields -->
                <div id="training-fields" style="display: none;">
                    <div style="display: grid; gap: 1.5rem;">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                            <div>
                                <label class="form-label">مجال التدريب <span style="color: #ef4444;">*</span></label>
                                <input type="text" name="training_field" id="training_field" class="form-input" placeholder="مثال: التصميم الجرافيكي">
                                <div class="field-error"></div>
                            </div>

                            <div>
                                <label class="form-label">مدة التدريب <span style="color: #ef4444;">*</span></label>
                                <input type="text" name="training_duration" id="training_duration" class="form-input" placeholder="مثال: 3 أشهر">
                                <div class="field-error"></div>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                            <div>
                                <label class="form-label">هل التدريب معتمد؟ <span style="color: #ef4444;">*</span></label>
                                <select name="is_certified" id="is_certified" class="form-input">
                                    <option value="">اختر</option>
                                    <option value="yes">نعم</option>
                                    <option value="no">لا</option>
                                </select>
                                <div class="field-error"></div>
                            </div>

                            <div>
                                <label class="form-label">هل التدريب مدفوع؟ <span style="color: #ef4444;">*</span></label>
                                <select name="is_paid" id="is_paid" class="form-input">
                                    <option value="">اختر</option>
                                    <option value="yes">نعم</option>
                                    <option value="no">لا</option>
                                </select>
                                <div class="field-error"></div>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem;">
                            <div>
                                <label class="form-label">عدد المقاعد <span style="color: #ef4444;">*</span></label>
                                <input type="number" name="seats" id="training_seats" class="form-input" min="1" placeholder="مثال: 20">
                                <div class="field-error"></div>
                            </div>

                            <div>
                                <label class="form-label">عدد الساعات اليومية</label>
                                <input type="number" name="daily_hours" id="training_daily_hours" class="form-input" min="1" placeholder="مثال: 4">
                                <div class="field-error"></div>
                            </div>

                            <div>
                                <label class="form-label">إجمالي الساعات <span style="color: #ef4444;">*</span></label>
                                <input type="number" name="total_hours" id="training_total_hours" class="form-input" min="1" placeholder="مثال: 120">
                                <div class="field-error"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 4: شروط ومواصفات المتقدمين -->
        <div class="form-step" data-step="4" style="display: none;">
            <div class="card" style="padding: 2.5rem; border-radius: 1.5rem;">
                <h3 style="margin-bottom: 2rem; color: #4f46e5; border-bottom: 2px solid #f1f5f9; padding-bottom: 1rem;">4. شروط ومواصفات المتقدمين (اختياري)</h3>
                
                <div style="display: grid; gap: 1.5rem;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div>
                            <label class="form-label">الفئة العمرية</label>
                            <input type="text" name="age_requirement" id="age_requirement" class="form-input" placeholder="مثال: 18 - 30 سنة">
                            <div class="field-error"></div>
                        </div>

                        <div>
                            <label class="form-label">المستوى التعليمي</label>
                            <select name="education_level" id="education_level" class="form-input">
                                <option value="">اختر المستوى التعليمي</option>
                                <option value="none">غير محدد</option>
                                <option value="high_school">ثانوي</option>
                                <option value="diploma">دبلوم</option>
                                <option value="bachelor">بكالوريوس</option>
                                <option value="master">ماجستير</option>
                                <option value="phd">دكتوراه</option>
                            </select>
                            <div class="field-error"></div>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div>
                            <label class="form-label">المهارات المطلوبة</label>
                            <input type="text" name="skills_requirement" id="skills_requirement" class="form-input" placeholder="مثال: إجادة اللغة الإنجليزية">
                            <div class="field-error"></div>
                        </div>

                        <div>
                            <label class="form-label">خبرة سابقة</label>
                            <select name="previous_experience" id="previous_experience" class="form-input">
                                <option value="">اختر</option>
                                <option value="yes">نعم</option>
                                <option value="no">لا</option>
                            </select>
                            <div class="field-error"></div>
                        </div>
                    </div>

                    <div>
                        <label class="form-label">متطلبات إضافية</label>
                        <textarea name="additional_requirements" id="additional_requirements" rows="3" class="form-input" placeholder="أي متطلبات أخرى تود ذكرها..."></textarea>
                        <div class="field-error"></div>
                    </div>

                    <div>
                        <label class="form-label">أهداف البرنامج</label>
                        <textarea name="objectives" id="objectives" rows="3" class="form-input" placeholder="ما الذي تسعى المؤسسة لتحقيقه؟"></textarea>
                        <div class="field-error"></div>
                    </div>

                    <div>
                        <label class="form-label">المهام المطلوبة</label>
                        <textarea name="tasks" id="tasks" rows="3" class="form-input" placeholder="ما هي الأنشطة اليومية المتوقعة؟"></textarea>
                        <div class="field-error"></div>
                    </div>

                    <div id="training-outcomes-field" style="display: none;">
                        <label class="form-label">مخرجات التدريب (للمتدرب)</label>
                        <textarea name="training_outcomes" id="training_outcomes" rows="3" class="form-input" placeholder="ما المهارات التي سيكتسبها المتدرب؟"></textarea>
                        <div class="field-error"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 5: الشهادات والتواصل -->
        <div class="form-step" data-step="5" style="display: none;">
            <div class="card" style="padding: 2.5rem; border-radius: 1.5rem;">
                <h3 style="margin-bottom: 2rem; color: #4f46e5; border-bottom: 2px solid #f1f5f9; padding-bottom: 1rem;">5. الشهادات والتواصل</h3>
                
                <div style="display: grid; gap: 1.5rem;">
                    <div>
                        <label class="form-label">هل توفر شهادات؟ <span style="color: #ef4444;">*</span></label>
                        <select name="provides_certificate" id="provides_certificate" class="form-input" data-required="true">
                            <option value="">اختر</option>
                            <option value="yes">نعم</option>
                            <option value="no">لا</option>
                        </select>
                        <div class="field-error"></div>
                    </div>

                    <div id="certificate-fields" style="display: none;">
                        <div style="display: grid; gap: 1.5rem;">
                            <div>
                                <label class="form-label">نوع الشهادة</label>
                                <input type="text" name="requires_certification" id="certificate_type" class="form-input" placeholder="مثال: شهادة تطوع معتمدة">
                                <div class="field-error"></div>
                            </div>

                            <div>
                                <label class="form-label">نموذج الشهادة (PDF أو صورة)</label>
                                <input type="file" name="certificate_file" id="certificate_file" accept=".pdf,image/*" class="form-input" style="border: 2px dashed #e2e8f0;">
                                <p style="font-size: 0.75rem; color: #64748b; margin-top: 0.5rem;">أو استخدام نموذج المنصة الافتراضي</p>
                                <div class="field-error"></div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="form-label">هل تحتاج رسالة تغطية؟ <span style="color: #ef4444;">*</span></label>
                        <select name="requires_cover_letter" id="requires_cover_letter" class="form-input" data-required="true">
                            <option value="">اختر</option>
                            <option value="yes">نعم</option>
                            <option value="no">لا</option>
                        </select>
                        <div class="field-error"></div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div>
                            <label class="form-label">اسم المسؤول</label>
                            <input type="text" name="contact_name" id="contact_name" class="form-input" placeholder="مثال: أحمد علي">
                            <div class="field-error"></div>
                        </div>

                        <div>
                            <label class="form-label">بيانات التواصل الإضافية</label>
                            <input type="text" name="additional_contact" id="additional_contact" class="form-input" placeholder="رقم هاتف أو بريد إلكتروني إضافي">
                            <div class="field-error"></div>
                        </div>
                    </div>

                    <div style="background: #f8fafc; padding: 1.5rem; border-radius: 1rem; margin-top: 1rem;">
                        <label style="display: flex; align-items: start; gap: 1rem; cursor: pointer;">
                            <input type="checkbox" name="agree_terms" id="agree_terms" data-required="true" style="margin-top: 0.25rem;">
                            <span style="font-weight: 600; color: #475569;">
                                أوافق على شروط المنصة وأتعهد بأن جميع المعلومات المقدمة صحيحة ودقيقة <span style="color: #ef4444;">*</span>
                            </span>
                        </label>
                        <div class="field-error"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin: 2rem 0 4rem;">
            <button type="button" id="prev-btn" style="display: none; padding: 1rem 2.5rem; background: #e2e8f0; color: #475569; border: none; border-radius: 1rem; font-weight: 700; cursor: pointer; transition: all 0.2s;">
                السابق
            </button>
            
            <div style="flex: 1;"></div>

            <div id="final-buttons" style="display: none; gap: 1rem;">
                <a href="{{ route('organization.opportunities.index') }}" style="padding: 1rem 2rem; background: #e2e8f0; color: #475569; border-radius: 1rem; font-weight: 700; text-decoration: none; transition: all 0.2s;">
                    إلغاء
                </a>
                <button type="button" id="draft-btn" style="padding: 1rem 2rem; background: #64748b; color: white; border: none; border-radius: 1rem; font-weight: 700; cursor: pointer; transition: all 0.2s;">
                    حفظ كمسودة
                </button>
                <button type="submit" id="publish-btn" style="padding: 1rem 2.5rem; background: #4f46e5; color: white; border: none; border-radius: 1rem; font-weight: 800; cursor: pointer; transition: all 0.2s; box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.4);">
                    نشر الفرصة
                </button>
            </div>

            <button type="button" id="next-btn" style="padding: 1rem 4rem; background: #4f46e5; color: white; border: none; border-radius: 1rem; font-weight: 800; cursor: pointer; transition: all 0.2s; box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.4);">
                التالي
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
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
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
    environment: [
        'قص أشجار',
        'تنظيف الشواطئ',
        'التشجير والزراعة',
        'التوعية البيئية',
        'إعادة التدوير',
        'حماية الحياة البرية',
        'مراقبة بيئية',
        'حملات النظافة العامة'
    ],
    technology: [
        'البرمجة وتطوير المواقع',
        'تصميم الجرافيك',
        'إدارة منصات التواصل',
        'الدعم التقني',
        'تحليل البيانات',
        'الأمن السيبراني',
        'الذكاء الاصطناعي'
    ],
    education: [
        'محو الأمية',
        'تدريس اللغات',
        'دروس تقوية',
        'التعليم المهني',
        'تعليم الكبار',
        'التعليم الإلكتروني'
    ],
    health: [
        'الإسعافات الأولية',
        'التوعية الصحية',
        'رعاية المرضى',
        'الصحة النفسية',
        'التغذية',
        'اللياقة البدنية'
    ],
    help: [
        'إغاثة الكوارث',
        'مساعدة الأسر المحتاجة',
        'رعاية الأيتام',
        'دعم كبار السن',
        'المساعدات الغذائية'
    ],
    entrepreneurship: [
        'تطوير الأعمال',
        'الاستشارات',
        'التسويق',
        'المبيعات',
        'ريادة الأعمال الاجتماعية'
    ],
    sports: [
        'تدريب رياضي',
        'تنظيم فعاليات رياضية',
        'التحكيم',
        'اللياقة البدنية',
        'الرياضات الجماعية'
    ],
    arts: [
        'الرسم والتلوين',
        'الموسيقى',
        'المسرح',
        'الحرف اليدوية',
        'التصوير الفوتوغرافي'
    ]
};

// Form state
let currentStep = 1;
const totalSteps = 5;
let formData = {};

// Elements
const form = document.getElementById('opportunity-form');
const steps = document.querySelectorAll('.form-step');
const prevBtn = document.getElementById('prev-btn');
const nextBtn = document.getElementById('next-btn');
const finalButtons = document.getElementById('final-buttons');
const draftBtn = document.getElementById('draft-btn');
const publishBtn = document.getElementById('publish-btn');
const progressFill = document.getElementById('progress-fill');
const stepIndicator = document.getElementById('step-indicator');
const stepTitle = document.getElementById('step-title');
const errorContainer = document.getElementById('error-container');
const errorList = document.getElementById('error-list');
const autoSaveIndicator = document.getElementById('auto-save-indicator');

// Step titles
const stepTitles = {
    1: 'المعلومات الأساسية',
    2: 'تفاصيل التنفيذ',
    3: 'معلومات خاصة بنوع الفرصة',
    4: 'شروط ومواصفات المتقدمين',
    5: 'الشهادات والتواصل'
};

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    loadFromLocalStorage();
    setupEventListeners();
    updateNavigationButtons();
});

// Setup event listeners
function setupEventListeners() {
    // Category change - update subcategories
    document.getElementById('category').addEventListener('change', function() {
        const subcategorySelect = document.getElementById('subcategory');
        const category = this.value;
        
        subcategorySelect.innerHTML = '<option value="">اختر الفئة الفرعية</option>';
        
        if (category && subcategories[category]) {
            subcategorySelect.disabled = false;
            subcategories[category].forEach(sub => {
                const option = document.createElement('option');
                option.value = sub;
                option.textContent = sub;
                subcategorySelect.appendChild(option);
            });
        } else {
            subcategorySelect.disabled = true;
        }
    });

    // Opportunity type change
    document.getElementById('type').addEventListener('change', function() {
        const volunteeringFields = document.getElementById('volunteering-fields');
        const trainingFields = document.getElementById('training-fields');
        const trainingOutcomesField = document.getElementById('training-outcomes-field');
        
        if (this.value === 'volunteering') {
            volunteeringFields.style.display = 'block';
            trainingFields.style.display = 'none';
            trainingOutcomesField.style.display = 'none';
        } else if (this.value === 'training') {
            volunteeringFields.style.display = 'none';
            trainingFields.style.display = 'block';
            trainingOutcomesField.style.display = 'block';
        }
    });

    // Execution method change
    document.getElementById('execution_method').addEventListener('change', function() {
        const locationFields = document.getElementById('location-fields');
        const cityField = document.getElementById('city_id');
        
        if (this.value === 'in_person') {
            locationFields.style.display = 'block';
            cityField.setAttribute('data-required', 'true');
        } else {
            locationFields.style.display = 'none';
            cityField.removeAttribute('data-required');
        }
    });

    // Certificate provision change
    document.getElementById('provides_certificate').addEventListener('change', function() {
        const certificateFields = document.getElementById('certificate-fields');
        certificateFields.style.display = this.value === 'yes' ? 'block' : 'none';
    });

    // Navigation buttons
    prevBtn.addEventListener('click', () => navigateStep(-1));
    nextBtn.addEventListener('click', () => navigateStep(1));
    
    // Draft button
    draftBtn.addEventListener('click', function() {
        document.getElementById('form-status').value = 'draft';
        form.submit();
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        if (!validateCurrentStep()) {
            e.preventDefault();
        } else {
            clearLocalStorage();
        }
    });

    // Auto-save
    setInterval(autoSave, 30000); // Every 30 seconds

    // Save on input change
    form.addEventListener('input', debounce(autoSave, 2000));
}

// Navigate between steps
function navigateStep(direction) {
    if (direction === 1 && !validateCurrentStep()) {
        return;
    }

    hideErrors();
    currentStep += direction;
    
    if (currentStep < 1) currentStep = 1;
    if (currentStep > totalSteps) currentStep = totalSteps;

    updateStepDisplay();
    updateNavigationButtons();
    updateProgress();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Update step display
function updateStepDisplay() {
    steps.forEach((step, index) => {
        if (index + 1 === currentStep) {
            step.style.display = 'block';
            setTimeout(() => step.classList.add('active'), 10);
        } else {
            step.style.display = 'none';
            step.classList.remove('active');
        }
    });
    
    stepIndicator.textContent = `الخطوة ${currentStep} من ${totalSteps}`;
    stepTitle.textContent = stepTitles[currentStep];
}

// Update navigation buttons
function updateNavigationButtons() {
    prevBtn.style.display = currentStep === 1 ? 'none' : 'block';
    
    if (currentStep === totalSteps) {
        nextBtn.style.display = 'none';
        finalButtons.style.display = 'flex';
    } else {
        nextBtn.style.display = 'block';
        finalButtons.style.display = 'none';
    }
}

// Update progress bar
function updateProgress() {
    const progress = (currentStep / totalSteps) * 100;
    progressFill.style.width = progress + '%';
}

// Validate current step
function validateCurrentStep() {
    const currentStepElement = document.querySelector(`.form-step[data-step="${currentStep}"]`);
    const requiredFields = currentStepElement.querySelectorAll('[data-required="true"]');
    let isValid = true;
    const errors = [];

    requiredFields.forEach(field => {
        const fieldError = field.parentElement.querySelector('.field-error');
        const label = field.parentElement.querySelector('.form-label').textContent.replace(' *', '');
        
        if (!field.value || field.value.trim() === '') {
            isValid = false;
            field.classList.add('error');
            fieldError.textContent = `${label} مطلوب`;
            errors.push(`${label} مطلوب`);
        } else if (field.type === 'checkbox' && !field.checked) {
            isValid = false;
            field.classList.add('error');
            fieldError.textContent = `يجب الموافقة على الشروط`;
            errors.push(`يجب الموافقة على الشروط`);
        } else {
            field.classList.remove('error');
            fieldError.textContent = '';
        }
    });

    // Date validation for step 2
    if (currentStep === 2) {
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;
        const deadline = document.getElementById('application_deadline').value;

        if (startDate && endDate && new Date(endDate) < new Date(startDate)) {
            isValid = false;
            errors.push('تاريخ النهاية يجب أن يكون بعد تاريخ البداية');
        }

        if (startDate && deadline && new Date(deadline) > new Date(startDate)) {
            isValid = false;
            errors.push('تاريخ إغلاق التقديم يجب أن يكون قبل تاريخ البداية');
        }
    }

    if (!isValid) {
        showErrors(errors);
    } else {
        hideErrors();
    }

    return isValid;
}

// Show errors
function showErrors(errors) {
    errorList.innerHTML = '';
    errors.forEach(error => {
        const li = document.createElement('li');
        li.textContent = error;
        errorList.appendChild(li);
    });
    errorContainer.style.display = 'block';
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Hide errors
function hideErrors() {
    errorContainer.style.display = 'none';
    errorList.innerHTML = '';
}

// Auto-save functionality
function autoSave() {
    const formDataObj = new FormData(form);
    const data = {};
    
    for (let [key, value] of formDataObj.entries()) {
        if (key !== '_token' && key !== 'status') {
            data[key] = value;
        }
    }
    
    localStorage.setItem('opportunity_draft', JSON.stringify(data));
    showAutoSaveIndicator();
}

// Show auto-save indicator
function showAutoSaveIndicator() {
    autoSaveIndicator.style.opacity = '1';
    setTimeout(() => {
        autoSaveIndicator.style.opacity = '0';
    }, 2000);
}

// Load from localStorage
function loadFromLocalStorage() {
    const saved = localStorage.getItem('opportunity_draft');
    if (saved) {
        const data = JSON.parse(saved);
        
        if (confirm('تم العثور على بيانات محفوظة مسبقاً. هل تريد استعادتها؟')) {
            for (let key in data) {
                const field = document.querySelector(`[name="${key}"]`);
                if (field) {
                    if (field.type === 'checkbox') {
                        field.checked = data[key] === 'on' || data[key] === '1';
                    } else {
                        field.value = data[key];
                    }
                    
                    // Trigger change events for conditional fields
                    field.dispatchEvent(new Event('change'));
                }
            }
        } else {
            clearLocalStorage();
        }
    }
}

// Clear localStorage
function clearLocalStorage() {
    localStorage.removeItem('opportunity_draft');
}

// Debounce function
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}
</script>

@endsection
