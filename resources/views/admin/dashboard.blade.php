@extends('layouts.admin')

@section('title', 'لوحة التحكم')

@section('header', 'الرئيسية')

@section('content')
<div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="stat-card" style="background: white; padding: 1.5rem; border-radius: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <p style="color: #64748b; font-size: 0.9rem; margin-bottom: 0.5rem;">إجمالي المتطوعين</p>
                <h3 style="font-size: 1.8rem; margin: 0;">{{ $stats['total_users'] }}</h3>
            </div>
            <div style="background: #e0e7ff; color: #4f46e5; width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>

    <div class="stat-card" style="background: white; padding: 1.5rem; border-radius: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <p style="color: #64748b; font-size: 0.9rem; margin-bottom: 0.5rem;">المنظمات المسجلة</p>
                <h3 style="font-size: 1.8rem; margin: 0;">{{ $stats['total_organizations'] }}</h3>
            </div>
            <div style="background: #fef3c7; color: #d97706; width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                <i class="fas fa-building"></i>
            </div>
        </div>
    </div>

    <a href="{{ route('admin.organizations.index') }}" class="stat-card" style="background: white; padding: 1.5rem; border-radius: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); text-decoration: none; color: inherit; transition: transform 0.2s;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <p style="color: #64748b; font-size: 0.9rem; margin-bottom: 0.5rem;">في انتظار الاعتماد</p>
                <h3 style="font-size: 1.8rem; margin: 0; color: #dc2626;">{{ $stats['pending_organizations'] }}</h3>
            </div>
            <div style="background: #fee2e2; color: #dc2626; width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </a>

    <div class="stat-card" style="background: white; padding: 1.5rem; border-radius: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <p style="color: #64748b; font-size: 0.9rem; margin-bottom: 0.5rem;">الفرص النشطة</p>
                <h3 style="font-size: 1.8rem; margin: 0;">{{ $stats['total_opportunities'] }}</h3>
            </div>
            <div style="background: #dcfce7; color: #16a34a; width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                <i class="fas fa-briefcase"></i>
            </div>
        </div>
    </div>

    <div class="stat-card" style="background: white; padding: 1.5rem; border-radius: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <p style="color: #64748b; font-size: 0.9rem; margin-bottom: 0.5rem;">الشهادات الصادرة</p>
                <h3 style="font-size: 1.8rem; margin: 0;">{{ $stats['total_certificates'] }}</h3>
            </div>
            <div style="background: #fee2e2; color: #dc2626; width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                <i class="fas fa-award"></i>
            </div>
        </div>
    </div>
</div>

<div style="background: white; padding: 2rem; border-radius: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <h3 style="margin-top: 0; margin-bottom: 1.5rem; color: #1e293b;">أهلا بك في لوحة تحكم أثيرا</h3>
    <p style="color: #64748b;">هنا يمكنك إدارة المتطوعين، المنظمات، والفرص التطوعية. استخدم القائمة الجانبية للتنقل بين الأقسام المختلفة.</p>
</div>
@endsection
