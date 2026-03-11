@extends('layouts.dashboard')

@section('title', 'الملف المؤسسي')

@section('content')
<div style="animation: fadeIn 0.8s ease-out;">
    <!-- Profile Header / Cover Section -->
    <div class="glass-card" style="margin-bottom: 2rem; padding: 0; overflow: hidden; border-radius: 2rem; position: relative;">
        <div style="height: 160px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); position: relative;">
            <div style="position: absolute; bottom: -50px; right: 40px; display: flex; align-items: flex-end; gap: 1.5rem;">
                <div style="position: relative; cursor: pointer;" onclick="openPhotoModal('{{ $user->avatar_url }}', '{{ $user->organization->name }}')">
                    <img src="{{ $user->avatar_url }}" style="width: 140px; height: 140px; border-radius: 2rem; object-fit: cover; border: 6px solid white; box-shadow: 0 10px 25px rgba(0,0,0,0.1); background: white; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    @if($user->organization->verified)
                    <div style="position: absolute; top: -10px; right: -10px; width: 34px; height: 34px; background: #10b981; border: 4px solid white; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;" title="مؤسسة معتمدة">
                        <i class="fas fa-check" style="font-size: 0.8rem;"></i>
                    </div>
                    @endif
                </div>
                <div style="padding-bottom: 10px;">
                    <h1 style="font-size: 2rem; font-weight: 800; color: white; margin-bottom: 0.25rem; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">{{ $user->organization->name }}</h1>
                    <div style="display: flex; align-items: center; gap: 1rem; color: rgba(255,255,255,0.9); font-size: 0.95rem;">
                        <span><i class="fas fa-map-marker-alt ml-1"></i> {{ $user->city->name ?? 'غير محدد' }}</span>
                        <span>•</span>
                        <span><i class="fas fa-building ml-1"></i> {{ $user->organization->sector == 'private' ? 'قطاع خاص' : ($user->organization->sector == 'public' ? 'قطاع عام' : ($user->organization->sector == 'non_profit' ? 'غير ربحية' : 'مبادرة تطوعية')) }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div style="padding: 70px 40px 30px; display: flex; justify-content: flex-end; gap: 1rem; align-items: center;">
            <div style="display: flex; gap: 2rem; margin-left: auto; margin-right: 180px;">
                <div style="text-align: center;">
                    <div style="font-size: 1.25rem; font-weight: 800; color: #1e293b;">{{ $user->organization->opportunities->count() }}</div>
                    <div style="font-size: 0.8rem; color: #64748b; font-weight: 600;">فرصة منشورة</div>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 1.25rem; font-weight: 800; color: #1e293b;">{{ $user->organization->rating }}</div>
                    <div style="font-size: 0.8rem; color: #64748b; font-weight: 600;">التقييم</div>
                </div>
            </div>
            <div style="display: flex; gap: 0.75rem;">
                <a href="{{ route('organization.profile.edit') }}" class="btn-brand" style="padding: 0.75rem 1.5rem; border-radius: 1rem; font-size: 0.95rem; background: #f59e0b;">
                    <i class="fas fa-edit ml-2"></i> تعديل البيانات
                </a>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
        <!-- Left Column: Description & Media -->
        <div style="display: flex; flex-direction: column; gap: 2rem;">
            <!-- Description Section -->
            <div class="glass-card" style="padding: 2rem; border-radius: 1.5rem;">
                <h3 style="font-size: 1.25rem; font-weight: 800; color: #1e293b; margin-bottom: 1.25rem;">عن المؤسسة</h3>
                <p style="color: #475569; line-height: 1.8; font-size: 1.05rem; margin: 0; white-space: pre-wrap;">{{ $user->organization->description ?: 'لا يوجد وصف متاح للمؤسسة حالياً.' }}</p>
                
                @if($user->organization->social_links)
                <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                    @foreach($user->organization->social_links as $platform => $url)
                        @if($url)
                        <a href="{{ $url }}" target="_blank" style="width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; transition: all 0.3s;" class="social-icon">
                            @if($platform == 'facebook') <i class="fab fa-facebook-f"></i>
                            @elseif($platform == 'twitter') <i class="fab fa-twitter"></i>
                            @elseif($platform == 'instagram') <i class="fab fa-instagram"></i>
                            @elseif($platform == 'website') <i class="fas fa-globe"></i>
                            @else <i class="fas fa-link"></i> @endif
                        </a>
                        @endif
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Additional Information Grid -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="glass-card" style="padding: 1.5rem; border-radius: 1.5rem;">
                    <h4 style="font-size: 1rem; font-weight: 700; color: #1e293b; margin-bottom: 1rem;">تفاصيل التسجيل</h4>
                    <div style="display: flex; flex-direction: column; gap: 0.75rem; font-size: 0.9rem;">
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #64748b;">رقم القيد:</span>
                            <span style="font-weight: 600;">{{ $user->organization->registration_number ?? 'غير متاح' }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #64748b;">تاريخ التأسيس:</span>
                            <span style="font-weight: 600;">{{ $user->organization->established_at ? $user->organization->established_at->format('Y/m/d') : 'غير متاح' }}</span>
                        </div>
                    </div>
                </div>
                <div class="glass-card" style="padding: 1.5rem; border-radius: 1.5rem;">
                    <h4 style="font-size: 1rem; font-weight: 700; color: #1e293b; margin-bottom: 1rem;">مجالات العمل</h4>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                        <span style="background: #fffcf0; color: #d97706; border: 1px solid #fef3c7; padding: 0.4rem 0.8rem; border-radius: 0.75rem; font-size: 0.8rem; font-weight: 600;">
                            {{ $user->organization->organization_type == 'volunteering' ? 'تطوعية' : ($user->organization->organization_type == 'training' ? 'تدريبية' : 'تطوعية وتدريبية') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Contact & Info -->
        <div style="display: flex; flex-direction: column; gap: 2rem;">
            <!-- Contact Card -->
            <div class="glass-card" style="padding: 1.8rem; border-radius: 1.5rem;">
                <h3 style="font-size: 1.1rem; font-weight: 800; color: #1e293b; margin-bottom: 1.5rem;">معلومات التواصل</h3>
                <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 40px; height: 40px; border-radius: 10px; background: #f0fdf4; color: #16a34a; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div>
                            <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 600;">رقم الهاتف</div>
                            <div style="font-weight: 700; color: #1e293b; direction: ltr;">{{ $user->organization->phone ?? 'غير متاح' }}</div>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 40px; height: 40px; border-radius: 10px; background: #eff6ff; color: #3b82f6; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 600;">البريد الإلكتروني</div>
                            <div style="font-weight: 700; color: #1e293b;">{{ $user->email }}</div>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 40px; height: 40px; border-radius: 10px; background: #fff7ed; color: #ea580c; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 600;">العنوان</div>
                            <div style="font-weight: 700; color: #1e293b; font-size: 0.9rem;">{{ $user->organization->address ?? 'غير متاح' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Card -->
            <div class="glass-card" style="padding: 1.5rem; border-radius: 1.5rem; text-align: center;">
                <div style="width: 60px; height: 60px; border-radius: 50%; background: {{ $user->organization->verified ? '#f0fdf4' : '#fff7ed' }}; color: {{ $user->organization->verified ? '#16a34a' : '#ea580c' }}; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; font-size: 1.5rem;">
                    <i class="fas {{ $user->organization->verified ? 'fa-shield-check' : 'fa-hourglass-half' }}"></i>
                </div>
                <h4 style="margin: 0; color: #1e293b; font-weight: 700;">حالة الاعتماد</h4>
                <p style="font-size: 0.85rem; color: #64748b; margin: 0.5rem 0 0;">
                    {{ $user->organization->verified ? 'هذه المؤسسة معتمدة رسمياً لدى المنصة.' : 'بانتظار مراجعة الأوراق الثبوتية من قبل الإدارة.' }}
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .social-icon:hover {
        background: #4f46e5 !important;
        color: white !important;
        border-color: #4f46e5 !important;
        transform: translateY(-3px);
    }
</style>
@endsection
