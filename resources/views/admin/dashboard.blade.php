@extends('layouts.admin')

@section('title', 'لوحة التحكم')
@section('header', 'نظرة عامة على النظام')

@section('content')
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 1.75rem; margin-bottom: 2.5rem;">
    <!-- Total Users -->
    <div class="card" style="padding: 1.75rem; display: flex; align-items: center; gap: 1.5rem; border-right: 4px solid var(--brand-blue);">
        <div style="width: 56px; height: 56px; background: #eff6ff; color: var(--brand-blue); border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
            <i class="fas fa-users"></i>
        </div>
        <div>
            <p style="color: #64748b; font-size: 0.85rem; font-weight: 700; margin: 0 0 0.25rem 0;">إجمالي المتطوعين</p>
            <h3 style="font-size: 1.75rem; font-weight: 800; margin: 0; color: #1e293b;">{{ number_format($stats['total_users']) }}</h3>
        </div>
    </div>

    <!-- Registered Organizations -->
    <div class="card" style="padding: 1.75rem; display: flex; align-items: center; gap: 1.5rem; border-right: 4px solid var(--volunteer-green);">
        <div style="width: 56px; height: 56px; background: #f0fdf4; color: var(--volunteer-green); border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
            <i class="fas fa-building"></i>
        </div>
        <div>
            <p style="color: #64748b; font-size: 0.85rem; font-weight: 700; margin: 0 0 0.25rem 0;">المنظمات المسجلة</p>
            <h3 style="font-size: 1.75rem; font-weight: 800; margin: 0; color: #1e293b;">{{ number_format($stats['total_organizations']) }}</h3>
        </div>
    </div>

    <!-- Pending Organizations -->
    <a href="{{ route('admin.organizations.index') }}" class="card" style="padding: 1.75rem; display: flex; align-items: center; gap: 1.5rem; border-right: 4px solid #ef4444; text-decoration: none; transition: transform 0.2s;">
        <div style="width: 56px; height: 56px; background: #fef2f2; color: #ef4444; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
            <i class="fas fa-shield-clock"></i>
        </div>
        <div>
            <p style="color: #64748b; font-size: 0.85rem; font-weight: 700; margin: 0 0 0.25rem 0;">في انتظار الاعتماد</p>
            <h3 style="font-size: 1.75rem; font-weight: 800; margin: 0; color: #ef4444;">{{ $stats['pending_organizations'] }}</h3>
        </div>
    </a>

    <!-- Active Opportunities -->
    <div class="card" style="padding: 1.75rem; display: flex; align-items: center; gap: 1.5rem; border-right: 4px solid #f59e0b;">
        <div style="width: 56px; height: 56px; background: #fffbeb; color: #f59e0b; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
            <i class="fas fa-briefcase"></i>
        </div>
        <div>
            <p style="color: #64748b; font-size: 0.85rem; font-weight: 700; margin: 0 0 0.25rem 0;">الفرص النشطة</p>
            <h3 style="font-size: 1.75rem; font-weight: 800; margin: 0; color: #1e293b;">{{ number_format($stats['total_opportunities']) }}</h3>
        </div>
    </div>
</div>

<div class="card" style="padding: 3rem; background: linear-gradient(135deg, white 0%, #f8fafc 100%); text-align: center; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -50px; left: -50px; width: 200px; height: 200px; background: var(--volunteer-green); opacity: 0.03; border-radius: 50%;"></div>
    <div style="position: absolute; bottom: -50px; right: -50px; width: 200px; height: 200px; background: var(--brand-blue); opacity: 0.03; border-radius: 50%;"></div>
    
    <div style="width: 80px; height: 80px; background: white; border-radius: 20px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05); display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem;">
        <i class="fas fa-feather-pointed" style="font-size: 2rem; background: linear-gradient(135deg, var(--volunteer-green), var(--brand-blue)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
    </div>
    
    <h3 style="margin: 0 0 1rem 0; font-size: 1.75rem; font-weight: 800; color: #1e293b;">مرحباً بك في لوحة تحكم أثيرا</h3>
    <p style="max-width: 600px; margin: 0 auto 2.5rem; color: #64748b; line-height: 1.8; font-size: 1.1rem;">
        من هنا يمكنك متابعة أداء المنصة بشكل كامل، مراجعة طلبات المؤسسات الجديدة، والموافقة على الفرص التطوعية لضمان جودة المحتوى المقدم للمتطوعين.
    </p>
    
    <div style="display: flex; justify-content: center; gap: 1rem;">
        <a href="{{ route('admin.opportunities.index') }}" style="background: var(--volunteer-green); color: white; padding: 0.875rem 2rem; border-radius: 0.75rem; text-decoration: none; font-weight: 700; transition: transform 0.2s; box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.2);" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='none'">
            مراجعة الفرص
        </a>
        <a href="{{ route('admin.organizations.index') }}" style="background: white; color: #1e293b; padding: 0.875rem 2rem; border-radius: 0.75rem; text-decoration: none; font-weight: 700; border: 1px solid #e2e8f0; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='none'">
            المنظمات قيد الانتظار
        </a>
    </div>
</div>
@endsection
