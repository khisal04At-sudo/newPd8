@extends('layouts.dashboard')

@section('title', 'الملف الشخصي')

@section('content')
<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px;">
    <!-- Sidebar Info -->
    <div>
        <div class="card" style="text-align: center;">
            <img src="{{ $user->avatar_url }}" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 5px solid #f1f5f9; margin-bottom: 20px;">
            <h2 style="margin: 10px 0;">{{ $user->name }}</h2>
            <p style="color: #64748b; margin-bottom: 20px;">{{ $user->user_type == 'user' ? 'متطوع' : 'منظمة' }}</p>
            
            <div style="display: flex; justify-content: center; gap: 10px; margin-bottom: 20px;">
                <div style="background: #e0e7ff; color: #4338ca; padding: 5px 15px; border-radius: 20px; font-size: 14px; font-weight: 600;">
                    <i class="fas fa-coins"></i> {{ $user->points }} نقطة
                </div>
            </div>

            <hr style="border: 0; border-top: 1px solid #f1f5f9; margin: 20px 0;">

            <div style="text-align: right;">
                <div style="margin-bottom: 15px;">
                    <label style="color: #94a3b8; font-size: 12px; display: block;">العمر</label>
                    <span style="font-weight: 600;">{{ $user->age ?? 'غير محدد' }} سنة</span>
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="color: #94a3b8; font-size: 12px; display: block;">المدينة</label>
                    <span style="font-weight: 600;">{{ $user->city->name ?? 'غير محدد' }}</span>
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="color: #94a3b8; font-size: 12px; display: block;">الجنس</label>
                    <span style="font-weight: 600;">{{ $user->gender }}</span>
                </div>
            </div>

            <a href="#" class="btn-submit" style="display: block; text-decoration: none; text-align: center; margin-top: 20px; font-size: 14px; padding: 10px;">تعديل الملف</a>
        </div>

        <div class="card">
            <h4 style="margin-top: 0; margin-bottom: 15px;">السيرة الذاتية</h4>
            @if($user->getCV())
                <a href="{{ asset($user->getCV()->file_url) }}" target="_blank" style="display: flex; align-items: center; gap: 10px; text-decoration: none; color: #475569; background: #f8fafc; padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <i class="fas fa-file-pdf" style="color: #ef4444; font-size: 20px;"></i>
                    <span style="font-size: 14px;">تحميل السيرة الذاتية</span>
                </a>
            @else
                <p style="color: #94a3b8; font-size: 14px; text-align: center;">لم يتم رفع سيرة ذاتية</p>
            @endif
        </div>
    </div>

    <!-- Main Info -->
    <div>
        <div class="card">
            <h3 style="margin-top: 0; margin-bottom: 20px;">النبذة التعريفية</h3>
            <p style="color: #475569; line-height: 1.6;">
                {{ $user->bio ?: 'لا توجد نبذة تعريفية حالياً.' }}
            </p>
        </div>

        <div class="card">
            <h3 style="margin-top: 0; margin-bottom: 20px;">المهارات</h3>
            <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                @forelse($user->skills as $skill)
                    <div style="background: #f1f5f9; border: 1px solid #e2e8f0; padding: 8px 15px; border-radius: 8px; display: flex; align-items: center; gap: 10px;">
                        <span style="font-weight: 600;">{{ $skill->skill_name }}</span>
                        <span style="background: #{{ $skill->proficiency_color == 'blue' ? 'dbeafe' : ($skill->proficiency_color == 'green' ? 'dcfce7' : ($skill->proficiency_color == 'purple' ? 'f3e8ff' : 'f1f5f9')) }}; 
                                     color: #{{ $skill->proficiency_color == 'blue' ? '1e40af' : ($skill->proficiency_color == 'green' ? '166534' : ($skill->proficiency_color == 'purple' ? '6b21a8' : '475569')) }}; 
                                     font-size: 11px; padding: 2px 8px; border-radius: 10px;">
                            {{ $skill->proficiency_label }}
                        </span>
                    </div>
                @empty
                    <p style="color: #94a3b8;">لم يتم إضافة مهارات</p>
                @endforelse
            </div>
        </div>

        <div class="card">
            <h3 style="margin-top: 0; margin-bottom: 20px;">الشهادات المكتسبة</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                @forelse($user->certificates as $cert)
                    <div style="border: 1px solid #e2e8f0; border-radius: 10px; padding: 15px; display: flex; align-items: center; gap: 15px;">
                        <i class="fas fa-award" style="font-size: 30px; color: #f59e0b;"></i>
                        <div>
                            <div style="font-weight: 600;">{{ $cert->title }}</div>
                            <div style="font-size: 12px; color: #64748b;">{{ $cert->issue_date->format('Y/m/d') }}</div>
                            @if($cert->file)
                                <a href="{{ asset($cert->file->file_url) }}" target="_blank" style="font-size: 12px; color: #4f46e5; text-decoration: none;">تحميل PDF</a>
                            @endif
                        </div>
                    </div>
                @empty
                    <p style="color: #94a3b8; grid-column: span 2; text-align: center; padding: 20px;">لم تحصل على شهادات بعد</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
