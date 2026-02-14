@extends('layouts.dashboard')

@section('title', 'نشر فرصة جديدة')

@section('content')
<div style="max-width: 1000px; margin: 0 auto;">
    <div style="margin-bottom: 2rem;">
        <h2 style="font-size: 1.8rem; font-weight: 800; color: #1e293b; margin: 0;">نشر فرصة جديدة</h2>
        <p style="color: #64748b; margin-top: 0.5rem;">املأ البيانات التالية بدقة لجذب أفضل المتطوعين والمتدربين.</p>
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
        <div id="auto-save-indicator" style="position: absolute; top: 1rem; left: 2rem; color: #10b981; font-size: 0.75rem; font-weight: 700; opacity: 0; transition: opacity 0.3s;">
             تم الحفظ تلقائياً ✓
        </div>
    </div>

    <!-- Error Display -->
    <div id="error-container" style="{{ $errors->any() ? 'display: block;' : 'display: none;' }} background: #fee2e2; color: #b91c1c; padding: 1.25rem; border-radius: 1rem; margin-bottom: 2rem;">
        <ul id="error-list" style="margin: 0; padding-right: 1.5rem; font-weight: 600;">
            @if($errors->any())
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            @endif
        </ul>
    </div>

    <form id="opportunity-form" action="{{ route('organization.opportunities.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="status" id="form-status" value="pending">

        <!-- Step 1: المعلومات الأساسية والتنفيذ -->
        <div class="form-step active" data-step="1">
            <div class="card" style="padding: 2rem; border-radius: 1.5rem; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.25rem;">
                    <div style="grid-column: span 2;">
                        <label class="form-label">عنوان الفرصة <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="title" id="title" class="form-input" placeholder="مثال: مدرب جرافيك للناشئين" data-required="true">
                        <div class="field-error"></div>
                    </div>

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
                            <option value="بيئة">بيئة</option>
                            <option value="تكنولوجيا">تكنولوجيا</option>
                            <option value="تعليم">تعليم</option>
                            <option value="صحة">صحة</option>
                            <option value="مساعدة إنسانية">مساعدة إنسانية</option>
                            <option value="ريادة أعمال">ريادة أعمال</option>
                            <option value="رياضة">رياضة</option>
                            <option value="فنون">فنون</option>
                        </select>
                        <div class="field-error"></div>
                    </div>

                    <div>
                        <label class="form-label">الفئة الفرعية</label>
                        <select name="subcategory" id="subcategory" class="form-input" disabled>
                            <option value="">اختر الفئة أولاً</option>
                        </select>
                        <div class="field-error"></div>
                    </div>

                    <div>
                        <label class="form-label">طريقة التنفيذ <span style="color: #ef4444;">*</span></label>
                        <select name="execution_method" id="execution_method" class="form-input" data-required="true">
                            <option value="">اختر طريقة التنفيذ</option>
                            <option value="in_person">حضوري</option>
                            <option value="remote">عن بعد</option>
                        </select>
                        <div class="field-error"></div>
                    </div>

                    <div id="location-fields" style="display: none; grid-column: span 2;">
                        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 1.25rem;">
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

                    <div style="grid-column: span 2;">
                        <label class="form-label">أدوات التواصل <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="contact_info" id="contact_info" class="form-input" placeholder="مثال: البريد الإلكتروني، رقم الهاتف، رابط المنصة" data-required="true">
                        <div class="field-error"></div>
                    </div>

                    <div style="grid-column: span 2; display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.25rem;">
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
                            <label class="form-label">إغلاق التقديم <span style="color: #ef4444;">*</span></label>
                            <input type="date" name="application_deadline" id="application_deadline" class="form-input" data-required="true">
                            <div class="field-error"></div>
                        </div>
                    </div>

                    <div style="grid-column: span 2;">
                        <label class="form-label">وصف الفرصة <span style="color: #ef4444;">*</span></label>
                        <textarea name="description" id="description" rows="3" class="form-input" placeholder="مقدمة بسيطة عن الفرصة..." data-required="true"></textarea>
                        <div class="field-error"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 2: تفاصيل الفرصة والشروط -->
        <div class="form-step" data-step="2" style="display: none;">
            <div class="card" style="padding: 2rem; border-radius: 1.5rem; border: none;">
                <!-- Volunteering Fields Area -->
                <div id="volunteering-fields" style="display: none; margin-bottom: 2rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1rem;">
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.25rem;">
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
                            <label class="form-label">عدد المتطوعين <span style="color: #ef4444;">*</span></label>
                            <input type="number" name="seats" id="volunteer_seats" class="form-input" min="1" placeholder="مثال: 10">
                            <div class="field-error"></div>
                        </div>
                        <div>
                            <label class="form-label">الساعات اليومية</label>
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

                <!-- Training Fields Area -->
                <div id="training-fields" style="display: none; margin-bottom: 2rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1rem;">
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.25rem;">
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
                        <div>
                            <label class="form-label">عدد المقاعد <span style="color: #ef4444;">*</span></label>
                            <input type="number" name="seats" id="training_seats" class="form-input" min="1" placeholder="مثال: 20">
                            <div class="field-error"></div>
                        </div>
                        <div>
                            <label class="form-label">إجمالي الساعات <span style="color: #ef4444;">*</span></label>
                            <input type="number" name="total_hours" id="training_total_hours" class="form-input" min="1" placeholder="مثال: 120">
                            <div class="field-error"></div>
                        </div>
                    </div>
                </div>

                <!-- Generic Requirements Grid -->
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.25rem;">
                    <div>
                        <label class="form-label">الفئة العمرية (اختياري)</label>
                        <input type="text" name="age_requirement" id="age_requirement" class="form-input" placeholder="مثال: 18 - 30 سنة">
                        <div class="field-error"></div>
                    </div>
                    <div>
                        <label class="form-label">المستوى التعليمي (اختياري)</label>
                        <select name="education_level" id="education_level" class="form-input">
                            <option value="">اختر المستوى التعليمي</option>
                            <option value="none">غير محدد</option>
                            <option value="high_school">ثانوي</option>
                            <option value="diploma">دبلوم</option>
                            <option value="bachelor">بكالوريوس</option>
                            <option value="master">ماجستير</option>
                        </select>
                        <div class="field-error"></div>
                    </div>
                    <div>
                        <label class="form-label">المهارات المطلوبة (اختياري)</label>
                        <input type="text" name="skills_requirement" id="skills_requirement" class="form-input" placeholder="مثال: إجادة اللغة الإنجليزية">
                        <div class="field-error"></div>
                    </div>
                    <div>
                        <label class="form-label">خبرة سابقة؟ (اختياري)</label>
                        <select name="previous_experience" id="previous_experience" class="form-input">
                            <option value="">اختر</option>
                            <option value="yes">نعم</option>
                            <option value="no">لا</option>
                        </select>
                        <div class="field-error"></div>
                    </div>
                    <div style="grid-column: span 2;">
                        <label class="form-label">المهام المطلوبة</label>
                        <textarea name="tasks" id="tasks" rows="2" class="form-input" placeholder="ما هي الأنشطة اليومية المتوقعة؟"></textarea>
                        <div class="field-error"></div>
                    </div>
                    <div id="training-outcomes-field" style="display: none; grid-column: span 2;">
                        <label class="form-label">مخرجات التدريب (للمتدرب)</label>
                        <textarea name="training_outcomes" id="training_outcomes" rows="2" class="form-input" placeholder="ما المهارات التي سيكتسبها المتدرب؟"></textarea>
                        <div class="field-error"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 3: التوثيق والارسال -->
        <div class="form-step" data-step="3" style="display: none;">
            <div class="card" style="padding: 2rem; border-radius: 1.5rem; border: none;">
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.25rem;">
                    <!-- Removed certificate fields as they are now automated -->


                    <div>
                        <label class="form-label">هل تحتاج رسالة تغطية؟ <span style="color: #ef4444;">*</span></label>
                        <select name="requires_cover_letter" id="requires_cover_letter" class="form-input" data-required="true">
                            <option value="">اختر</option>
                            <option value="yes">نعم</option>
                            <option value="no">لا</option>
                        </select>
                        <div class="field-error"></div>
                    </div>

                    <div>
                        <label class="form-label">اسم المسؤول المباشر</label>
                        <input type="text" name="contact_name" id="contact_name" class="form-input" placeholder="أحمد علي">
                        <div class="field-error"></div>
                    </div>

                    <div style="grid-column: span 2; background: #f0fdf4; padding: 1.5rem; border-radius: 1rem; border: 1px dashed #10b981;">
                        <label style="display: flex; align-items: start; gap: 1rem; cursor: pointer;">
                            <input type="checkbox" name="agree_terms" id="agree_terms" data-required="true" style="margin-top: 0.25rem; width: 1.2rem; height: 1.2rem;">
                            <span style="font-weight: 700; color: #065f46; font-size: 0.95rem;">
                                أقر بأن المؤسسة تلتزم بكافة ضوابط العمل التطوعي/التدريبي، وأن جميع البيانات المدخلة صحيحة وتقع تحت مسؤوليتنا القانونية. <span style="color: #ef4444;">*</span>
                            </span>
                        </label>
                        <div class="field-error"></div>
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
                <button type="button" id="draft-btn" style="padding: 1rem 2rem; background: #64748b; color: white; border: none; border-radius: 1rem; font-weight: 700; cursor: pointer; transition: all 0.2s;">
                    حفظ كمسودة
                </button>
                <button type="submit" id="publish-btn" style="padding: 1rem 3rem; background: linear-gradient(135deg, #10b981, #059669); color: white; border: none; border-radius: 1rem; font-weight: 800; cursor: pointer; transition: all 0.2s; box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.3);">
                    تأكيد ونشر
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
    'بيئة': [
        'قص أشجار',
        'تنظيف الشواطئ',
        'التشجير والزراعة',
        'التوعية البيئية',
        'إعادة التدوير',
        'حماية الحياة البرية',
        'مراقبة بيئية',
        'حملات النظافة العامة'
    ],
    'تكنولوجيا': [
        'البرمجة وتطوير المواقع',
        'تصميم الجرافيك',
        'إدارة منصات التواصل',
        'الدعم التقني',
        'تحليل البيانات',
        'الأمن السيبراني',
        'الذكاء الاصطناعي'
    ],
    'تعليم': [
        'محو الأمية',
        'تدريس اللغات',
        'دروس تقوية',
        'التعليم المهني',
        'تعليم الكبار',
        'التعليم الإلكتروني'
    ],
    'صحة': [
        'الإسعافات الأولية',
        'التوعية الصحية',
        'رعاية المرضى',
        'الصحة النفسية',
        'التغذية',
        'اللياقة البدنية'
    ],
    'مساعدة إنسانية': [
        'إغاثة الكوارث',
        'مساعدة الأسر المحتاجة',
        'رعاية الأيتام',
        'دعم كبار السن',
        'المساعدات الغذائية'
    ],
    'ريادة أعمال': [
        'تطوير الأعمال',
        'الاستشارات',
        'التسويق',
        'المبيعات',
        'ريادة الأعمال الاجتماعية'
    ],
    'رياضة': [
        'تدريب رياضي',
        'تنظيم فعاليات رياضية',
        'التحكيم',
        'اللياقة البدنية',
        'الرياضات الجماعية'
    ],
    'فنون': [
        'الرسم والتلوين',
        'الموسيقى',
        'المسرح',
        'الحرف اليدوية',
        'التصوير الفوتوغرافي'
    ]
};

