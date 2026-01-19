@extends('layouts.app')

@section('title', 'تصفح الفرص')

@section('content')
<div style="background: #f8fafc; min-height: 100vh; padding: 2rem 0;">
    <div style="max-width: 1300px; margin: 0 auto; padding: 0 1.5rem;">
        
        <!-- Header & Search -->
        <div style="margin-bottom: 3rem; text-align: center;">
            <h1 style="font-size: 2.5rem; font-weight: 800; color: #1e293b; margin-bottom: 1rem;">اكتشف فرصتك القادمة</h1>
            <p style="color: #64748b; font-size: 1.1rem; max-width: 600px; margin: 0 auto;">تصفح المئات من فرص التطوع والتدريب المتاحة في مختلف المجالات والمدن الليبية.</p>
        </div>

        <!-- Filters Section -->
        <div style="background: white; border-radius: 1.5rem; padding: 2rem; box-shadow: 0 4px 20px rgba(0,0,0,0.05); margin-bottom: 3rem;">
            <form action="{{ route('opportunities.index') }}" method="GET">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; align-items: end;">
                    
                    <!-- Search Field -->
                    <!-- (Search by Title logic can be added to Controller) -->
                    
                    <!-- Type Filter -->
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #475569;">نوع الفرصة</label>
                        <select name="type" style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; outline: none; transition: border-color 0.2s;">
                            <option value="all" {{ request('type') == 'all' ? 'selected' : '' }}>الكل</option>
                            <option value="volunteering" {{ request('type') == 'volunteering' ? 'selected' : '' }}>تطوع</option>
                            <option value="training" {{ request('type') == 'training' ? 'selected' : '' }}>تدريب</option>
                        </select>
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #475569;">التصنيف</label>
                        <select name="category" style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; outline: none;">
                            <option value="all">الكل</option>
                            <option value="help" {{ request('category') == 'help' ? 'selected' : '' }}>مساعدة إنسانية</option>
                            <option value="education" {{ request('category') == 'education' ? 'selected' : '' }}>تعليم</option>
                            <option value="environment" {{ request('category') == 'environment' ? 'selected' : '' }}>بيئة</option>
                            <option value="entrepreneurship" {{ request('category') == 'entrepreneurship' ? 'selected' : '' }}>ريادة أعمال</option>
                            <option value="sports" {{ request('category') == 'sports' ? 'selected' : '' }}>رياضة</option>
                            <option value="arts" {{ request('category') == 'arts' ? 'selected' : '' }}>فنون</option>
                            <option value="health" {{ request('category') == 'health' ? 'selected' : '' }}>صحة</option>
                            <option value="technology" {{ request('category') == 'technology' ? 'selected' : '' }}>تكنولوجيا</option>
                        </select>
                    </div>

                    <!-- City Filter -->
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #475569;">المدينة</label>
                        <select name="city_id" style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; outline: none;">
                            <option value="">كل المدن</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sort Filter -->
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #475569;">الترتيب حسب</label>
                        <select name="time_filter" style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; outline: none;">
                            <option value="newest" {{ request('time_filter') == 'newest' ? 'selected' : '' }}>الأحدث أولاً</option>
                            <option value="ending_soon" {{ request('time_filter') == 'ending_soon' ? 'selected' : '' }}>تنتهي قريباً</option>
                            <option value="completed" {{ request('time_filter') == 'completed' ? 'selected' : '' }}>الفرص السابقة</option>
                        </select>
                    </div>

                    <!-- Action Button -->
                    <div style="grid-column: span 1;">
                        <button type="submit" style="width: 100%; padding: 0.85rem; background: #4f46e5; color: white; border: none; border-radius: 0.75rem; font-weight: 600; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);">
                            <i class="fas fa-filter"></i> تطبيق الفلتر
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Opportunities Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 2rem;">
            @forelse($opportunities as $opp)
                <div style="background: white; border-radius: 1.25rem; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; transition: transform 0.3s; position: relative;" 
                     onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                    
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
                <div style="grid-column: 1 / -1; text-align: center; padding: 5rem 0;">
                    <i class="fas fa-search" style="font-size: 3rem; color: #e2e8f0; margin-bottom: 1.5rem;"></i>
                    <h3 style="color: #64748b; font-weight: 600;">لا توجد فرص تطابق هذه الفلاتر حالياً</h3>
                    <p style="color: #94a3b8;">جرب تغيير خيارات البحث أو العودة لاحقاً.</p>
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
</style>
@endsection
