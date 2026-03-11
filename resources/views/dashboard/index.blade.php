@extends('layouts.dashboard')

@section('title', 'الرئيسية')

@section('content')
@if(auth()->user()->user_type === 'organization')
    @php $org = auth()->user()->organization; @endphp
    <div style="margin-bottom: 2rem; padding: 1.5rem; border-radius: 1rem; background: {{ $org->verified ? '#f0fdf4' : '#fff7ed' }}; border: 1px solid {{ $org->verified ? '#bbf7d0' : '#ffedd5' }}; display: flex; justify-content: space-between; align-items: center;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="font-size: 2rem; color: {{ $org->verified ? '#16a34a' : '#ea580c' }};">
                <i class="fas {{ $org->verified ? 'fa-check-decagram' : 'fa-hourglass-half' }}"></i>
            </div>
            <div>
                <h3 style="margin: 0; color: #1e293b;">
                    {{ $org->name }} ({{ $org->verified ? 'مؤسسة معتمدة' : 'مؤسسة غير معتمدة' }})
                </h3>
                <p style="margin: 0; font-size: 0.9rem; color: #64748b;">
                    {{ $org->verified ? 'حسابكم موثق ويمكنكم نشر الفرص التطوعية.' : 'حسابكم بانتظار مراجعة الإدارة للمستندات المرفوعة.' }}
                </p>
            </div>
        </div>
        @if(!$org->verified)
            <a href="{{ route('organization.verify.documents') }}" style="padding: 0.6rem 1.2rem; background: #ea580c; color: white; border-radius: 0.5rem; text-decoration: none; font-weight: 600; font-size: 0.9rem;">
                <i class="fas fa-upload"></i> رفع المستندات
            </a>
        @endif
    </div>
@endif
@if(auth()->user()->user_type === 'organization')
    <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px;">
        <div class="card" style="text-align: center;">
            <i class="fas fa-briefcase" style="font-size: 30px; color: #6366f1; margin-bottom: 10px;"></i>
            <h2 style="margin: 5px 0;">{{ $stats['opportunities_count'] ?? 0 }}</h2>
            <p style="color: #64748b; margin: 0;">إجمالي الفرص</p>
        </div>
        <div class="card" style="text-align: center;">
            <i class="fas fa-users" style="font-size: 30px; color: #10b981; margin-bottom: 10px;"></i>
            <h2 style="margin: 5px 0;">{{ $stats['total_applicants'] ?? 0 }}</h2>
            <p style="color: #64748b; margin: 0;">إجمالي المتطوعين</p>
        </div>
        <div class="card" style="text-align: center;">
            <i class="fas fa-clock" style="font-size: 30px; color: #f59e0b; margin-bottom: 10px;"></i>
            <h2 style="margin: 5px 0;">{{ $stats['total_hours_provided'] ?? 0 }}</h2>
            <p style="color: #64748b; margin: 0;">ساعات تم تقديمها</p>
        </div>
        <div class="card" style="text-align: center;">
            <i class="fas fa-check-circle" style="font-size: 30px; color: #8b5cf6; margin-bottom: 10px;"></i>
            <h2 style="margin: 5px 0;">{{ $stats['active_opportunities'] ?? 0 }}</h2>
            <p style="color: #64748b; margin: 0;">فرص نشطة</p>
        </div>
    </div>
@else
    <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px;">
        <div class="card" style="text-align: center;">
            <i class="fas fa-clock" style="font-size: 30px; color: #6366f1; margin-bottom: 10px;"></i>
            <h2 style="margin: 5px 0;">{{ auth()->user()->volunteer_hours }}</h2>
            <p style="color: #64748b; margin: 0;">ساعة تطوعية</p>
        </div>
        <div class="card" style="text-align: center;">
            <i class="fas fa-tasks" style="font-size: 30px; color: #10b981; margin-bottom: 10px;"></i>
            <h2 style="margin: 5px 0;">{{ auth()->user()->applications()->count() }}</h2>
            <p style="color: #64748b; margin: 0;">فرص مشارك بها</p>
        </div>
        <div class="card" style="text-align: center;">
            <i class="fas fa-certificate" style="font-size: 30px; color: #f59e0b; margin-bottom: 10px;"></i>
            <h2 style="margin: 5px 0;">{{ auth()->user()->certificates()->count() }}</h2>
            <p style="color: #64748b; margin: 0;">شهادات حصلت عليها</p>
        </div>
        <div class="card" style="text-align: center;">
            <i class="fas fa-trophy" style="font-size: 30px; color: #8b5cf6; margin-bottom: 10px;"></i>
            <h2 style="margin: 5px 0;">{{ auth()->user()->achievements()->count() }}</h2>
            <p style="color: #64748b; margin: 0;">إنجازات محققة</p>
        </div>
    </div>
@endif

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-top: 30px;">
    <div class="card">
        @if(auth()->user()->user_type === 'user')
            <h3 style="margin-top: 0; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-star" style="color: #f59e0b;"></i> الفرص المقترحة لك
            </h3>
            <p style="color: #64748b; text-align: center; padding: 40px 0;">قريباً سيتم عرض الفرص التي تناسب مهاراتك هنا...</p>
        @endif
    </div>

    <div class="card">
        <h3 style="margin-top: 0; margin-bottom: 20px;">الإشعارات الأخيرة</h3>
        @forelse(auth()->user()->notifications()->latest()->take(5)->get() as $notification)
            <div style="padding: 10px; border-bottom: 1px solid #f1f5f9; display: flex; gap: 10px; align-items: start;">
                <i class="fas fa-circle" style="font-size: 8px; color: #6366f1; margin-top: 6px;"></i>
                <div>
                    <div style="font-weight: 600; font-size: 14px;">{{ $notification->title }}</div>
                    <div style="font-size: 12px; color: #64748b;">{{ $notification->created_at->diffForHumans() }}</div>
                </div>
            </div>
        @empty
            <p style="color: #64748b; text-align: center;">لا توجد إشعارات جديدة</p>
        @endforelse
    </div>
</div>
@endsection
