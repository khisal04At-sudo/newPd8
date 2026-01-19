@extends('layouts.admin')

@section('title', 'إدارة المؤسسات')
@section('header', 'إدارة المؤسسات المسجلة')

@section('content')
<div style="margin-bottom: 2rem;">
    <!-- Filters Card -->
    <div style="background: white; border-radius: 1rem; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 2rem;">
        <form action="{{ route('admin.organizations.index') }}" method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
            
            <!-- Search -->
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-size: 0.85rem; color: #64748b; font-weight: 600;">بحث بالاسم</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="اسم المؤسسة..." 
                       style="width: 100%; padding: 0.6rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; outline: none;">
            </div>

            <!-- Status -->
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-size: 0.85rem; color: #64748b; font-weight: 600;">الحالة</label>
                <select name="status" style="width: 100%; padding: 0.6rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; outline: none; background: white;">
                    <option value="all">الكل</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>معتمدة</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>مرفوضة</option>
                    <option value="needs_documents" {{ request('status') == 'needs_documents' ? 'selected' : '' }}>نقص مستندات</option>
                </select>
            </div>

            <!-- Sector (Legal Type) -->
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-size: 0.85rem; color: #64748b; font-weight: 600;">نوع المؤسسة</label>
                <select name="sector" style="width: 100%; padding: 0.6rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; outline: none; background: white;">
                    <option value="all">الكل</option>
                    <option value="non_profit" {{ request('sector') == 'non_profit' ? 'selected' : '' }}>جمعية/NGO</option>
                    <option value="public" {{ request('sector') == 'public' ? 'selected' : '' }}>حكومية</option>
                    <option value="private" {{ request('sector') == 'private' ? 'selected' : '' }}>خاصة</option>
                    <option value="educational" {{ request('sector') == 'educational' ? 'selected' : '' }}>تعليمية</option>
                    <option value="initiative" {{ request('sector') == 'initiative' ? 'selected' : '' }}>فريق تطوعي</option>
                </select>
            </div>

            <!-- City -->
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-size: 0.85rem; color: #64748b; font-weight: 600;">المدينة</label>
                <select name="city_id" style="width: 100%; padding: 0.6rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; outline: none; background: white;">
                    <option value="">كل المدن</option>
                    @foreach($cities as $city)
                        <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Date -->
            <div>
                <label style="display: block; margin-bottom: 0.5rem; font-size: 0.85rem; color: #64748b; font-weight: 600;">تاريخ التسجيل</label>
                <input type="date" name="date" value="{{ request('date') }}" 
                       style="width: 100%; padding: 0.6rem; border: 1px solid #e2e8f0; border-radius: 0.5rem; outline: none;">
            </div>

            <!-- Actions -->
            <div style="display: flex; gap: 0.5rem;">
                <button type="submit" style="flex: 2; padding: 0.6rem; background: #4f46e5; color: white; border: none; border-radius: 0.5rem; font-weight: 700; cursor: pointer;">تطبيق</button>
                <a href="{{ route('admin.organizations.index') }}" style="flex: 1; padding: 0.6rem; background: #f1f5f9; color: #475569; border-radius: 0.5rem; text-align: center; text-decoration: none; font-weight: 600;">مسح</a>
            </div>
        </form>
    </div>

    <!-- Organizations Table Card -->
    <div style="background: white; border-radius: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
        <div style="padding: 1.5rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; color: #1e293b;">عرض المؤسسات ({{ $organizations->total() }})</h3>
        </div>

        @if(session('success'))
            <div style="margin: 1.5rem; padding: 1rem; background: #dcfce7; color: #16a34a; border-radius: 0.5rem;">
                {{ session('success') }}
            </div>
        @endif
        @if(session('info'))
            <div style="margin: 1.5rem; padding: 1rem; background: #e0f2fe; color: #0369a1; border-radius: 0.5rem;">
                {{ session('info') }}
            </div>
        @endif

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: right;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 1px solid #f1f5f9;">
                        <th style="padding: 1rem;">المؤسسة</th>
                        <th style="padding: 1rem;">النوع</th>
                        <th style="padding: 1rem;">المدينة</th>
                        <th style="padding: 1rem;">حالة الاعتماد</th>
                        <th style="padding: 1rem;">التاريخ</th>
                        <th style="padding: 1rem;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($organizations as $org)
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                            <td style="padding: 1rem;">
                                <div style="font-weight: 750; color: #1e293b;">{{ $org->name }}</div>
                                <div style="font-size: 0.8rem; color: #64748b;">ID: {{ $org->registration_number ?? '--' }}</div>
                            </td>
                            <td style="padding: 1rem;">
                                @php
                                    $sectors = [
                                        'private' => 'قطاع خاص',
                                        'public' => 'حكومية',
                                        'initiative' => 'مبادرة',
                                        'non_profit' => 'NGO/غير ربحية',
                                        'educational' => 'تعليمية'
                                    ];
                                @endphp
                                <span style="font-size: 0.85rem; color: #475569;">
                                    {{ $sectors[$org->sector] ?? $org->sector }}
                                </span>
                            </td>
                            <td style="padding: 1rem;">{{ $org->city->name ?? '--' }}</td>
                            <td style="padding: 1rem;">
                                @php
                                    $statusLabels = [
                                        'pending' => ['text' => 'قيد الانتظار', 'bg' => '#fef3c7', 'color' => '#d97706'],
                                        'approved' => ['text' => 'تم التحقق', 'bg' => '#dcfce7', 'color' => '#16a34a'],
                                        'rejected' => ['text' => 'مرفوضة', 'bg' => '#fee2e2', 'color' => '#dc2626'],
                                        'needs_documents' => ['text' => 'نقص أوراق', 'bg' => '#e0f2fe', 'color' => '#0369a1'],
                                    ];
                                    $s = $statusLabels[$org->status] ?? ['text' => $org->status, 'bg' => '#f1f5f9', 'color' => '#64748b'];
                                @endphp
                                <span style="padding: 0.3rem 0.75rem; border-radius: 99px; font-size: 0.75rem; font-weight: 800; background: {{ $s['bg'] }}; color: {{ $s['color'] }};">
                                    {{ $s['text'] }}
                                </span>
                            </td>
                            <td style="padding: 1rem; font-size: 0.85rem; color: #64748b;">{{ $org->created_at->format('Y-m-d') }}</td>
                            <td style="padding: 1rem;">
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="{{ route('admin.organizations.show', $org) }}" title="عرض التفاصيل" 
                                       style="padding: 0.4rem 0.6rem; background: #f1f5f9; color: #475569; border-radius: 0.4rem; text-decoration: none; font-size: 0.8rem;">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    @if($org->status !== 'approved')
                                    <form action="{{ route('admin.organizations.approve', $org) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من اعتماد هذه المؤسسة؟')">
                                        @csrf
                                        <button type="submit" title="اعتماد" style="padding: 0.4rem 0.6rem; background: #dcfce7; color: #16a34a; border: none; border-radius: 0.4rem; cursor: pointer; font-size: 0.8rem;">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    @endif

                                    @if($org->status !== 'rejected')
                                    <button onclick="openRejectModal('{{ $org->id }}', '{{ $org->name }}')" title="رفض" 
                                            style="padding: 0.4rem 0.6rem; background: #fee2e2; color: #dc2626; border: none; border-radius: 0.4rem; cursor: pointer; font-size: 0.8rem;">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    @endif

                                    @if($org->status !== 'needs_documents')
                                    <button onclick="openRequestModal('{{ $org->id }}', '{{ $org->name }}')" title="طلب مستندات" 
                                            style="padding: 0.4rem 0.6rem; background: #e0f2fe; color: #0369a1; border: none; border-radius: 0.4rem; cursor: pointer; font-size: 0.8rem;">
                                        <i class="fas fa-file-signature"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 4rem; text-align: center; color: #94a3b8;">
                                <i class="fas fa-search" style="font-size: 2rem; display: block; margin-bottom: 1rem;"></i>
                                لا توجد نتائج تطابق خيارات الفلترة الحالية.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

