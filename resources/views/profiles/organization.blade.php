@extends('layouts.app')

@section('title', $organization->name . ' - ملف المؤسسة')

@section('content')
<div style="background: #f8fafc; min-height: 100vh; padding: 3rem 0;">
    <div style="max-width: 1400px; margin: 0 auto; padding: 0 1.5rem;">
        
        <!-- Organization Header -->
        <div class="card" style="padding: 3rem; border-radius: 2rem; margin-bottom: 3rem; position: relative; overflow: hidden;">
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 150px; background: linear-gradient(135deg, #3b82f6, #10b981); opacity: 0.05;"></div>
            
            <div style="position: relative; z-index: 1; display: flex; align-items: start; gap: 2rem;">
                <!-- Logo -->
                <div style="flex-shrink: 0;" onclick="openPhotoModal('{{ $organization->user->avatar_url }}', '{{ $organization->name }}')">
                    <img src="{{ $organization->user->avatar_url }}" style="width: 150px; height: 150px; border-radius: 1.5rem; object-fit: cover; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border: 4px solid white; cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                </div>
                
                <!-- Info -->
                <div style="flex: 1;">
                    <h1 style="font-size: 2.5rem; font-weight: 900; color: #1e293b; margin: 0 0 1rem 0;">{{ $organization->name }}</h1>
                    
                    <div style="display: flex; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem;">
                        <span style="padding: 0.5rem 1.25rem; background: #eff6ff; color: #2563eb; border-radius: 99px; font-weight: 700; font-size: 0.9rem;">
                            <i class="fas fa-building"></i> {{ $organization->type ?? 'منظمة' }}
                        </span>
                        <span style="padding: 0.5rem 1.25rem; background: #f0fdf4; color: #059669; border-radius: 99px; font-weight: 700; font-size: 0.9rem;">
                            <i class="fas fa-map-marker-alt"></i> {{ $organization->city->name ?? 'ليبيا' }}
                        </span>
                        <span style="padding: 0.5rem 1.25rem; background: #fef3c7; color: #d97706; border-radius: 99px; font-weight: 700; font-size: 0.9rem;">
                            <i class="fas fa-check-circle"></i> مؤسسة معتمدة
                        </span>
                    </div>
                    
                    @if($organization->description)
                    <p style="color: #475569; line-height: 1.8; font-size: 1.05rem; margin-bottom: 1.5rem;">{{ $organization->description }}</p>
                    @endif
                    
                    @if($organization->social_links)
                    <div style="display: flex; gap: 1rem; align-items: center; margin-top: 1rem;">
                        @if(!empty($organization->social_links['website']))
                        <a href="{{ $organization->social_links['website'] }}" target="_blank" class="btn-brand">
                            <i class="fas fa-globe"></i> الموقع الإلكتروني
                        </a>
                        @endif
                        
                        <div style="display: flex; gap: 0.75rem;">
                            @foreach($organization->social_links as $platform => $url)
                                @if($url && $platform !== 'website')
                                <a href="{{ $url }}" target="_blank" style="width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; background: white; color: #64748b; border: 1px solid #e2e8f0; transition: all 0.3s; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);" onmouseover="this.style.background='#f8fafc'; this.style.color='#2563eb'; this.style.transform='translateY(-3px)'" onmouseout="this.style.background='white'; this.style.color='#64748b'; this.style.transform='none'">
                                    @if($platform == 'facebook') <i class="fab fa-facebook-f"></i>
                                    @elseif($platform == 'twitter') <i class="fab fa-twitter"></i>
                                    @elseif($platform == 'instagram') <i class="fab fa-instagram"></i>
                                    @else <i class="fas fa-link"></i> @endif
                                </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
            <div class="card" style="padding: 2rem; text-align: center; background: linear-gradient(135deg, #3b82f6, #2563eb); color: white;">
                <div style="font-size: 3rem; font-weight: 900; margin-bottom: 0.5rem;">{{ $stats['total_opportunities'] }}</div>
                <div style="font-size: 1.1rem; opacity: 0.9;">فرصة منشورة</div>
            </div>
            <div class="card" style="padding: 2rem; text-align: center; background: linear-gradient(135deg, #10b981, #059669); color: white;">
                <div style="font-size: 3rem; font-weight: 900; margin-bottom: 0.5rem;">{{ $stats['total_volunteers'] }}</div>
                <div style="font-size: 1.1rem; opacity: 0.9;">متطوع ومتدرب</div>
            </div>
            <div class="card" style="padding: 2rem; text-align: center; background: linear-gradient(135deg, #f59e0b, #d97706); color: white;">
                <div style="font-size: 3rem; font-weight: 900; margin-bottom: 0.5rem;">{{ number_format($stats['total_hours']) }}</div>
                <div style="font-size: 1.1rem; opacity: 0.9;">ساعة معتمدة</div>
            </div>
        </div>

        <!-- Active Opportunities -->
        @if($activeOpportunities->count() > 0)
        <div style="margin-bottom: 3rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h2 style="font-size: 1.75rem; font-weight: 800; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 0.75rem;">
                    <i class="fas fa-fire" style="color: #ef4444;"></i> الفرص المتاحة حالياً
                </h2>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 2rem;">
                @foreach($activeOpportunities as $opp)
                <div class="card" style="padding: 1.75rem; transition: all 0.2s; position: relative;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 24px rgba(0,0,0,0.1)'" onmouseout="this.style.transform='none'; this.style.boxShadow=''">
                    <div style="position: absolute; top: 1rem; left: 1rem;">
                        <span style="padding: 0.35rem 0.75rem; background: {{ $opp->type == 'volunteering' ? '#d1fae5' : '#dbeafe' }}; color: {{ $opp->type == 'volunteering' ? '#059669' : '#2563eb' }}; border-radius: 99px; font-size: 0.75rem; font-weight: 700;">
                            {{ $opp->type == 'volunteering' ? 'تطوع' : 'تدريب' }}
                        </span>
                    </div>
                    
                    <h3 style="font-size: 1.15rem; font-weight: 700; color: #1e293b; margin: 2rem 0 1rem 0; line-height: 1.4;">
                        <a href="{{ route('opportunities.show', $opp) }}" style="color: inherit; text-decoration: none;">{{ $opp->title }}</a>
                    </h3>
                    
                    <p style="color: #64748b; font-size: 0.9rem; line-height: 1.6; margin-bottom: 1.25rem; height: 3rem; overflow: hidden;">
                        {{ Str::limit($opp->description, 100) }}
                    </p>
                    
                    <div style="display: flex; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 1.25rem;">
                        <div style="font-size: 0.8rem; color: #64748b;">
                            <i class="fas fa-map-marker-alt" style="color: var(--brand-blue);"></i> {{ $opp->city->name ?? 'عن بعد' }}
                        </div>
                        <div style="font-size: 0.8rem; color: #64748b;">
                            <i class="fas fa-clock" style="color: var(--brand-blue);"></i> {{ $opp->total_hours }} ساعة
                        </div>
                        <div style="font-size: 0.8rem; color: #64748b;">
                            <i class="fas fa-users" style="color: var(--brand-blue);"></i> {{ $opp->seats }} مقعد
                        </div>
                    </div>
                    
                    <a href="{{ route('opportunities.show', $opp) }}" class="btn-volunteer" style="display: block; text-align: center;">
                        عرض التفاصيل
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Past Opportunities -->
        @if($pastOpportunities->count() > 0)
        <div>
            <h2 style="font-size: 1.75rem; font-weight: 800; color: #1e293b; margin: 0 0 2rem 0; display: flex; align-items: center; gap: 0.75rem;">
                <i class="fas fa-history" style="color: #64748b;"></i> الفرص المكتملة
            </h2>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem;">
                @foreach($pastOpportunities as $opp)
                <div style="background: white; padding: 1.5rem; border-radius: 1rem; border: 1px solid #f1f5f9;">
                    <h4 style="font-size: 1rem; font-weight: 700; color: #64748b; margin: 0 0 0.5rem 0;">{{ $opp->title }}</h4>
                    <div style="font-size: 0.85rem; color: #94a3b8;">
                        <i class="fas fa-calendar"></i> انتهت {{ $opp->end_date->diffForHumans() }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($activeOpportunities->count() == 0 && $pastOpportunities->count() == 0)
        <div class="card" style="padding: 4rem; text-align: center;">
            <i class="fas fa-inbox" style="font-size: 4rem; color: #e2e8f0; margin-bottom: 1.5rem;"></i>
            <h3 style="color: #64748b; font-weight: 700;">لا توجد فرص منشورة</h3>
            <p style="color: #94a3b8;">لم تنشر هذه المؤسسة أي فرص بعد</p>
        </div>
        @endif
    </div>
</div>
@endsection
