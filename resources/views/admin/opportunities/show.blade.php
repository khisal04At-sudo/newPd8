@extends('layouts.admin')

@section('title', 'مراجعة تفاصيل الفرصة')
@section('header', 'تفاصيل الفرصة للمراجعة')

@section('content')
<div style="max-width: 1000px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <a href="{{ route('admin.opportunities.index') }}" style="color: #64748b; text-decoration: none; font-weight: 600;">
            <i class="fas fa-arrow-right"></i> العودة للقائمة
        </a>
        <div style="display: flex; gap: 1rem;">
            <span style="padding: 0.5rem 1rem; border-radius: 0.5rem; background: #fef3c7; color: #d97706; font-weight: 700; font-size: 0.9rem;">
                <i class="fas fa-clock"></i> في انتظار المراجعة
            </span>
        </div>
    </div>

    @if($errors->any())
        <div style="margin-bottom: 2rem; padding: 1rem; background: #fee2e2; color: #dc2626; border-radius: 0.5rem;">
            <ul style="margin: 0; padding-right: 1.5rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.opportunities.publish', $opportunity) }}" method="POST">
        @csrf
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
            
            <!-- Left Side: Editable Content -->
            <div style="display: flex; flex-direction: column; gap: 2rem;">
                <div style="background: white; border-radius: 1rem; padding: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <h3 style="margin-top: 0; margin-bottom: 1.5rem; border-bottom: 2px solid #f1f5f9; padding-bottom: 0.5rem;">المحتوى القابل للتعديل</h3>
                    
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #1e293b;">عنوان الفرصة</label>
                        <input type="text" name="title" value="{{ old('title', $opportunity->title) }}" 
                               style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 1.1rem; outline: none; focus-border-color: #4f46e5;">
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 700; color: #1e293b;">الوصف التفصيلي</label>
                        <textarea name="description" rows="12" style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; font-size: 1rem; line-height: 1.6; outline: none; resize: vertical;">{{ old('description', $opportunity->description) }}</textarea>
                    </div>
                </div>

                <div style="background: white; border-radius: 1rem; padding: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <h3 style="margin-top: 0; margin-bottom: 1.5rem; border-bottom: 2px solid #f1f5f9; padding-bottom: 0.5rem;">البيانات اللوجستية (للمراجعة فقط)</h3>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div>
                            <span style="display: block; color: #64748b; font-size: 0.85rem; margin-bottom: 0.25rem;">الأهداف</span>
                            <div style="background: #f8fafc; padding: 0.75rem; border-radius: 0.5rem; font-size: 0.95rem;">{{ $opportunity->objectives ?: 'لا يوجد' }}</div>
                        </div>
                        <div>
                            <span style="display: block; color: #64748b; font-size: 0.85rem; margin-bottom: 0.25rem;">المهام</span>
                            <div style="background: #f8fafc; padding: 0.75rem; border-radius: 0.5rem; font-size: 0.95rem;">{{ $opportunity->tasks ?: 'لا يوجد' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Details & Actions -->
            <div style="display: flex; flex-direction: column; gap: 2rem;">
                <div style="background: white; border-radius: 1rem; padding: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <h3 style="margin-top: 0; margin-bottom: 1.5rem; font-size: 1.1rem;">معلومات المؤسسة</h3>
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                        <div style="width: 50px; height: 50px; background: #f1f5f9; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-building" style="font-size: 1.5rem; color: #94a3b8;"></i>
                        </div>
                        <div>
                            <div style="font-weight: 700; color: #1e293b;">{{ $opportunity->organization->name }}</div>
                            <div style="font-size: 0.8rem; color: #64748b;">تاريخ التسجيل: {{ $opportunity->organization->created_at->format('Y-m-d') }}</div>
                        </div>
                    </div>
                    
                    <div style="border-top: 1px solid #f1f5f9; padding-top: 1rem; font-size: 0.9rem;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem;">
                            <span style="color: #64748b;">المدينة:</span>
                            <span style="font-weight: 600;">{{ $opportunity->city->name ?? '--' }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem;">
                            <span style="color: #64748b;">العنوان:</span>
                            <span style="font-weight: 600; text-align: left; max-width: 150px;">{{ $opportunity->address }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem;">
                            <span style="color: #64748b;">عدد المقاعد:</span>
                            <span style="font-weight: 600;">{{ $opportunity->seats }} مقعد</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.75rem;">
                            <span style="color: #64748b;">الحضور:</span>
                            <span style="font-weight: 600; color: {{ $opportunity->attendance_required ? '#dc2626' : '#16a34a' }};">
                                {{ $opportunity->attendance_required ? 'إلزامي' : 'مرن/عن بعد' }}
                            </span>
                        </div>
                        
                        <!-- Policy Details -->
                        <div style="background: #f8fafc; padding: 0.75rem; border-radius: 0.5rem; margin-top: 1rem; margin-bottom: 1rem;">
                            <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                                @if($opportunity->is_practical)
                                    <span style="font-size: 0.7rem; background: #e0f2fe; color: #0369a1; padding: 0.2rem 0.5rem; border-radius: 4px;">عملي</span>
                                @endif
                                @if($opportunity->has_stipend)
                                    <span style="font-size: 0.7rem; background: #dcfce7; color: #16a34a; padding: 0.2rem 0.5rem; border-radius: 4px;">بدل مالي</span>
                                @endif
                                @if($opportunity->pre_test_required)
                                    <span style="font-size: 0.7rem; background: #fef3c7; color: #d97706; padding: 0.2rem 0.5rem; border-radius: 4px;">اختبار قبلي</span>
                                @endif
                            </div>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                            <span style="color: #64748b; font-size: 0.85rem;">شهادة الإتمام:</span>
                            <div style="font-weight: 600; border: 1px solid #e2e8f0; padding: 0.5rem; border-radius: 0.5rem;">
                                {{ $opportunity->requires_certification ?: 'لا توجد تفاصيل' }}
                            </div>
                            @if($opportunity->certificateFile)
                                <a href="{{ asset($opportunity->certificateFile->file_url) }}" target="_blank" style="margin-top: 0.5rem; display: block; padding: 0.5rem; background: #eff6ff; color: #1d4ed8; text-decoration: none; border-radius: 0.5rem; text-align: center; font-weight: 700;">
                                    <i class="fas fa-file-pdf"></i> عرض ملف الشهادة المرفق
                                </a>
                            @endif
                        </div>

                        <div style="margin-top: 1.5rem; border-top: 1px solid #f1f5f9; padding-top: 1rem;">
                            <span style="color: #64748b; display: block; margin-bottom: 0.5rem;">مسؤول التواصل:</span>
                            <div style="font-weight: 600; font-size: 1rem;">{{ $opportunity->contact_name ?: '--' }}</div>
                            <div style="color: #4f46e5; font-size: 0.9rem;">{{ $opportunity->contact_info ?: '--' }}</div>
                        </div>
                    </div>
                </div>

                <div style="background: white; border-radius: 1rem; padding: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <button type="submit" style="width: 100%; padding: 1rem; background: #16a34a; color: white; border: none; border-radius: 0.75rem; font-weight: 700; font-size: 1.1rem; cursor: pointer; margin-bottom: 1rem; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='none'">
                        <i class="fas fa-check-circle"></i> نشر الفرصة الآن
                    </button>
                    
                    <button type="button" onclick="openReviewModal('changes')" style="width: 100%; padding: 1rem; background: #4f46e5; color: white; border: none; border-radius: 0.75rem; font-weight: 700; cursor: pointer; margin-bottom: 1rem;">
                        <i class="fas fa-edit"></i> طلب تعديلات
                    </button>

                    <button type="button" onclick="openReviewModal('reject')" style="width: 100%; padding: 1rem; background: white; border: 2px solid #fee2e2; color: #dc2626; border-radius: 0.75rem; font-weight: 700; cursor: pointer;">
                        <i class="fas fa-times-circle"></i> رفض النشر
                    </button>
                </div>

            </div>
        </div>
    </form>
</div>

<!-- Review Modals -->
<div id="reviewModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:2000; align-items:center; justify-content:center; padding: 1rem;">
    <div style="background:white; padding:2rem; border-radius:1rem; width:100%; max-width:500px; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1);">
        <h3 id="modalTitle" style="margin-top:0; color:#1e293b;">إجراء مراجعة</h3>
        <form id="reviewActionForm" method="POST">
            @csrf
            <label id="modalLabel" style="display:block; margin-bottom:0.5rem; font-weight:600; color:#475569;">الملاحظات / سبب الرفض</label>
            <textarea name="notes" rows="6" required style="width:100%; padding:0.75rem; border:1px solid #e2e8f0; border-radius:0.5rem; outline:none; resize:none;" placeholder="يرجى كتابة الملاحظات هنا ليتم إرسالها للمؤسسة..."></textarea>
            <div style="display:flex; justify-content:flex-end; gap:1rem; margin-top:1.5rem;">
                <button type="button" onclick="closeReviewModal()" style="padding:0.6rem 1.5rem; background:#f1f5f9; color:#475569; border:none; border-radius:0.5rem; cursor:pointer; font-weight:600;">إلغاء</button>
                <button type="submit" id="modalSubmit" style="padding:0.6rem 1.5rem; background:#4f46e5; color:white; border:none; border-radius:0.5rem; cursor:pointer; font-weight:700;">تأكيد</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openReviewModal(type) {
        const form = document.getElementById('reviewActionForm');
        const title = document.getElementById('modalTitle');
        const label = document.getElementById('modalLabel');
        const submit = document.getElementById('modalSubmit');

        if (type === 'changes') {
            title.innerText = 'طلب تعديلات من المؤسسة';
            label.innerText = 'ما هي التعديلات المطلوبة؟';
            form.action = "{{ route('admin.opportunities.request-changes', $opportunity) }}";
            submit.style.background = '#4f46e5';
            submit.innerText = 'إرسال طلب التعديل';
        } else {
            title.innerText = 'رفض نشر الفرصة';
            label.innerText = 'سبب الرفض (سيظهر للمؤسسة)';
            form.action = "{{ route('admin.opportunities.reject', $opportunity) }}";
            submit.style.background = '#dc2626';
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