<!-- Modals & Scripts -->
<div id="rejectModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:2000; align-items:center; justify-content:center;">
    <div style="background:white; padding:2rem; border-radius:1rem; width:100%; max-width:500px; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1);">
        <h3 id="rejectTitle" style="margin-top:0; color:#1e293b;">رفض المؤسسة</h3>
        <form id="rejectForm" method="POST">
            @csrf
            <label style="display:block; margin-bottom:0.5rem; font-weight:600; color:#475569;">سبب الرفض</label>
            <textarea name="reason" rows="4" required style="width:100%; padding:0.75rem; border:1px solid #e2e8f0; border-radius:0.5rem; outline:none; resize:none;"></textarea>
            <div style="display:flex; justify-content:flex-end; gap:1rem; margin-top:1.5rem;">
                <button type="button" onclick="closeModals()" style="padding:0.6rem 1.5rem; background:#f1f5f9; color:#475569; border:none; border-radius:0.53rem; cursor:pointer; font-weight:600;">إلغاء</button>
                <button type="submit" style="padding:0.6rem 1.5rem; background:#dc2626; color:white; border:none; border-radius:0.5rem; cursor:pointer; font-weight:700;">تأكيد الرفض</button>
            </div>
        </form>
    </div>
</div>

<div id="requestModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:2000; align-items:center; justify-content:center;">
    <div style="background:white; padding:2rem; border-radius:1rem; width:100%; max-width:500px; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1);">
        <h3 id="requestTitle" style="margin-top:0; color:#1e293b;">طلب مستندات إضافية</h3>
        <form id="requestForm" method="POST">
            @csrf
            <label style="display:block; margin-bottom:0.5rem; font-weight:600; color:#475569;">المستندات المطلوبة</label>
            <textarea name="requested_documents" rows="4" required style="width:100%; padding:0.75rem; border:1px solid #e2e8f0; border-radius:0.5rem; outline:none; resize:none;" placeholder="مثال: يرجى إرسال نسخة من السجل التجاري..."></textarea>
            <div style="display:flex; justify-content:flex-end; gap:1rem; margin-top:1.5rem;">
                <button type="button" onclick="closeModals()" style="padding:0.6rem 1.5rem; background:#f1f5f9; color:#475569; border:none; border-radius:0.5rem; cursor:pointer; font-weight:600;">إلغاء</button>
                <button type="submit" style="padding:0.6rem 1.5rem; background:#4f46e5; color:white; border:none; border-radius:0.5rem; cursor:pointer; font-weight:700;">إرسال الطلب</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openRejectModal(id, name) {
        document.getElementById('rejectTitle').innerText = 'رفض طلب مؤسسة: ' + name;
        document.getElementById('rejectForm').action = '/admin/organizations/' + id + '/reject';
        document.getElementById('rejectModal').style.display = 'flex';
    }

    function openRequestModal(id, name) {
        document.getElementById('requestTitle').innerText = 'طلب مستندات لـ: ' + name;
        document.getElementById('requestForm').action = '/admin/organizations/' + id + '/request-documents';
        document.getElementById('requestModal').style.display = 'flex';
    }

    function closeModals() {
        document.getElementById('rejectModal').style.display = 'none';
        document.getElementById('requestModal').style.display = 'none';
    }

    // Close on overlay click
    window.onclick = function(event) {
        if (event.target == document.getElementById('rejectModal')) closeModals();
        if (event.target == document.getElementById('requestModal')) closeModals();
    }
</script>
@endsection

