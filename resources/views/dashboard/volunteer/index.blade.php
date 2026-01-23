@extends('layouts.dashboard')

@section('title', 'لوحة التحكم')

@section('content')
<div style="max-width: 1400px; margin: 0 auto;">
    <div style="margin-bottom: 2.5rem;">
        <h2 style="font-size: 2rem; font-weight: 800; color: #1e293b; margin: 0;">مرحباً، {{ Auth::user()->name }}</h2>
        <p style="color: #64748b; margin-top: 0.5rem;">نظرة عامة على نشاطك التطوعي</p>
    </div>

    <!-- Statistics Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
        <!-- Total Applications -->
        <div class="card" style="padding: 1.5rem; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border: none;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <div style="font-size: 0.85rem; opacity: 0.9; margin-bottom: 0.5rem;">إجمالي التقديمات</div>
                    <div style="font-size: 2.5rem; font-weight: 800;">{{ $stats['total_applications'] }}</div>
                </div>
                <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-file-alt" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>

        <!-- Pending -->
        <div class="card" style="padding: 1.5rem; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; border: none;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <div style="font-size: 0.85rem; opacity: 0.9; margin-bottom: 0.5rem;">قيد المراجعة</div>
                    <div style="font-size: 2.5rem; font-weight: 800;">{{ $stats['pending'] }}</div>
                </div>
                <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-clock" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>

        <!-- Accepted -->
        <div class="card" style="padding: 1.5rem; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; border: none;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <div style="font-size: 0.85rem; opacity: 0.9; margin-bottom: 0.5rem;">تم القبول</div>
                    <div style="font-size: 2.5rem; font-weight: 800;">{{ $stats['accepted'] }}</div>
                </div>
                <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-check-circle" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>

        <!-- Saved -->
        <div class="card" style="padding: 1.5rem; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; border: none;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <div style="font-size: 0.85rem; opacity: 0.9; margin-bottom: 0.5rem;">الفرص المحفوظة</div>
                    <div style="font-size: 2.5rem; font-weight: 800;">{{ $stats['saved_count'] }}</div>
                </div>
                <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-bookmark" style="font-size: 1.5rem;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 3rem;">
        <a href="{{ route('volunteer.applications') }}" class="card" style="padding: 1.5rem; text-decoration: none; color: inherit; transition: all 0.2s; border: 2px solid #e2e8f0;" onmouseover="this.style.borderColor='var(--brand-blue)'" onmouseout="this.style.borderColor='#e2e8f0'">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 56px; height: 56px; background: #eff6ff; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-list-alt" style="font-size: 1.5rem; color: var(--brand-blue);"></i>
                </div>
                <div>
                    <div style="font-weight: 700; color: #1e293b; font-size: 1.1rem;">التقديمات</div>
                    <div style="color: #64748b; font-size: 0.9rem;">عرض جميع تقديماتك</div>
                </div>
            </div>
        </a>

        <a href="{{ route('volunteer.saved') }}" class="card" style="padding: 1.5rem; text-decoration: none; color: inherit; transition: all 0.2s; border: 2px solid #e2e8f0;" onmouseover="this.style.borderColor='var(--volunteer-green)'" onmouseout="this.style.borderColor='#e2e8f0'">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 56px; height: 56px; background: #f0fdf4; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-bookmark" style="font-size: 1.5rem; color: var(--volunteer-green);"></i>
                </div>
                <div>
                    <div style="font-weight: 700; color: #1e293b; font-size: 1.1rem;">الفرص المحفوظة</div>
                    <div style="color: #64748b; font-size: 0.9rem;">تصفح فرصك المفضلة</div>
                </div>
            </div>
        </a>
    </div>

    <!-- Recent Applications -->
    @if($recentApplications->count() > 0)
    <div class="card" style="padding: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.25rem; font-weight: 800; color: #1e293b; margin: 0;">آخر التقديمات</h3>
            <a href="{{ route('volunteer.applications') }}" style="color: var(--brand-blue); text-decoration: none; font-weight: 600;">عرض الكل <i class="fas fa-arrow-left" style="font-size: 0.8rem; margin-right: 0.25rem;"></i></a>
        </div>

        <div style="display: flex; flex-direction: column; gap: 1rem;">
            @foreach($recentApplications as $application)
            <div style="padding: 1.25rem; background: #f8fafc; border-radius: 1rem; display: flex; justify-content: space-between; align-items: center;">
                <div style="flex: 1;">
                    <div style="font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">{{ $application->opportunity->title }}</div>
                    <div style="display: flex; gap: 1rem; font-size: 0.85rem; color: #64748b;">
                        <span><i class="fas fa-building" style="margin-left: 0.25rem;"></i> {{ $application->opportunity->organization->name }}</span>
                        <span><i class="fas fa-calendar" style="margin-left: 0.25rem;"></i> {{ $application->applied_at->diffForHumans() }}</span>
                    </div>
                </div>
                <div>
                    @if($application->status == 'pending')
                        <span style="padding: 0.5rem 1rem; background: #fef3c7; color: #d97706; border-radius: 99px; font-weight: 700; font-size: 0.85rem;">قيد المراجعة</span>
                    @elseif($application->status == 'accepted')
                        <span style="padding: 0.5rem 1rem; background: #d1fae5; color: #059669; border-radius: 99px; font-weight: 700; font-size: 0.85rem;">مقبول ✓</span>
                    @else
                        <span style="padding: 0.5rem 1rem; background: #fee2e2; color: #dc2626; border-radius: 99px; font-weight: 700; font-size: 0.85rem;">مرفوض</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="card" style="padding: 3rem; text-align: center;">
        <i class="fas fa-inbox" style="font-size: 3rem; color: #e2e8f0; margin-bottom: 1rem;"></i>
        <h3 style="color: #64748b; font-weight: 700;">لم تقم بأي تقديمات بعد</h3>
        <p style="color: #94a3b8; margin: 0.5rem 0 1.5rem 0;">ابدأ بتصفح الفرص المتاحة والتقديم عليها</p>
        <a href="{{ route('opportunities.index') }}" class="btn-brand">تصفح الفرص</a>
    </div>
    @endif

    <!-- Recent Saved Opportunities -->
    @if(isset($recentSaved) && $recentSaved->count() > 0)
    <div class="card" style="padding: 2rem; margin-top: 3rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.25rem; font-weight: 800; color: #1e293b; margin: 0;">الفرص المحفوظة مؤخراً</h3>
            <a href="{{ route('volunteer.saved') }}" style="color: var(--volunteer-green); text-decoration: none; font-weight: 600;">عرض الكل <i class="fas fa-arrow-left" style="font-size: 0.8rem; margin-right: 0.25rem;"></i></a>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.25rem;">
            @foreach($recentSaved as $saved)
            @php $opp = $saved->opportunity; @endphp
            <div style="background: #f8fafc; padding: 1.25rem; border-radius: 1rem; border: 1px solid #f1f5f9; transition: all 0.2s;" onmouseover="this.style.borderColor='var(--volunteer-green)'; this.style.background='white'" onmouseout="this.style.borderColor='#f1f5f9'; this.style.background='#f8fafc'">
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;">
                    <img src="{{ url($opp->organization->logo_url ?? 'assets/default-logo.png') }}" style="width: 36px; height: 36px; border-radius: 8px; object-fit: cover;">
                    <div style="font-size: 0.8rem; font-weight: 600; color: #64748b; flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $opp->organization->name }}</div>
                </div>
                <h4 style="font-size: 1rem; font-weight: 700; color: #1e293b; margin: 0 0 0.5rem 0; line-height: 1.3; height: 2.6rem; overflow: hidden;">
                    <a href="{{ route('opportunities.show', $opp) }}" style="color: inherit; text-decoration: none;">{{ Str::limit($opp->title, 50) }}</a>
                </h4>
                <div style="display: flex; gap: 0.75rem; font-size: 0.75rem; color: #64748b; margin-bottom: 1rem;">
                    <span><i class="fas fa-clock"></i> {{ $opp->total_hours }}ساعة</span>
                    <span><i class="fas fa-users"></i> {{ $opp->seats }}</span>
                </div>
                <a href="{{ route('applications.create', $opp) }}" style="display: block; width: 100%; padding: 0.6rem; background: var(--volunteer-green); color: white; text-align: center; border-radius: 0.75rem; text-decoration: none; font-weight: 700; font-size: 0.85rem; transition: all 0.2s;" onmouseover="this.style.background='#059669'" onmouseout="this.style.background='var(--volunteer-green)'">
                    <i class="fas fa-paper-plane"></i> تقديم الآن
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