// Form state
let currentStep = 1;
const totalSteps = 3;
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
const stepTitle = document.getElementById('step-title');
const stepDesc = document.getElementById('step-desc');
const errorContainer = document.getElementById('error-container');
const errorList = document.getElementById('error-list');
const autoSaveIndicator = document.getElementById('auto-save-indicator');

// Step metadata
const stepInfo = {
    1: { title: 'المعلومات الأساسية والتنفيذ', desc: 'أدخل البيانات الأساسية للفرصة ومكان وزمان التنفيذ' },
    2: { title: 'تفاصيل الفرصة والشروط', desc: 'حدد المقاعد، الساعات، والمتطلبات الخاصة بالمتقدمين' },
    3: { title: 'التوثيق والارسال', desc: 'أضف معلومات الشهادات وأنهِ عملية النشر' }
};

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    setupEventListeners();
    loadFromLocalStorage();
    restoreSession();
    updateNavigationButtons();
    
    if ("{{ $errors->any() }}") {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
});

// Helper to toggle disabled state for hidden inputs
function toggleSectionInputs(sectionId, enabled) {
    const section = document.getElementById(sectionId);
    if (!section) return;
    section.querySelectorAll('input, select, textarea').forEach(el => {
        el.disabled = !enabled;
    });
}

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
            toggleSectionInputs('volunteering-fields', true);
            trainingFields.style.display = 'none';
            toggleSectionInputs('training-fields', false);
            trainingOutcomesField.style.display = 'none';
        } else if (this.value === 'training') {
            volunteeringFields.style.display = 'none';
            toggleSectionInputs('volunteering-fields', false);
            trainingFields.style.display = 'block';
            toggleSectionInputs('training-fields', true);
            trainingOutcomesField.style.display = 'block';
        } else {
            volunteeringFields.style.display = 'none';
            trainingFields.style.display = 'none';
            toggleSectionInputs('volunteering-fields', false);
            toggleSectionInputs('training-fields', false);
        }
    });

    // Execution method change
    document.getElementById('execution_method').addEventListener('change', function() {
        const locationFields = document.getElementById('location-fields');
        const cityField = document.getElementById('city_id');
        
        if (this.value === 'in_person') {
            locationFields.style.display = 'block';
            toggleSectionInputs('location-fields', true);
            cityField.setAttribute('data-required', 'true');
        } else {
            locationFields.style.display = 'none';
            toggleSectionInputs('location-fields', false);
            cityField.removeAttribute('data-required');
        }
    });

    // Certificate provision removed as it is now automated


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

