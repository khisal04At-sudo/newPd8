@extends('layouts.dashboard')

@section('title', 'الإنجازات')

@section('content')
<div class="card">
    <h3 style="margin-top: 0; margin-bottom: 10px;">إنجازاتي وأوسمتي</h3>
    <p style="color: #64748b; margin-bottom: 30px;">اجمع الأوسمة والنقاط من خلال مشاركاتك التطوعية!</p>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px;">
        @forelse($achievements as $achievement)
            <div style="border: 1px solid #e2e8f0; border-radius: 15px; padding: 25px; text-align: center; background: white; transition: transform 0.2s; cursor: default;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                <div style="font-size: 40px; margin-bottom: 15px;">
                    @if($achievement->icon_type == 'emoji')
                        {{ $achievement->icon }}
                    @else
                        <i class="fas {{ $achievement->icon }}" style="color: #f59e0b;"></i>
                    @endif
                </div>
                <div style="font-weight: bold; color: #1e293b; margin-bottom: 5px;">{{ $achievement->name }}</div>
                <div style="font-size: 12px; color: #64748b; margin-bottom: 10px;">{{ $achievement->description }}</div>
                <div style="font-size: 11px; color: #94a3b8;">تم الحصول عليه في: {{ $achievement->pivot->earned_at->format('Y/m/d') }}</div>
                <div style="margin-top: 10px; color: #4f46e5; font-weight: 600; font-size: 13px;">+{{ $achievement->points_reward }} نقطة</div>
            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 50px; color: #94a3b8; background: #f8fafc; border-radius: 15px;">
                <i class="fas fa-medal" style="font-size: 50px; margin-bottom: 20px; display: block;"></i>
                لم تحصل على أي أوسمة بعد. ابدأ التطوع الآن!
            </div>
        @endforelse
    </div>
</div>
@endsection
