@extends('layouts.admin')

@section('title', $user->name . ' - الملف الشخصي')

@section('content')
<div style="font-family: 'Cairo', sans-serif;">
    {{-- Back Button --}}
    <a href="{{ route('admin.users.index') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; color: #64748b; text-decoration: none; margin-bottom: 1.5rem; font-weight: 700; transition: color 0.2s;" onmouseover="this.style.color='#3b82f6'" onmouseout="this.style.color='#64748b'">
        <i class="fas fa-arrow-right"></i> العودة إلى قائمة المستخدمين
    </a>

    @if(session('success'))
        <div style="background: #ecfdf5; color: #10b981; padding: 1.25rem; border-radius: 1.25rem; margin-bottom: 2rem; font-weight: 800; border: 1px solid #d1fae5; display: flex; align-items: center; gap: 0.75rem;">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: #fef2f2; color: #ef4444; padding: 1.25rem; border-radius: 1.25rem; margin-bottom: 2rem; font-weight: 800; border: 1px solid #fee2e2; display: flex; align-items: center; gap: 0.75rem;">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- Admin Control Panel --}}
    <div class="card" style="padding: 2rem; border-radius: 1.5rem; margin-bottom: 2rem; background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 100%); border: 2px solid #e0e7ff;">
        <h3 style="margin: 0 0 1.5rem 0; color: #1e293b; font-weight: 850; font-size: 1.3rem; display: flex; align-items: center; gap: 0.75rem;">
            <i class="fas fa-shield-alt" style="color: #3b82f6;"></i> لوحة التحكم الإدارية
        </h3>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
            {{-- Current Status --}}
            <div style="background: white; padding: 1.5rem; border-radius: 1rem; border: 1px solid #e2e8f0;">
                <div style="font-size: 0.85rem; color: #64748b; margin-bottom: 0.75rem; font-weight: 700;">الحالة الحالية</div>
                @php
                    $statusInfo = [
                        0 => ['label' => 'جديد', 'color' => '#f59e0b', 'bg' => '#fef3c7'],
                        1 => ['label' => 'نشط', 'color' => '#10b981', 'bg' => '#d1fae5'],
                        2 => ['label' => 'محظور', 'color' => '#ef4444', 'bg' => '#fee2e2'],
                    ];
                    $status = $statusInfo[$user->status] ?? ['label' => 'غير معروف', 'color' => '#64748b', 'bg' => '#f1f5f9'];
                @endphp
                <div style="background: {{ $status['bg'] }}; color: {{ $status['color'] }}; padding: 0.75rem 1.25rem; border-radius: 0.75rem; font-weight: 800; text-align: center; font-size: 1.1rem;">
                    {{ $status['label'] }}
                </div>
                <div style="margin-top: 0.75rem; font-size: 0.8rem; color: #94a3b8; text-align: center;">
                    {{ $user->is_active ? '✓ الحساب مفعّل' : '✗ الحساب معطّل' }}
                </div>
            </div>

            {{-- Ban/Unban Actions --}}
            <div style="background: white; padding: 1.5rem; border-radius: 1rem; border: 1px solid #e2e8f0;">
                <div style="font-size: 0.85rem; color: #64748b; margin-bottom: 0.75rem; font-weight: 700;">إدارة الحظر</div>
                @if($user->isBanned())
                    <form action="{{ route('admin.users.unban', $user) }}" method="POST">
                        @csrf
                        <button type="submit" style="width: 100%; background: #10b981; color: white; border: none; padding: 0.75rem 1.25rem; border-radius: 0.75rem; font-size: 0.95rem; font-weight: 800; cursor: pointer; transition: all 0.2s; font-family: 'Cairo', sans-serif;" onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10b981'">
                            <i class="fas fa-unlock"></i> إلغاء الحظر
                        </button>
                    </form>
                @else
                    <form action="{{ route('admin.users.ban', $user) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حظر هذا المستخدم؟')">
                        @csrf
                        <button type="submit" style="width: 100%; background: #ef4444; color: white; border: none; padding: 0.75rem 1.25rem; border-radius: 0.75rem; font-size: 0.95rem; font-weight: 800; cursor: pointer; transition: all 0.2s; font-family: 'Cairo', sans-serif;" onmouseover="this.style.background='#dc2626'" onmouseout="this.style.background='#ef4444'">
                            <i class="fas fa-ban"></i> حظر المستخدم
                        </button>
                    </form>
                @endif
            </div>

            {{-- Toggle Active --}}
            <div style="background: white; padding: 1.5rem; border-radius: 1rem; border: 1px solid #e2e8f0;">
                <div style="font-size: 0.85rem; color: #64748b; margin-bottom: 0.75rem; font-weight: 700;">تفعيل/تعطيل الحساب</div>
                <form action="{{ route('admin.users.toggle-active', $user) }}" method="POST">
                    @csrf
                    <button type="submit" style="width: 100%; background: {{ $user->is_active ? '#f59e0b' : '#3b82f6' }}; color: white; border: none; padding: 0.75rem 1.25rem; border-radius: 0.75rem; font-size: 0.95rem; font-weight: 800; cursor: pointer; transition: all 0.2s; font-family: 'Cairo', sans-serif;">
                        <i class="fas fa-power-off"></i> {{ $user->is_active ? 'تعطيل' : 'تفعيل' }} الحساب
                    </button>
                </form>
            </div>

            {{-- Rating System --}}
            <div style="background: white; padding: 1.5rem; border-radius: 1rem; border: 1px solid #e2e8f0;">
                <div style="font-size: 0.85rem; color: #64748b; margin-bottom: 0.75rem; font-weight: 700;">تقييم المستخدم</div>
                <form action="{{ route('admin.users.rating', $user) }}" method="POST" id="ratingForm">
                    @csrf
                    <input type="hidden" name="rating" id="ratingValue" value="{{ $user->admin_rating ?? 0 }}">
                    <div style="display: flex; justify-content: center; gap: 0.5rem; margin-bottom: 1rem;">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star rating-star" data-rating="{{ $i }}" style="font-size: 1.5rem; color: {{ $i <= ($user->admin_rating ?? 0) ? '#f59e0b' : '#e2e8f0' }}; cursor: pointer; transition: all 0.2s;"></i>
                        @endfor
                    </div>
                    <button type="submit" style="width: 100%; background: #3b82f6; color: white; border: none; padding: 0.5rem 1rem; border-radius: 0.75rem; font-size: 0.85rem; font-weight: 800; cursor: pointer; transition: all 0.2s; font-family: 'Cairo', sans-serif;">
                        حفظ التقييم
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- User Profile --}}
    <div style="background: linear-gradient(135deg, #eef2ff 0%, #f0fdf4 100%); min-height: 50vh; padding: 2rem; border-radius: 1.5rem;">
        {{-- Profile Header --}}
        <div class="card" style="padding: 3rem; border-radius: 2rem; margin-bottom: 2rem; text-align: center; position: relative; overflow: hidden;">
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 120px; background: linear-gradient(135deg, #3b82f6, #10b981); opacity: 0.1;"></div>
            
            <div style="position: relative; z-index: 1;">
                <div style="width: 120px; height: 120px; border-radius: 50%; margin: 0 auto 1.5rem; background: linear-gradient(135deg, #3b82f6, #10b981); display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem; font-weight: 800; box-shadow: 0 10px 30px rgba(59, 130, 246, 0.3);">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                
                <h1 style="font-size: 2.5rem; font-weight: 800; color: #1e293b; margin: 0 0 0.5rem 0;">{{ $user->name }}</h1>
                <p style="font-size: 1.1rem; color: #64748b; margin: 0 0 1rem 0;">
                    <i class="fas fa-map-marker-alt" style="color: #3b82f6;"></i> {{ $user->city->name ?? 'ليبيا' }}
                </p>
                
                @if($user->bio)
                <p style="max-width: 600px; margin: 0 auto 2rem; color: #475569; line-height: 1.8;">{{ $user->bio }}</p>
                @endif
            </div>
        </div>

        {{-- Statistics Cards --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
            <div class="card" style="padding: 1.5rem; text-align: center; background: linear-gradient(135deg, #3b82f6, #2563eb); color: white;">
                <div style="font-size: 2.5rem; font-weight: 800; margin-bottom: 0.5rem;">{{ $stats['total_hours'] }}</div>
                <div style="opacity: 0.9;">ساعة تطوعية</div>
            </div>
            <div class="card" style="padding: 1.5rem; text-align: center; background: linear-gradient(135deg, #10b981, #059669); color: white;">
                <div style="font-size: 2.5rem; font-weight: 800; margin-bottom: 0.5rem;">{{ $stats['certificates_count'] }}</div>
                <div style="opacity: 0.9;">شهادة مكتسبة</div>
            </div>
            <div class="card" style="padding: 1.5rem; text-align: center; background: linear-gradient(135deg, #f59e0b, #d97706); color: white;">
                <div style="font-size: 2.5rem; font-weight: 800; margin-bottom: 0.5rem;">{{ $stats['achievements_count'] }}</div>
                <div style="opacity: 0.9;">إنجاز</div>
            </div>
            <div class="card" style="padding: 1.5rem; text-align: center; background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: white;">
                <div style="font-size: 2.5rem; font-weight: 800; margin-bottom: 0.5rem;">{{ $stats['accepted_applications'] }}</div>
                <div style="opacity: 0.9;">فرصة مقبولة</div>
            </div>
        </div>

        {{-- Rest of user profile content can be included here from the public profile --}}
        <div style="background: white; padding: 2rem; border-radius: 1.5rem; margin-bottom: 2rem;">
            <h3 style="margin: 0 0 1.5rem 0; color: #1e293b; font-weight: 800;">معلومات الحساب</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div>
                    <div style="color: #94a3b8; font-size: 0.85rem; margin-bottom: 0.5rem;">البريد الإلكتروني</div>
                    <div style="color: #1e293b; font-weight: 700;">{{ $user->email }}</div>
                </div>
                <div>
                    <div style="color: #94a3b8; font-size: 0.85rem; margin-bottom: 0.5rem;">رقم الهاتف</div>
                    <div style="color: #1e293b; font-weight: 700;">{{ $user->phone ?? 'غير محدد' }}</div>
                </div>
                <div>
                    <div style="color: #94a3b8; font-size: 0.85rem; margin-bottom: 0.5rem;">تاريخ التسجيل</div>
                    <div style="color: #1e293b; font-weight: 700;">{{ $user->created_at->format('Y/m/d - h:i A') }}</div>
                </div>
                <div>
                    <div style="color: #94a3b8; font-size: 0.85rem; margin-bottom: 0.5rem;">آخر تحديث</div>
                    <div style="color: #1e293b; font-weight: 700;">{{ $user->updated_at->format('Y/m/d - h:i A') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Rating system
document.querySelectorAll('.rating-star').forEach(star => {
    star.addEventListener('click', function() {
        const rating = this.dataset.rating;
        document.getElementById('ratingValue').value = rating;
        
        document.querySelectorAll('.rating-star').forEach((s, index) => {
            if (index < rating) {
                s.style.color = '#f59e0b';
            } else {
                s.style.color = '#e2e8f0';
            }
        });
    });
    
    star.addEventListener('mouseover', function() {
        this.style.transform = 'scale(1.2)';
    });
    
    star.addEventListener('mouseout', function() {
        this.style.transform = 'none';
    });
});
</script>
@endsection
