@extends('layouts.dashboard')

@section('title', 'الملف الشخصي')

@section('content')
<div style="animation: fadeIn 0.8s ease-out;">
    <!-- Profile Header / Cover Section -->
    <div class="glass-card" style="margin-bottom: 2rem; padding: 0; overflow: hidden; border-radius: 2rem; position: relative;">
        <div style="height: 160px; background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); position: relative;">
            <div style="position: absolute; bottom: -50px; right: 40px; display: flex; align-items: flex-end; gap: 1.5rem;">
                <div style="position: relative; cursor: pointer;" onclick="openPhotoModal('{{ $user->avatar_url }}', '{{ $user->name }}')">
                    <img src="{{ $user->avatar_url }}" style="width: 140px; height: 140px; border-radius: 2rem; object-fit: cover; border: 6px solid white; box-shadow: 0 10px 25px rgba(0,0,0,0.1); background: white; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                    @if($user->is_active)
                    <div style="position: absolute; bottom: 10px; left: 10px; width: 20px; height: 20px; background: #10b981; border: 4px solid white; border-radius: 50%;" title="نشط الآن"></div>
                    @endif
                </div>
                <div style="padding-bottom: 10px;">
                    <h1 style="font-size: 2rem; font-weight: 800; color: white; margin-bottom: 0.25rem; text-shadow: 0 2px 4px rgba(0,0,0,0.1);">{{ $user->name }}</h1>
                    <div style="display: flex; align-items: center; gap: 1rem; color: rgba(255,255,255,0.9); font-size: 0.95rem;">
                        <span><i class="fas fa-map-marker-alt ml-1"></i> {{ $user->city->name ?? 'غير محدد' }}</span>
                        <span>•</span>
                        <span><i class="fas fa-user-tag ml-1"></i> متطوع</span>
                    </div>
                </div>
            </div>
        </div>
        <div style="padding: 70px 40px 30px; display: flex; justify-content: flex-end; gap: 1rem; align-items: center;">
            <div style="display: flex; gap: 2rem; margin-left: auto; margin-right: 180px;">
                <div style="text-align: center;">
                    <div style="font-size: 1.25rem; font-weight: 800; color: #1e293b;">{{ (int)$user->volunteer_hours }}</div>
                    <div style="font-size: 0.8rem; color: #64748b; font-weight: 600;">ساعة تطوع</div>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 1.25rem; font-weight: 800; color: #1e293b;">{{ $user->points }}</div>
                    <div style="font-size: 0.8rem; color: #64748b; font-weight: 600;">نقطة</div>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 1.25rem; font-weight: 800; color: #1e293b;">{{ $user->certificates->count() }}</div>
                    <div style="font-size: 0.8rem; color: #64748b; font-weight: 600;">شهادة</div>
                </div>
            </div>
            <a href="{{ route('dashboard.profile.edit') }}" class="btn-brand" style="padding: 0.75rem 1.5rem; border-radius: 1rem; font-size: 0.95rem;">
                <i class="fas fa-edit ml-2"></i> تعديل الملف
            </a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
        <!-- Left Column -->
        <div style="display: flex; flex-direction: column; gap: 2rem;">
            <!-- Personal Info -->
            <div class="glass-card" style="padding: 1.5rem; border-radius: 1.5rem;">
                <h3 style="font-size: 1.1rem; font-weight: 700; color: #1e293b; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-info-circle text-brand-500"></i> معلومات شخصية
                </h3>
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div style="display: flex; justify-content: space-between; font-size: 0.95rem;">
                        <span style="color: #64748b;">العمر:</span>
                        <span style="font-weight: 600; color: #1e293b;">{{ $user->age ?? 'غير محدد' }} سنة</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 0.95rem;">
                        <span style="color: #64748b;">الجنس:</span>
                        <span style="font-weight: 600; color: #1e293b;">{{ $user->gender ?? 'غير محدد' }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 0.95rem;">
                        <span style="color: #64748b;">رقم الهاتف:</span>
                        <span style="font-weight: 600; color: #1e293b; direction: ltr;">{{ $user->phone ?? 'غير محدد' }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 0.95rem;">
                        <span style="color: #64748b;">تاريخ الانضمام:</span>
                        <span style="font-weight: 600; color: #1e293b;">{{ $user->created_at->format('Y/m/d') }}</span>
                    </div>
                </div>
            </div>

            <!-- CV Section -->
            <div class="glass-card" style="padding: 1.5rem; border-radius: 1.5rem;">
                <h3 style="font-size: 1.1rem; font-weight: 700; color: #1e293b; margin-bottom: 1.25rem;">السيرة الذاتية</h3>
                @if($user->getCV())
                    <a href="{{ asset($user->getCV()->file_url) }}" target="_blank" style="display: flex; align-items: center; gap: 0.75rem; text-decoration: none; background: #f8fafc; padding: 1rem; border-radius: 1rem; border: 1px solid #e2e8f0; transition: all 0.3s;" class="hover-translate">
                        <div style="width: 40px; height: 40px; background: #fee2e2; border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; color: #ef4444;">
                            <i class="fas fa-file-pdf" style="font-size: 1.25rem;"></i>
                        </div>
                        <div style="flex: 1;">
                            <div style="font-weight: 700; color: #1e293b; font-size: 0.9rem;">عرض السيرة الذاتية</div>
                            <div style="font-size: 0.75rem; color: #64748b;">PDF • {{ $user->getCV()->file_size_human }}</div>
                        </div>
                        <i class="fas fa-external-link-alt" style="color: #94a3b8; font-size: 0.8rem;"></i>
                    </a>
                @else
                    <div style="text-align: center; padding: 1.5rem; background: #f8fafc; border-radius: 1rem; border: 2px dashed #e2e8f0;">
                        <i class="fas fa-file-upload" style="font-size: 2rem; color: #cbd5e1; margin-bottom: 0.75rem;"></i>
                        <p style="font-size: 0.85rem; color: #94a3b8; margin: 0;">لم يتم رفع سيرة ذاتية بعد</p>
                    </div>
                @endif
            </div>

            <!-- Interests Section -->
            <div class="glass-card" style="padding: 1.5rem; border-radius: 1.5rem;">
                <h3 style="font-size: 1.1rem; font-weight: 700; color: #1e293b; margin-bottom: 1.25rem;">🎯 الاهتمامات</h3>
                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                    @php
                        $allCategories = \App\Models\UserInterest::$categories;
                    @endphp
                    @forelse($user->interests as $interest)
                        @php $info = $allCategories[$interest->category] ?? ['icon' => 'fas fa-star', 'color' => '#94a3b8']; @endphp
                        <div style="display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.4rem 0.9rem; border-radius: 2rem; background: color-mix(in srgb, {{ $info['color'] }} 12%, white); border: 1.5px solid color-mix(in srgb, {{ $info['color'] }} 40%, white); color: {{ $info['color'] }}; font-size: 0.85rem; font-weight: 700;">
                            @if(str_contains($info['icon'], '/'))
                                <img src="{{ asset($info['icon']) }}" style="width: 1.25rem; height: 1.25rem; object-fit: contain;">
                            @else
                                <i class="{{ $info['icon'] }}"></i>
                            @endif
                            <span>{{ $interest->category }}</span>
                        </div>
                    @empty
                        <p style="font-size: 0.85rem; color: #94a3b8; text-align: center; width: 100%;">لم يتم تحديد الاهتمامات بعد</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div style="display: flex; flex-direction: column; gap: 2rem;">
            <!-- About Section -->
            <div class="glass-card" style="padding: 2rem; border-radius: 1.5rem;">
                <h3 style="font-size: 1.25rem; font-weight: 800; color: #1e293b; margin-bottom: 1.25rem;">النبذة التعريفية</h3>
                <p style="color: #475569; line-height: 1.8; font-size: 1rem; margin: 0; white-space: pre-wrap;">{{ $user->bio ?: 'لا توجد نبذة تعريفية حالياً. أخبر الآخرين عن اهتماماتك وخبراتك!' }}</p>
            </div>

            <!-- Certificates Grid -->
            <div class="glass-card" style="padding: 2rem; border-radius: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: #1e293b; margin: 0;">الشهادات المكتسبة</h3>
                    <span style="background: #f0fdf4; color: #16a34a; padding: 0.25rem 0.75rem; border-radius: 2rem; font-size: 0.8rem; font-weight: 700;">{{ $user->certificates->count() }} إنجاز</span>
                </div>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.25rem;">
                    @forelse($user->certificates as $cert)
                        <div class="certification-card" style="background: white; border: 1px solid #f1f5f9; border-radius: 1.25rem; padding: 1.25rem; transition: all 0.3s; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); display: flex; align-items: center; gap: 1.25rem;">
                            <div style="width: 54px; height: 54px; background: #fffcf0; border-radius: 1rem; display: flex; align-items: center; justify-content: center; color: #f59e0b; border: 1px solid #fef3c7;">
                                <i class="fas fa-medal" style="font-size: 1.5rem;"></i>
                            </div>
                            <div style="flex: 1;">
                                <div style="font-weight: 800; color: #1e293b; font-size: 0.95rem; margin-bottom: 0.25rem;">{{ $cert->title }}</div>
                                <div style="font-size: 0.8rem; color: #94a3b8;">{{ $cert->issue_date->translatedFormat('d F Y') }}</div>
                                @if($cert->file || $cert->file_url)
                                <a href="{{ route('certificates.download', $cert->id) }}" style="margin-top: 0.5rem; display: inline-flex; align-items: center; font-size: 0.8rem; color: #4f46e5; font-weight: 700; text-decoration: none;">
                                    تحميل الشهادة <i class="fas fa-download mr-1" style="font-size: 0.7rem;"></i>
                                </a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div style="grid-column: 1 / -1; text-align: center; padding: 3rem 1rem; background: #f8fafc; border-radius: 1.25rem; border: 2px dashed #e2e8f0;">
                            <div style="font-size: 3rem; color: #e2e8f0; margin-bottom: 1rem;">
                                <i class="fas fa-award"></i>
                            </div>
                            <h4 style="color: #1e293b; font-weight: 700; margin-bottom: 0.5rem;">لم تحصل على شهادات بعد</h4>
                            <p style="color: #64748b; font-size: 0.9rem;">بادر بالتطوع في الفرص المتاحة للحصول على شهادات معتمدة</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .hover-translate:hover {
        transform: translateY(-3px);
        background: white !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
    }
    
    .certification-card:hover {
        border-color: #e0e7ff;
        box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.1);
    }
</style>
@endsection
