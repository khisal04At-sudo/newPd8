@extends('layouts.app')

@section('title', $user->name . ' - الملف الشخصي')

@section('content')
<div style="background: linear-gradient(135deg, #eef2ff 0%, #f0fdf4 100%); min-height: 100vh; padding: 3rem 0;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 1.5rem;">
        
        <!-- Profile Header -->
        <div class="card" style="padding: 3rem; border-radius: 2rem; margin-bottom: 2rem; text-align: center; position: relative; overflow: hidden;">
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 120px; background: linear-gradient(135deg, #3b82f6, #10b981); opacity: 0.1;"></div>
            
            <div style="position: relative; z-index: 1;">
                <!-- صورة المستخدم -->
                <div onclick="openPhotoModal('{{ $user->avatar_url }}', '{{ $user->name }}')" style="width: 140px; height: 140px; border-radius: 2rem; margin: 0 auto 1.5rem; background: white; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border: 4px solid white; cursor: pointer; transition: transform 0.2s; overflow: hidden;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    <img src="{{ $user->avatar_url }}" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                
                <h1 style="font-size: 2.5rem; font-weight: 800; color: #1e293b; margin: 0 0 0.5rem 0;">{{ $user->name }}</h1>
                <p style="font-size: 1.1rem; color: #64748b; margin: 0 0 1rem 0;">
                    <i class="fas fa-map-marker-alt" style="color: var(--brand-blue);"></i> {{ $user->city->name ?? 'ليبيا' }}
                </p>
                
                @if($user->bio)
                <p style="max-width: 600px; margin: 0 auto 2rem; color: #475569; line-height: 1.8;">{{ $user->bio }}</p>
                @endif
                
                {{-- Action Buttons - Messaging feature coming soon --}}
                {{-- @auth
                    @if(Auth::id() !== $user->id)
                    <div style="display: flex; gap: 1rem; justify-content: center;">
                        <a href="{{ route('messages.show', $user) }}" class="btn-brand">
                            <i class="fas fa-comment"></i> إرسال رسالة
                        </a>
                    </div>
                    @endif
                @endauth --}}
            </div>
        </div>

        <!-- Statistics Cards -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
            <div class="card" style="padding: 1.5rem; text-align: center; background: linear-gradient(135deg, #3b82f6, #2563eb); color: white;">
                <div style="font-size: 2.5rem; font-weight: 800; margin-bottom: 0.5rem;">{{ $stats['total_hours'] }}</div>
                <div style="opacity: 0.9;">ساعة تطوعية</div>
            </div>
            <div class="card" style="padding: 1.5rem; text-align: center; background: linear-gradient(135deg, #10b981, #059669); color: white;">
                <div style="font-size: 2.5rem; font-weight: 800; margin-bottom: 0.5rem;">{{ $stats['certificates_count'] }}</div>
                <div style="opacity: 0.9;">شهادة وإنجاز</div>
            </div>
            <div class="card" style="padding: 1.5rem; text-align: center; background: linear-gradient(135deg, #f59e0b, #d97706); color: white;">
                <div style="font-size: 2.5rem; font-weight: 800; margin-bottom: 0.5rem;">{{ $stats['accepted_applications'] }}</div>
                <div style="opacity: 0.9;">فرصة مقبولة</div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
            <!-- Main Content -->
            <div>
                <!-- Achievements Section -->
                @if($achievements->count() > 0)
                <div class="card" style="padding: 2rem; border-radius: 1.5rem; margin-bottom: 2rem;">
                    <h2 style="font-size: 1.5rem; font-weight: 800; color: #1e293b; margin: 0 0 1.5rem 0; display: flex; align-items: center; gap: 0.75rem;">
                        <i class="fas fa-trophy" style="color: #f59e0b;"></i> الإنجازات
                    </h2>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem;">
                        @foreach($achievements as $achievement)
                        <div style="background: #f8fafc; padding: 1rem; border-radius: 1rem; text-align: center; border: 2px solid #f1f5f9; transition: all 0.2s;" onmouseover="this.style.borderColor='var(--brand-blue)'; this.style.transform='translateY(-4px)'" onmouseout="this.style.borderColor='#f1f5f9'; this.style.transform='none'">
                            <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">🏆</div>
                            <div style="font-weight: 700; color: #1e293b; font-size: 0.9rem; margin-bottom: 0.25rem;">{{ $achievement->title }}</div>
                            <div style="font-size: 0.75rem; color: #64748b;">{{ \Carbon\Carbon::parse($achievement->earned_at)->format('Y/m/d') }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Certificates Section -->
                @if($user->certificates->count() > 0)
                <div class="card" style="padding: 2rem; border-radius: 1.5rem; margin-bottom: 2rem;">
                    <h2 style="font-size: 1.5rem; font-weight: 800; color: #1e293b; margin: 0 0 1.5rem 0; display: flex; align-items: center; gap: 0.75rem;">
                        <i class="fas fa-certificate" style="color: #3b82f6;"></i> الشهادات المكتسبة
                    </h2>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.25rem;">
                        @foreach($user->certificates as $cert)
                        <div style="background: linear-gradient(135deg, #f8fafc, #ffffff); padding: 1.5rem; border-radius: 1rem; border: 2px solid #e2e8f0; transition: all 0.3s;" onmouseover="this.style.borderColor='#3b82f6'; this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 20px rgba(59, 130, 246, 0.15)'" onmouseout="this.style.borderColor='#e2e8f0'; this.style.transform='none'; this.style.boxShadow='none'">
                            <div style="display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 1rem;">
                                <i class="fas fa-award" style="font-size: 2.5rem; color: #3b82f6;"></i>
                                <div style="flex: 1;">
                                    <div style="font-weight: 800; color: #1e293b; font-size: 1rem; margin-bottom: 0.5rem;">{{ $cert->title }}</div>
                                    <div style="font-size: 0.85rem; color: #64748b; margin-bottom: 0.25rem;">
                                        <i class="fas fa-building" style="color: #94a3b8;"></i>
                                        {{ $cert->organization_name }}
                                    </div>
                                    <div style="font-size: 0.85rem; color: #64748b; display: flex; align-items: center; gap: 0.5rem;">
                                        <i class="far fa-calendar-alt" style="color: #94a3b8;"></i>
                                        {{ $cert->issue_date->format('Y/m/d') }}
                                    </div>
                                    @if($cert->certificate_number)
                                    <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 0.25rem;">
                                        رقم: {{ $cert->certificate_number }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @if($cert->file_url || $cert->file)
                            <div style="display: flex; gap: 1rem;">
                                <a href="{{ route('certificates.download', $cert->id) }}" style="flex: 1; text-align: center; padding: 0.85rem; background: #3b82f6; color: white; text-decoration: none; border-radius: 0.85rem; font-weight: 800; font-size: 0.9rem; transition: background 0.2s;" onmouseover="this.style.background='#2563eb'" onmouseout="this.style.background='#3b82f6'">
                                    <i class="fas fa-download" style="margin-left: 0.5rem;"></i> تحميل الشهادة
                                </a>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Recent Activity -->
                @if($recentActivity->count() > 0)
                <div class="card" style="padding: 2rem; border-radius: 1.5rem;">
                    <h2 style="font-size: 1.5rem; font-weight: 800; color: #1e293b; margin: 0 0 1.5rem 0; display: flex; align-items: center; gap: 0.75rem;">
                        <i class="fas fa-history" style="color: var(--volunteer-green);"></i> آخر النشاطات
                    </h2>
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        @foreach($recentActivity as $activity)
                        <div style="padding: 1.25rem; background: #f8fafc; border-radius: 1rem; display: flex; justify-content: space-between; align-items: center;">
                            <div style="flex: 1;">
                                <div style="font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">
                                    <a href="{{ route('opportunities.show', $activity->opportunity) }}" style="color: inherit; text-decoration: none;">{{ $activity->opportunity->title }}</a>
                                </div>
                                <div style="font-size: 0.85rem; color: #64748b;">
                                    <i class="fas fa-building"></i> {{ $activity->opportunity->organization->name }}
                                </div>
                            </div>
                            <div style="text-align: left;">
                                <span style="padding: 0.5rem 1rem; background: #d1fae5; color: #059669; border-radius: 99px; font-weight: 700; font-size: 0.85rem;">مكتمل</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($achievements->count() == 0 && $recentActivity->count() == 0)
                <div class="card" style="padding: 4rem; text-align: center;">
                    <i class="fas fa-user-clock" style="font-size: 4rem; color: #e2e8f0; margin-bottom: 1.5rem;"></i>
                    <h3 style="color: #64748b; font-weight: 700;">لا توجد نشاطات بعد</h3>
                    <p style="color: #94a3b8;">لم يشارك هذا المستخدم في أي فرص حتى الآن</p>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div>
                <!-- User Info Card -->
                <div class="card" style="padding: 1.5rem; border-radius: 1.5rem; margin-bottom: 1.5rem;">
                    <h3 style="font-size: 1.1rem; font-weight: 800; color: #1e293b; margin: 0 0 1.5rem 0;">معلومات المستخدم</h3>
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8fafc; border-radius: 0.75rem;">
                            <i class="fas fa-envelope" style="color: var(--brand-blue);"></i>
                            <div style="font-size: 0.9rem; color: #475569;">{{ $user->email }}</div>
                        </div>
                        @if($user->phone)
                        <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8fafc; border-radius: 0.75rem;">
                            <i class="fas fa-phone" style="color: var(--volunteer-green);"></i>
                            <div style="font-size: 0.9rem; color: #475569;">{{ $user->phone }}</div>
                        </div>
                        @endif
                        <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; background: #f8fafc; border-radius: 0.75rem;">
                            <i class="fas fa-calendar" style="color: #f59e0b;"></i>
                            <div style="font-size: 0.9rem; color: #475569;">انضم {{ $user->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
