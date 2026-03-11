@extends('layouts.app')

@section('title', 'المؤسسات الشريكة')

@section('content')
<div style="background: linear-gradient(135deg, #f8faff 0%, #f0fdf4 50%, #fdf2f8 100%); min-height: 100vh; padding: 4rem 0;">
    <div style="max-width: 1400px; margin: 0 auto; padding: 0 1.5rem;">
        
        <!-- Header Section -->
        <div style="margin-bottom: 4rem; text-align: center; animation: slideUp 0.6s ease-out;">
            <h1 class="gradient-text" style="font-size: 3.5rem; font-weight: 900; margin-bottom: 1.25rem;">شركاء مسيرة الأثر</h1>
            <p style="color: #64748b; font-size: 1.25rem; max-width: 750px; margin: 0 auto; line-height: 1.8;">
                تعرف على المؤسسات الرائدة التي تساهم في تمكين المجتمعات الليبية من خلال توفير فرص التطوع والتدريب النوعي.
            </p>
        </div>

        <!-- Search and Filters -->
        <div class="glass-card" style="border-radius: 2rem; padding: 2.5rem; margin-bottom: 4rem; box-shadow: 0 20px 50px rgba(0,0,0,0.05); border: 1px solid rgba(255,255,255,0.7); backdrop-filter: blur(20px); animation: fadeIn 0.8s ease-out;">
            <form action="{{ route('organizations.index') }}" method="GET">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; align-items: end;">
                    
                    <!-- Search Input -->
                    <div style="grid-column: span 2;">
                        <label style="display: block; margin-bottom: 0.75rem; font-weight: 800; color: #1e293b; font-size: 0.95rem;">
                            <i class="fas fa-search text-brand-600 ml-2"></i>
                            البحث عن مؤسسة
                        </label>
                        <div style="position: relative;">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="ادخل اسم المؤسسة..." 
                                   style="width: 100%; padding: 0.85rem 3rem 0.85rem 1.25rem; border: 1.5px solid #e2e8f0; border-radius: 1rem; font-size: 1rem; outline: none; transition: all 0.3s; background: white;"
                                   onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 4px rgba(59, 130, 246, 0.1)'"
                                   onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                            <i class="fas fa-search" style="position: absolute; right: 1.25rem; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
                        </div>
                    </div>

                    <!-- City Filter -->
                    <div>
                        <label style="display: block; margin-bottom: 0.75rem; font-weight: 800; color: #1e293b; font-size: 0.95rem;">
                            <i class="fas fa-map-marker-alt text-red-500 ml-2"></i>
                            المدينة
                        </label>
                        <select name="city_id" class="input-modern" onchange="this.form.submit()" style="width: 100%; padding: 0.85rem; border-radius: 1rem; border: 1.5px solid #e2e8f0; background: white; font-weight: 600; color: #475569;">
                            <option value="">كل المدن</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sector Filter -->
                    <div>
                        <label style="display: block; margin-bottom: 0.75rem; font-weight: 800; color: #1e293b; font-size: 0.95rem;">
                            <i class="fas fa-briefcase text-purple-500 ml-2"></i>
                            مجال العمل
                        </label>
                        <select name="sector" class="input-modern" onchange="this.form.submit()" style="width: 100%; padding: 0.85rem; border-radius: 1rem; border: 1.5px solid #e2e8f0; background: white; font-weight: 600; color: #475569;">
                            <option value="">كل القطاعات</option>
                            @foreach($sectors as $sector)
                                <option value="{{ $sector }}" {{ request('sector') == $sector ? 'selected' : '' }}>{{ $sector }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Reset Button -->
                    @if(request()->anyFilled(['search', 'city_id', 'sector']))
                    <div style="text-align: left;">
                        <a href="{{ route('organizations.index') }}" style="color: #64748b; font-size: 0.85rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;" onmouseover="this.style.color='#ef4444'" onmouseout="this.style.color='#64748b'">
                            <i class="fas fa-times-circle"></i> تنظيف الفلاتر
                        </a>
                    </div>
                    @endif
                </div>
            </form>
        </div>

        <!-- Organizations Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 2.5rem;">
            @forelse($organizations as $org)
                <div class="org-card-modern">
                    <!-- Top Ribbon/Background -->
                    <div style="height: 100px; background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); opacity: 0.05; border-radius: 1.5rem 1.5rem 0 0;"></div>
                    
                    <div style="padding: 0 1.75rem 2rem; margin-top: -50px; text-align: center;">
                        <!-- Logo -->
                        <div style="width: 100px; height: 100px; margin: 0 auto 1.5rem; background: white; border-radius: 2rem; padding: 0.5rem; box-shadow: 0 10px 25px rgba(0,0,0,0.1); display: flex; align-items: center; justify-content: center; border: 2px solid white;">
                            <img src="{{ $org->user->avatar_url }}" 
                                 style="max-width: 100%; max-height: 100%; object-fit: contain; border-radius: 1.5rem;">
                        </div>

                        <!-- Info -->
                        <h3 style="font-size: 1.35rem; font-weight: 900; color: #1e293b; margin-bottom: 0.5rem; min-height: 3.2rem; display: flex; align-items: center; justify-content: center;">{{ $org->name }}</h3>
                        
                        <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: #f1f5f9; padding: 0.4rem 0.85rem; border-radius: 2rem; font-size: 0.8rem; font-weight: 700; color: #64748b; margin-bottom: 1.25rem;">
                            <i class="fas fa-tag" style="font-size: 0.7rem;"></i>
                            {{ $org->sector ?? 'قطاع عام' }}
                        </div>

                        <p style="color: #64748b; font-size: 0.9rem; margin-bottom: 1.75rem; line-height: 1.6; height: 3.2rem; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                            {{ $org->description ?? 'لا يوجد وصف متاح حالياً لهذه المؤسسة.' }}
                        </p>

                        <!-- Location & Stats -->
                        <div style="display: flex; justify-content: center; gap: 1.5rem; margin-bottom: 2rem; border-top: 1px solid #f1f5f9; padding-top: 1.25rem;">
                            <div style="text-align: center;">
                                <div style="color: #94a3b8; font-size: 0.75rem; font-weight: 700; margin-bottom: 0.25rem;">المدينة</div>
                                <div style="color: #1e293b; font-size: 0.9rem; font-weight: 800;">
                                    <i class="fas fa-map-marker-alt text-red-500" style="font-size: 0.8rem; margin-left: 4px;"></i>
                                    {{ $org->city ? $org->city->name : 'طرابلس' }}
                                </div>
                            </div>
                            <div style="width: 1px; background: #e2e8f0;"></div>
                            <div style="text-align: center;">
                                <div style="color: #94a3b8; font-size: 0.75rem; font-weight: 700; margin-bottom: 0.25rem;">الفرص</div>
                                <div style="color: #1e293b; font-size: 0.9rem; font-weight: 800;">
                                    <i class="fas fa-star text-amber-500" style="font-size: 0.8rem; margin-left: 4px;"></i>
                                    {{ $org->opportunities_count ?? 0 }}
                                </div>
                            </div>
                        </div>

                        <!-- Action -->
                        <a href="{{ route('organizations.profile', $org->id) }}" class="btn-org-secondary">
                            عرض الملف التعريفي
                            <i class="fas fa-arrow-left" style="margin-right: 8px; font-size: 0.8rem;"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 6rem 2rem; background: white; border-radius: 2.5rem; box-shadow: 0 10px 40px rgba(0,0,0,0.05);">
                    <div style="font-size: 5rem; color: #e2e8f0; margin-bottom: 2rem; animation: bounce 2s infinite;">
                        <i class="fas fa-building-circle-exclamation"></i>
                    </div>
                    <h3 style="color: #1e293b; font-weight: 900; font-size: 2rem; margin-bottom: 1rem;">لم نجد مؤسسات تطابق بحثك</h3>
                    <p style="color: #64748b; font-size: 1.15rem; margin-bottom: 2.5rem;">جرب الكتابة بشكل مختلف أو اختر مدينة أخرى لاستكشاف الشركاء.</p>
                    <a href="{{ route('organizations.index') }}" class="btn-org-primary">
                        <i class="fas fa-redo ml-2"></i> تعيين البحث من جديد
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($organizations->hasPages())
        <div style="margin-top: 5rem; display: flex; justify-content: center;">
            <div style="background: white; padding: 0.75rem 1.5rem; border-radius: 1.25rem; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #f1f5f9;">
                {{ $organizations->links() }}
            </div>
        </div>
        @endif

    </div>
</div>

<style>
    .gradient-text {
        background: linear-gradient(135deg, #10b981 0%, #3b82f6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .org-card-modern {
        background: white;
        border-radius: 2rem;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        border: 1px solid #f1f5f9;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .org-card-modern:hover {
        transform: translateY(-12px);
        box-shadow: 0 25px 50px rgba(0,0,0,0.1);
        border-color: rgba(59, 130, 246, 0.3);
    }

    .btn-org-primary {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 0.85rem 2.5rem;
        border-radius: 1rem;
        font-weight: 800;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s;
        box-shadow: 0 10px 20px -5px rgba(16, 185, 129, 0.4);
    }

    .btn-org-primary:hover {
        transform: scale(1.05);
        box-shadow: 0 15px 30px -5px rgba(16, 185, 129, 0.5);
    }

    .btn-org-secondary {
        background: #f8fafc;
        color: #1e293b;
        padding: 0.9rem 1.5rem;
        border-radius: 1.25rem;
        font-weight: 800;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        transition: all 0.3s;
        border: 1.5px solid #e2e8f0;
    }

    .btn-org-secondary:hover {
        background: #1e293b;
        color: white;
        border-color: #1e293b;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(40px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
</style>
@endsection
