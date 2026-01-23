@extends('layouts.admin')

@section('title', 'مراجعة تفاصيل الفرصة')
@section('header', 'تفاصيل الفرصة للمراجعة')

@section('content')
<div style="max-width: 1400px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem;">
        <a href="{{ route('admin.opportunities.index') }}" style="color: #64748b; text-decoration: none; font-weight: 700; display: flex; align-items: center; gap: 0.5rem; transition: color 0.2s;" onmouseover="this.style.color='var(--volunteer-green)'" onmouseout="this.style.color='#64748b'">
            <i class="fas fa-chevron-right"></i> العودة لقائمة الانتظار
        </a>
        <div style="display: flex; gap: 1rem;">
            <span style="padding: 0.5rem 1.25rem; border-radius: 99px; background: #fef3c7; color: #d97706; font-weight: 800; font-size: 0.85rem; display: flex; align-items: center; gap: 0.5rem; border: 1px solid #fde68a;">
                <i class="fas fa-history"></i> في انتظار المراجعة
            </span>
        </div>
    </div>

    @if($errors->any())
        <div class="card" style="margin-bottom: 2rem; padding: 1.25rem; background: #fef2f2; border: 1px solid #fee2e2; color: #991b1b;">
            <ul style="margin: 0; padding-right: 1.5rem; font-weight: 600;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.opportunities.publish', $opportunity) }}" method="POST">
        @csrf
        <div style="display: grid; grid-template-columns: 1fr 340px; gap: 2rem; align-items: start;">
            
            <!-- Main Content Area -->
            <div style="display: flex; flex-direction: column; gap: 2rem;">
                <!-- Header Card (Title & Description - Editable) -->
                <div class="card" style="padding: 2.5rem;">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1rem;">
                        <i class="fas fa-edit" style="color: var(--volunteer-green); font-size: 1.5rem;"></i>
                        <h3 style="margin: 0; font-size: 1.25rem; font-weight: 800; color: #1e293b;">المحتوى الأساسي (قابل للتعديل)</h3>
                    </div>
                    
                    <div style="margin-bottom: 2rem;">
                        <label style="display: block; margin-bottom: 0.75rem; font-weight: 700; color: #475569; font-size: 0.95rem;">عنوان الفرصة</label>
                        <input type="text" name="title" value="{{ old('title', $opportunity->title) }}" 
                               style="width: 100%; padding: 1rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; font-size: 1.1rem; outline: none; transition: border-color 0.2s; font-family: 'Cairo', sans-serif; font-weight: 700;"
                               onfocus="this.style.borderColor='var(--volunteer-green)'" onblur="this.style.borderColor='#e2e8f0'">
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.75rem; font-weight: 700; color: #475569; font-size: 0.95rem;">الوصف التفصيلي</label>
                        <textarea name="description" rows="10" 
                                  style="width: 100%; padding: 1rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; font-size: 1rem; line-height: 1.7; outline: none; resize: vertical; transition: border-color 0.2s; font-family: 'Cairo', sans-serif;"
                                  onfocus="this.style.borderColor='var(--volunteer-green)'" onblur="this.style.borderColor='#e2e8f0'">{{ old('description', $opportunity->description) }}</textarea>
                    </div>
                </div>

                <!-- Technical Details Grid -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                    <!-- Details Card -->
                    <div class="card" style="padding: 2rem;">
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.75rem;">
                            <i class="fas fa-info-circle" style="color: var(--brand-blue); font-size: 1.25rem;"></i>
                            <h4 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #1e293b;">تفاصيل الفرصة</h4>
                        </div>
                        
                        <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.75rem; border-bottom: 1px dashed #e2e8f0;">
                                <span style="color: #64748b; font-weight: 600;">النوع:</span>
                                <span style="font-weight: 700; color: var(--volunteer-green); background: #f0fdf4; padding: 0.25rem 0.75rem; border-radius: 6px; font-size: 0.85rem;">
                                    {{ $opportunity->type == 'volunteering' ? 'فرصة تطوعية' : 'فرصة تدريبية' }}
                                </span>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.75rem; border-bottom: 1px dashed #e2e8f0;">
                                <span style="color: #64748b; font-weight: 600;">التصنيف:</span>
                                <span style="font-weight: 700; color: #1e293b;">{{ $opportunity->category }} / {{ $opportunity->subcategory ?: '--' }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.75rem; border-bottom: 1px dashed #e2e8f0;">
                                <span style="color: #64748b; font-weight: 600;">نوع المتطوع:</span>
                                <span style="font-weight: 700; color: #1e293b;">{{ $opportunity->volunteer_type == 'individual' ? 'أفراد' : ($opportunity->volunteer_type == 'group' ? 'مجموعات' : 'الكل') }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="color: #64748b; font-weight: 600;">طريقة التنفيذ:</span>
                                <span style="font-weight: 700; color: #1e293b;">{{ $opportunity->execution_method == 'remote' ? 'عن بعد 🌐' : 'حضوري 🏢' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Logistics Card -->
                    <div class="card" style="padding: 2rem;">
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.5rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.75rem;">
                            <i class="fas fa-tasks" style="color: var(--volunteer-green); font-size: 1.25rem;"></i>
                            <h4 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #1e293b;">البيانات اللوجستية</h4>
                        </div>
                        
                        <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.75rem; border-bottom: 1px dashed #e2e8f0;">
                                <span style="color: #64748b; font-weight: 600;">عدد المقاعد:</span>
                                <span style="font-weight: 800; color: #1e293b; font-size: 1.1rem;">{{ $opportunity->seats }} <small style="font-weight: 400; font-size: 0.8rem;">مقعد</small></span>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.75rem; border-bottom: 1px dashed #e2e8f0;">
                                <span style="color: #64748b; font-weight: 600;">إجمالي الساعات:</span>
                                <span style="font-weight: 800; color: #1e293b; font-size: 1.1rem;">{{ $opportunity->total_hours }} <small style="font-weight: 400; font-size: 0.8rem;">ساعة</small></span>
                            </div>
                            @if($opportunity->daily_hours)
                            <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.75rem; border-bottom: 1px dashed #e2e8f0;">
                                <span style="color: #64748b; font-weight: 600;">ساعات يومية:</span>
                                <span style="font-weight: 700; color: #1e293b;">{{ $opportunity->daily_hours }} ساعات/يوم</span>
                            </div>
                            @endif
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="color: #64748b; font-weight: 600;">الموعد النهائي للتقديم:</span>
                                <span style="font-weight: 700; color: #ef4444;">{{ $opportunity->application_deadline ? $opportunity->application_deadline->format('Y-m-d') : '--' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Requirements & Outcomes Section -->
                <div class="card" style="padding: 2rem;">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1rem;">
                        <i class="fas fa-list-check" style="color: var(--brand-blue); font-size: 1.5rem;"></i>
                        <h3 style="margin: 0; font-size: 1.25rem; font-weight: 800; color: #1e293b;">المتطلبات والمخرجات</h3>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2.5rem;">
                        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                            <div>
                                <h5 style="margin: 0 0 0.75rem 0; color: #475569; font-weight: 800;">الأهداف</h5>
                                <p style="background: #f8fafc; padding: 1rem; border-radius: 0.75rem; margin: 0; font-size: 0.95rem; min-height: 60px;">{{ $opportunity->objectives ?: 'لا يوجد أهداف مضافة' }}</p>
                            </div>
                            <div>
                                <h5 style="margin: 0 0 0.75rem 0; color: #475569; font-weight: 800;">المهام المطلوبة</h5>
                                <p style="background: #f8fafc; padding: 1rem; border-radius: 0.75rem; margin: 0; font-size: 0.95rem; min-height: 60px;">{{ $opportunity->tasks ?: 'لا يوجد مهام مضافة' }}</p>
                            </div>
                            @if($opportunity->type == 'training')
                            <div>
                                <h5 style="margin: 0 0 0.75rem 0; color: #475569; font-weight: 800;">مخرجات التدريب</h5>
                                <p style="background: #f0f9ff; padding: 1rem; border-radius: 0.75rem; margin: 0; font-size: 0.95rem; border: 1px solid #e0f2fe;">{{ $opportunity->training_outcomes ?: 'لا يوجد مخرجات مضافة' }}</p>
                            </div>
                            @endif
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                            <div>
                                <h5 style="margin: 0 0 0.75rem 0; color: #475569; font-weight: 800;">متطلبات المهارات</h5>
                                <p style="background: #fffbeb; padding: 1rem; border-radius: 0.75rem; margin: 0; font-size: 0.95rem; border: 1px solid #fef3c7;">{{ $opportunity->skills_requirement ?: 'لا يوجد مهارات محددة' }}</p>
                            </div>
                            <div style="display: flex; flex-direction: column; gap: 0.85rem;">
                                <div style="display: flex; justify-content: space-between; align-items: center; background: #f8fafc; padding: 0.75rem 1rem; border-radius: 0.5rem;">
                                    <span style="color: #64748b; font-size: 0.9rem;">العمر المطلوب:</span>
                                    <span style="font-weight: 700;">{{ $opportunity->age_requirement ?: 'غير محدد' }}</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; align-items: center; background: #f8fafc; padding: 0.75rem 1rem; border-radius: 0.5rem;">
                                    <span style="color: #64748b; font-size: 0.9rem;">المستوى التعليمي:</span>
                                    <span style="font-weight: 700;">{{ $opportunity->education_level ?: 'غير محدد' }}</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; align-items: center; background: #f8fafc; padding: 0.75rem 1rem; border-radius: 0.5rem;">
                                    <span style="color: #64748b; font-size: 0.9rem;">الخبرة السابقة:</span>
                                    <span style="font-weight: 700;">{{ $opportunity->previous_experience == 'yes' ? 'مطلوب إلزامي' : 'غير مطلوب' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Property Badges -->
                <div class="card" style="padding: 1.5rem;">
                    <div style="display: flex; flex-wrap: wrap; gap: 1rem;">
                        <div style="flex: 1; min-width: 150px; background: {{ $opportunity->is_practical ? '#f0fdf4' : '#f8fafc' }}; padding: 1.25rem; border-radius: 1rem; text-align: center; border: 1px solid {{ $opportunity->is_practical ? '#d1fae5' : '#f1f5f9' }};">
                            <i class="fas fa-hammer" style="display: block; font-size: 1.5rem; margin-bottom: 0.5rem; color: {{ $opportunity->is_practical ? 'var(--volunteer-green)' : '#94a3b8' }};"></i>
                            <span style="font-weight: 700; color: {{ $opportunity->is_practical ? '#065f46' : '#64748b' }};">تدريب عملي/تطبيقي</span>
                            <div style="font-size: 0.75rem; margin-top: 0.25rem;">{{ $opportunity->is_practical ? 'نعم' : 'لا' }}</div>
                        </div>
                        <div style="flex: 1; min-width: 150px; background: {{ $opportunity->has_stipend ? '#fdf2f8' : '#f8fafc' }}; padding: 1.25rem; border-radius: 1rem; text-align: center; border: 1px solid {{ $opportunity->has_stipend ? '#fbcfe8' : '#f1f5f9' }};">
                            <i class="fas fa-coins" style="display: block; font-size: 1.5rem; margin-bottom: 0.5rem; color: {{ $opportunity->has_stipend ? '#db2777' : '#94a3b8' }};"></i>
                            <span style="font-weight: 700; color: {{ $opportunity->has_stipend ? '#9d174d' : '#64748b' }};">بدل مالي/مكافأة</span>
                            <div style="font-size: 0.75rem; margin-top: 0.25rem;">{{ $opportunity->has_stipend ? 'يوجد' : 'لا يوجد' }}</div>
                        </div>
                        <div style="flex: 1; min-width: 150px; background: {{ $opportunity->attendance_required ? '#fef2f2' : '#f8fafc' }}; padding: 1.25rem; border-radius: 1rem; text-align: center; border: 1px solid {{ $opportunity->attendance_required ? '#fee2e2' : '#f1f5f9' }};">
                            <i class="fas fa-calendar-check" style="display: block; font-size: 1.5rem; margin-bottom: 0.5rem; color: {{ $opportunity->attendance_required ? '#ef4444' : '#94a3b8' }};"></i>
                            <span style="font-weight: 700; color: {{ $opportunity->attendance_required ? '#991b1b' : '#64748b' }};">الحضور إلزامي</span>
                            <div style="font-size: 0.75rem; margin-top: 0.25rem;">{{ $opportunity->attendance_required ? 'نعم' : 'اختياري/مرن' }}</div>
                        </div>
                        <div style="flex: 1; min-width: 150px; background: {{ $opportunity->pre_test_required ? '#fffbeb' : '#f8fafc' }}; padding: 1.25rem; border-radius: 1rem; text-align: center; border: 1px solid {{ $opportunity->pre_test_required ? '#fef3c7' : '#f1f5f9' }};">
                            <i class="fas fa-file-signature" style="display: block; font-size: 1.5rem; margin-bottom: 0.5rem; color: {{ $opportunity->pre_test_required ? '#d97706' : '#94a3b8' }};"></i>
                            <span style="font-weight: 700; color: {{ $opportunity->pre_test_required ? '#92400e' : '#64748b' }};">اختبار قبول</span>
                            <div style="font-size: 0.75rem; margin-top: 0.25rem;">{{ $opportunity->pre_test_required ? 'مطلوب' : 'غير مطلوب' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Actions & Organization Info -->
            <div style="display: flex; flex-direction: column; gap: 2rem; position: sticky; top: 100px;">
                <!-- Review Actions Card -->
                <div class="card" style="padding: 2rem; background: linear-gradient(180deg, white 0%, #f8fafc 100%); border: 1px solid #e2e8f0;">
                    <h4 style="margin: 0 0 1.5rem 0; font-weight: 800; color: #1e293b; text-align: center; font-size: 1.1rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.75rem;">اتخاذ قرار</h4>
                    
                    <button type="submit" style="width: 100%; padding: 1rem; background: var(--volunteer-green); color: white; border: none; border-radius: 0.75rem; font-weight: 700; font-size: 1.1rem; cursor: pointer; margin-bottom: 1rem; transition: all 0.2s; box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2); display: flex; align-items: center; justify-content: center; gap: 0.75rem;" onmouseover="this.style.background='var(--volunteer-green-dark)'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='var(--volunteer-green)'; this.style.transform='none'">
                        <i class="fas fa-rocket"></i> اعتماد ونشر الفرصة
                    </button>
                    
                    <button type="button" onclick="openReviewModal('changes')" style="width: 100%; padding: 0.875rem; background: white; color: var(--brand-blue); border: 2px solid var(--brand-blue); border-radius: 0.75rem; font-weight: 700; cursor: pointer; margin-bottom: 1rem; transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 0.75rem;" onmouseover="this.style.background='#eff6ff'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='white'; this.style.transform='none'">
                        <i class="fas fa-reply-all"></i> طلب تعديلات محددة
                    </button>

                    <button type="button" onclick="openReviewModal('reject')" style="width: 100%; padding: 0.875rem; background: #fff1f2; color: #e11d48; border: none; border-radius: 0.75rem; font-weight: 700; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; justify-content: center; gap: 0.75rem;" onmouseover="this.style.background='#ffe4e6'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='#fff1f2'; this.style.transform='none'">
                        <i class="fas fa-ban"></i> رفض نشر الفرصة
                    </button>

                    <p style="margin: 1.5rem 0 0 0; font-size: 0.75rem; color: #94a3b8; text-align: center; line-height: 1.6;">بمجرد النشر، سيتم إرسال إشعارات لجميع المتطوعين المطابقين لمعايير هذه الفرصة.</p>
                </div>

                <!-- Organization Info Card -->
                <div class="card" style="padding: 1.5rem;">
                    <h5 style="margin: 0 0 1rem 0; color: #64748b; font-size: 0.85rem; text-transform: uppercase; font-weight: 700;">المؤسسة المنظمة</h5>
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                        <div style="width: 54px; height: 54px; background: #f1f5f9; border-radius: 12px; display: flex; align-items: center; justify-content: center; border: 1px solid #e2e8f0;">
                            <i class="fas fa-building-shield" style="font-size: 1.5rem; color: var(--volunteer-green);"></i>
                        </div>
                        <div>
                            <div style="font-weight: 800; color: #1e293b; font-size: 1rem;">{{ $opportunity->organization->name }}</div>
                            <div style="font-size: 0.75rem; color: #64748b;">تاريخ الانضمام: {{ $opportunity->organization->created_at->format('Y-m-d') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Logistics & Contact Sidebar Card -->
                <div class="card" style="padding: 1.5rem;">
                    <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                        <div>
                            <span style="color: #64748b; font-size: 0.8rem; display: block; margin-bottom: 0.5rem;">الموقع والتنفيذ:</span>
                            <div style="font-weight: 700; color: #1e293b;"><i class="fas fa-map-location-dot" style="margin-left: 0.5rem; color: #94a3b8;"></i> {{ $opportunity->city->name ?? '--' }}</div>
                            @if($opportunity->address)
                                <div style="font-size: 0.85rem; color: #64748b; margin-top: 0.25rem; padding-right: 1.5rem;">{{ $opportunity->address }}</div>
                            @endif
                        </div>

                        <div style="padding-top: 1rem; border-top: 1px solid #f1f5f9;">
                            <span style="color: #64748b; font-size: 0.8rem; display: block; margin-bottom: 0.5rem;">مسؤول التواصل:</span>
                            <div style="font-weight: 700; color: #1e293b; font-size: 0.95rem;">{{ $opportunity->contact_name ?: '--' }}</div>
                            <div style="color: var(--brand-blue); font-size: 0.9rem; font-weight: 600;">{{ $opportunity->contact_info ?: '--' }}</div>
                        </div>

                        @if($opportunity->provides_certificate == 'yes' || $opportunity->certificateFile)
                        <div style="padding-top: 1rem; border-top: 1px solid #f1f5f9;">
                            <span style="color: #64748b; font-size: 0.8rem; display: block; margin-bottom: 0.5rem;">شهادة الإتمام:</span>
                            <div style="font-size: 0.85rem; color: #475569; background: #fafafa; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #f1f5f9; line-height: 1.5;">
                                {{ $opportunity->requires_certification ?: 'نعم، يتم منح شهادة معتمدة بعد الانتهاء.' }}
                            </div>
                            @if($opportunity->certificateFile)
                                <a href="{{ asset($opportunity->certificateFile->file_url) }}" target="_blank" style="margin-top: 0.75rem; display: flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.6rem; background: #eef2ff; color: #4f46e5; text-decoration: none; border-radius: 0.5rem; text-align: center; font-weight: 700; font-size: 0.85rem; transition: background 0.2s;" onmouseover="this.style.background='#e0e7ff'" onmouseout="this.style.background='#eef2ff'">
                                    <i class="fas fa-file-pdf"></i> معاينة نموذج الشهادة
                                </a>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Review Modals -->
<div id="reviewModal" style="display:none; position:fixed; inset:0; background:rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index:2000; align-items:center; justify-content:center; padding: 1.5rem;">
    <div style="background:white; padding:2.5rem; border-radius:1.5rem; width:100%; max-width:550px; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25); animation: modalIn 0.3s ease-out;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 id="modalTitle" style="margin:0; color:#1e293b; font-size: 1.5rem; font-weight: 800;">إجراء مراجعة</h3>
            <button onclick="closeReviewModal()" style="background: none; border: none; font-size: 1.5rem; color: #94a3b8; cursor: pointer;">&times;</button>
        </div>
        
        <form id="reviewActionForm" method="POST">
            @csrf
            <div style="margin-bottom: 2rem;">
                <label id="modalLabel" style="display:block; margin-bottom:0.75rem; font-weight:700; color:#475569;">الملاحظات / سبب الرفض</label>
                <textarea name="notes" rows="6" required 
                          style="width:100%; padding:1rem; border:1px solid #e2e8f0; border-radius:0.75rem; outline:none; resize:none; font-family:'Cairo', sans-serif; font-size: 1rem; line-height: 1.6;" 
                          placeholder="يرجى كتابة الملاحظات هنا ليتم إرسالها للمؤسسة..."></textarea>
            </div>
            
            <div style="display:flex; justify-content:flex-end; gap:1rem;">
                <button type="button" onclick="closeReviewModal()" style="padding:0.75rem 2rem; background:#f1f5f9; color:#475569; border:none; border-radius:0.75rem; cursor:pointer; font-weight:700; transition: background 0.2s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">إلغاء</button>
                <button type="submit" id="modalSubmit" style="padding:0.75rem 2rem; background:var(--brand-blue); color:white; border:none; border-radius:0.75rem; cursor:pointer; font-weight:800; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='none'">تأكيد الإجراء</button>
            </div>
        </form>
    </div>
</div>

<style>
    @keyframes modalIn {
        from { opacity: 0; transform: scale(0.95) translateY(20px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }
</style>

<script>
    function openReviewModal(type) {
        const form = document.getElementById('reviewActionForm');
        const title = document.getElementById('modalTitle');
        const label = document.getElementById('modalLabel');
        const submit = document.getElementById('modalSubmit');

        if (type === 'changes') {
            title.innerText = 'طلب تعديلات من المؤسسة';
            label.innerText = 'ما هي التعديلات المطلوبة تحديداً؟';
            form.action = "{{ route('admin.opportunities.request-changes', $opportunity) }}";
            submit.style.background = 'var(--brand-blue)';
            submit.innerText = 'إرسال طلب التعديل';
        } else {
            title.innerText = 'رفض نشر الفرصة';
            label.innerText = 'سبب الرفض (سيصل للمؤسسة بوضوح)';
            form.action = "{{ route('admin.opportunities.reject', $opportunity) }}";
            submit.style.background = '#e11d48';
            submit.innerText = 'تأكيد الرفض النهائي';
        }

        document.getElementById('reviewModal').style.display = 'flex';
    }

    function closeReviewModal() {
        document.getElementById('reviewModal').style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target == document.getElementById('reviewModal')) closeReviewModal();
    }
</script>
@endsection