// Restore session values
function restoreSession() {
    const old = @json(old());
    if (old && Object.keys(old).length > 0) {
        // Trigger toggles first
        ['type', 'execution_method', 'category'].forEach(id => {
            if (old[id]) {
                const el = document.getElementById(id);
                if (el) {
                    el.value = old[id];
                    el.dispatchEvent(new Event('change'));
                }
            }
        });

        // Fill other fields
        for (const [key, value] of Object.entries(old)) {
            if (['type', 'execution_method', 'provides_certificate', 'category', 'subcategory', '_token'].includes(key)) continue;
            
            const fields = document.querySelectorAll(`[name="${key}"]`);
            fields.forEach(f => {
                if (!f.disabled) {
                    if (f.type === 'checkbox') f.checked = !!value;
                    else if (f.type !== 'file') f.value = value;
                }
            });
        }

        // Restore subcategory last after a small delay
        if (old['subcategory']) {
            setTimeout(() => {
                const sub = document.getElementById('subcategory');
                if (sub) {
                    sub.value = old['subcategory'];
                    sub.dispatchEvent(new Event('change'));
                }
            }, 100);
        }
    }
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
    
    // Update progress bubbles
    document.querySelectorAll('.step-bubble').forEach((bubble, index) => {
        const stepNum = index + 1;
        if (stepNum < currentStep) {
            bubble.style.background = '#10b981';
            bubble.style.borderColor = '#10b981';
            bubble.style.color = 'white';
            bubble.innerHTML = '✓';
            bubble.classList.add('done');
        } else if (stepNum === currentStep) {
            bubble.style.background = 'white';
            bubble.style.borderColor = '#3b82f6';
            bubble.style.color = '#3b82f6';
            bubble.innerHTML = stepNum;
            bubble.classList.add('active');
            bubble.style.boxShadow = '0 4px 10px rgba(59, 130, 246, 0.2)';
        } else {
            bubble.style.background = 'white';
            bubble.style.borderColor = '#f1f5f9';
            bubble.style.color = '#94a3b8';
            bubble.innerHTML = stepNum;
            bubble.classList.remove('active', 'done');
            bubble.style.boxShadow = 'none';
        }
    });

    stepTitle.textContent = stepInfo[currentStep].title;
    stepDesc.textContent = stepInfo[currentStep].desc;
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

    // Date validation for step 1
    if (currentStep === 1) {
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
