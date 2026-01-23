@extends('layouts.app')

@section('title', 'تصفح الفرص')

@section('content')
<div style="background: linear-gradient(135deg, #eef2ff 0%, #f0fdf4 50%, #fff1f2 100%); min-height: 100vh; padding: 3rem 0;">
    <div style="max-width: 1400px; margin: 0 auto; padding: 0 1.5rem;">
        
        <!-- Header & Search -->
        <div style="margin-bottom: 3rem; text-align: center; animation: slideUp 0.6s ease-out;">
            <h1 class="gradient-text" style="font-size: 3.5rem; font-weight: 900; margin-bottom: 1rem;">اكتشف فرصتك القادمة</h1>
            <p style="color: #64748b; font-size: 1.2rem; max-width: 700px; margin: 0 auto;">تصفح المئات من فرص التطوع والتدريب المتاحة في مختلف المجالات والمدن الليبية</p>
        </div>

        <!-- Filters Section -->
        <div class="glass-card" style="border-radius: 1.5rem; padding: 2.5rem; margin-bottom: 3rem; animation: scaleIn 0.5s ease-out;">
            <form action="{{ route('opportunities.index') }}" method="GET">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; align-items: end;">
                    
                    <!-- Type Filter -->
                    <div>
                        <label style="display: block; margin-bottom: 0.75rem; font-weight: 700; color: #1e293b; font-size: 0.95rem;">
                            <i class="fas fa-filter text-brand-600 ml-2"></i>
                            نوع الفرصة
                        </label>
                        <select name="type" class="input-modern">
                            <option value="all" {{ request('type') == 'all' ? 'selected' : '' }}>الكل</option>
                            <option value="volunteering" {{ request('type') == 'volunteering' ? 'selected' : '' }}>تطوع</option>
                            <option value="training" {{ request('type') == 'training' ? 'selected' : '' }}>تدريب</option>
                        </select>
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label style="display: block; margin-bottom: 0.75rem; font-weight: 700; color: #1e293b; font-size: 0.95rem;">
                            <i class="fas fa-th-large text-volunteer-600 ml-2"></i>
                            التصنيف
                        </label>
                        <select name="category" class="input-modern">
                            <option value="all">الكل</option>
                            <option value="مساعدة إنسانية" {{ request('category') == 'مساعدة إنسانية' ? 'selected' : '' }}>مساعدة إنسانية</option>
                            <option value="تعليم" {{ request('category') == 'تعليم' ? 'selected' : '' }}>تعليم</option>
                            <option value="بيئة" {{ request('category') == 'بيئة' ? 'selected' : '' }}>بيئة</option>
                            <option value="ريادة أعمال" {{ request('category') == 'ريادة أعمال' ? 'selected' : '' }}>ريادة أعمال</option>
                            <option value="رياضة" {{ request('category') == 'رياضة' ? 'selected' : '' }}>رياضة</option>
                            <option value="فنون" {{ request('category') == 'فنون' ? 'selected' : '' }}>فنون</option>
                            <option value="صحة" {{ request('category') == 'صحة' ? 'selected' : '' }}>صحة</option>
                            <option value="تكنولوجيا" {{ request('category') == 'تكنولوجيا' ? 'selected' : '' }}>تكنولوجيا</option>
                        </select>
                    </div>

                    <!-- City Filter -->
                    <div>
                        <label style="display: block; margin-bottom: 0.75rem; font-weight: 700; color: #1e293b; font-size: 0.95rem;">
                            <i class="fas fa-map-marker-alt text-red-600 ml-2"></i>
                            المدينة
                        </label>
                        <select name="city_id" class="input-modern">
                            <option value="">كل المدن</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sort Filter -->
                    <div>
                        <label style="display: block; margin-bottom: 0.75rem; font-weight: 700; color: #1e293b; font-size: 0.95rem;">
                            <i class="fas fa-sort text-purple-600 ml-2"></i>
                            الترتيب حسب
                        </label>
                        <select name="time_filter" class="input-modern">
                            <option value="newest" {{ request('time_filter') == 'newest' ? 'selected' : '' }}>الأحدث أولاً</option>
                            <option value="ending_soon" {{ request('time_filter') == 'ending_soon' ? 'selected' : '' }}>تنتهي قريباً</option>
                            <option value="completed" {{ request('time_filter') == 'completed' ? 'selected' : '' }}>الفرص السابقة</option>
                        </select>
                    </div>

                    <!-- Action Button -->
                    <div>
                        <button type="submit" class="btn-brand w-full">
                            <i class="fas fa-search ml-2"></i> بحث
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Opportunities Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 2.5rem;">
            @forelse($opportunities as $opp)
                <div class="card-hover" style="background: white; border-radius: 1.5rem; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border: 1px solid #f1f5f9; position: relative;">
                    
                    <!-- Category Badge -->
                    <div style="position: absolute; top: 1.5rem; right: 1.5rem;">
                        <span style="background: rgba(79, 70, 229, 0.1); color: #4f46e5; padding: 0.4rem 0.8rem; border-radius: 2rem; font-size: 0.75rem; font-weight: 700; backdrop-filter: blur(4px);">
                            {{ $opp->type == 'volunteering' ? 'تطوع' : 'تدريب' }}
                        </span>
                    </div>

                    <div style="padding: 2rem;">
                        <!-- Organization -->
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                            <img src="{{ url($opp->organization->logo_url ?? 'assets/default-logo.png') }}" style="width: 2.5rem; height: 2.5rem; border-radius: 0.5rem; object-fit: cover;">
                            <div style="font-size: 0.9rem; font-weight: 600; color: #64748b;">{{ $opp->organization->name }}</div>
                        </div>

                        <h3 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin-bottom: 1rem; line-height: 1.4;">{{ $opp->title }}</h3>
                        <p style="color: #64748b; font-size: 0.95rem; margin-bottom: 1.5rem; line-height: 1.6; height: 3rem; overflow: hidden;">
                            {{ Str::limit($opp->description, 100) }}
                        </p>

                        <!-- Info Tags -->
                        <div style="display: flex; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 2rem;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; color: #64748b;">
                                <i class="fas fa-map-marker-alt" style="color: #4f46e5;"></i>
                                {{ $opp->city->name }}
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; color: #64748b;">
                                <i class="fas fa-clock" style="color: #4f46e5;"></i>
                                {{ $opp->total_hours }} ساعة
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; color: #64748b;">
                                <i class="fas fa-users" style="color: #4f46e5;"></i>
                                {{ $opp->seats }} مقعد متاح
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #f1f5f9; padding-top: 1.5rem;">
                            <a href="{{ route('opportunities.show', $opp) }}" style="text-decoration: none; color: #4f46e5; font-weight: 700; font-size: 0.95rem;">
                                عرض التفاصيل <i class="fas fa-arrow-left" style="margin-right: 5px; font-size: 0.8rem;"></i>
                            </a>
                            <button style="background: none; border: none; color: #94a3b8; cursor: pointer; transition: color 0.2s;" class="hover-accent" title="حفظ للرجوع">
                                <i class="far fa-bookmark" style="font-size: 1.1rem;"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state" style="grid-column: 1 / -1; text-align: center; padding: 6rem 2rem; background: white; border-radius: 2rem; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
                    <div style="font-size: 5rem; color: #e2e8f0; margin-bottom: 2rem;">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3 style="color: #1e293b; font-weight: 800; font-size: 1.75rem; margin-bottom: 1rem;">لا توجد فرص تطابق هذه الفلاتر حالياً</h3>
                    <p style="color: #64748b; font-size: 1.1rem; margin-bottom: 2rem;">جرب تغيير خيارات البحث أو العودة لاحقاً للعثور على فرص جديدة</p>
                    <a href="{{ route('opportunities.index') }}" class="btn-volunteer" style="display: inline-block;">
                        <i class="fas fa-redo ml-2"></i>
                        إعادة تعيين الفلاتر
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div style="margin-top: 4rem; display: flex; justify-content: center;">
            {{ $opportunities->links() }}
        </div>
    </div>
</div>

<style>
    .hover-accent:hover { color: #f43f5e !important; }
    
    .gradient-text {
        background: linear-gradient(135deg, #16a34a 0%, #4338ca 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    /* Empty state animation */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .empty-state {
        animation: fadeIn 0.6s ease-out;
    }
</style>
@endsection
