@extends('layouts.dashboard')

@section('title', 'تقديماتي')

@section('content')
<div style="max-width: 1400px; margin: 0 auto;">
    <div style="margin-bottom: 2.5rem;">
        <h2 style="font-size: 2rem; font-weight: 800; color:#1e293b; margin: 0;">تقديماتي</h2>
        <p style="color: #64748b; margin-top: 0.5rem;">جميع الفرص التي قدمت عليها</p>
    </div>

    <!-- Filters -->
    <div class="card" style="padding: 1.5rem; margin-bottom: 2rem;">
        <form method="GET" action="{{ route('volunteer.applications') }}">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; align-items: end;">
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #475569;">الحالة</label>
                    <select name="status" class="form-input" onchange="this.form.submit()">
                        <option value="all">الكل</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد المراجعة</option>
                        <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>مقبول</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>مرفوض</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #475569;">النوع</label>
                    <select name="type" class="form-input" onchange="this.form.submit()">
                        <option value="all">الكل</option>
                        <option value="volunteering" {{ request('type') == 'volunteering' ? 'selected' : '' }}>تطوع</option>
                        <option value="training" {{ request('type') == 'training' ? 'selected' : '' }}>تدريب</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <!-- Applications Grid -->
    @if($applications->count() > 0)
    <div style="display: grid; gap: 1.5rem;">
        @foreach($applications as $application)
        <div class="card" style="padding: 2rem;">
            <div style="display: flex; justify-content: space-between; gap: 2rem;">
                <div style="flex: 1;">
                    <div style="display: flex; align-items: start; gap: 1rem; margin-bottom: 1rem;">
                        <div style="flex:1;">
                            <h3 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin: 0 0 0.5rem 0;">
                                <a href="{{ route('opportunities.show', $application->opportunity) }}" style="color: inherit; text-decoration: none;" onmouseover="this.style.color='var(--brand-blue)'" onmouseout="this.style.color='#1e293b'">
                                    {{ $application->opportunity->title }}
                                </a>
                            </h3>
                            <div style="display: flex; flex-wrap: wrap; gap: 1rem; font-size: 0.9rem; color: #64748b;">
                                <span><i class="fas fa-building" style="margin-left: 0.25rem;"></i> {{ $application->opportunity->organization->name }}</span>
                                <span><i class="fas fa-map-marker-alt" style="margin-left: 0.25rem;"></i> {{ $application->opportunity->city->name ?? 'عن بعد' }}</span>
                                <span><i class="fas fa-tag" style="margin-left: 0.25rem;"></i> {{ $application->opportunity->type == 'volunteering' ? 'تطوع' : 'تدريب' }}</span>
                            </div>
                        </div>
                        <div>
                            @if($application->status == 'pending')
                                <span style="padding: 0.5rem 1.25rem; background: #fef3c7; color: #d97706; border-radius: 99px; font-weight: 700; font-size: 0.85rem; white-space: nowrap;">
                                    <i class="fas fa-clock"></i> قيد المراجعة
                                </span>
                            @elseif($application->status == 'accepted')
                                <span style="padding: 0.5rem 1.25rem; background: #d1fae5; color: #059669; border-radius: 99px; font-weight: 700; font-size: 0.85rem; white-space: nowrap;">
                                    <i class="fas fa-check-circle"></i> مقبول
                                </span>
                            @else
                                <span style="padding: 0.5rem 1.25rem; background: #fee2e2; color: #dc2626; border-radius: 99px; font-weight: 700; font-size: 0.85rem; white-space: nowrap;">
                                    <i class="fas fa-times-circle"></i> مرفوض
                                </span>
                            @endif
                        </div>
                    </div>

                    <p style="color: #475569; line-height: 1.6; margin-bottom: 1rem;">{{ Str::limit($application->opportunity->description, 150) }}</p>

                    <div style="display: flex; flex-wrap: wrap; gap: 0.75rem; padding-top: 1rem; border-top: 1px solid #f1f5f9;">
                        <div style="font-size: 0.85rem;">
                            <span style="color: #64748b;">تاريخ التقديم:</span>
                            <span style="font-weight: 600; color: #1e293b;">{{ $application->applied_at->format('Y-m-d') }}</span>
                        </div>
                        @if($application->decision_at)
                        <div style="font-size: 0.85rem;">
                            <span style="color: #64748b;">تاريخ القرار:</span>
                            <span style="font-weight: 600; color: #1e293b;">{{ $application->decision_at->format('Y-m-d') }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 0.75rem; align-items: end;">
                    @if($application->status == 'pending')
                    <form method="POST" action="{{ route('applications.withdraw', $application) }}" onsubmit="return confirm('هل أنت متأكد من سحب التقديم؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="padding: 0.5rem 1rem; background: #fee2e2; color: #dc2626; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#fecaca'" onmouseout="this.style.background='#fee2e2'">
                            <i class="fas fa-times"></i> سحب التقديم
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div style="margin-top: 2rem;">
        {{ $applications->links() }}
    </div>
    @else
    <div class="card" style="padding: 4rem; text-align: center;">
        <i class="fas fa-inbox" style="font-size: 4rem; color: #e2e8f0; margin-bottom: 1.5rem;"></i>
        <h3 style="color: #64748b; font-weight: 700; font-size: 1.5rem;">لا توجد تقديمات</h3>
        <p style="color: #94a3b8; margin: 1rem 0 2rem 0;">لم تقم بأي تقديمات بعد</p>
        <a href="{{ route('opportunities.index') }}" class="btn-brand">تصفح الفرص</a>
    </div>
    @endif
</div>
@endsection
