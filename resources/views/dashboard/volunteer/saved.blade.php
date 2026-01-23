@extends('layouts.dashboard')

@section('title', 'الفرص المحفوظة')

@section('content')
<div style="max-width: 1400px; margin: 0 auto;">
    <div style="margin-bottom: 2.5rem;">
        <h2 style="font-size: 2rem; font-weight: 800; color: #1e293b; margin: 0;">الفرص المحفوظة</h2>
        <p style="color: #64748b; margin-top: 0.5rem;">الفرص التي قمت بحفظها للعودة إليها لاحقاً</p>
    </div>

    @if($savedOpportunities->count() > 0)
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 2rem;">
        @foreach($savedOpportunities as $saved)
        @php $opportunity = $saved->opportunity; @endphp
        <div class="card" style="padding: 1.75rem; position: relative; transition: all 0.2s;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 24px rgba(0,0,0,0.1)'" onmouseout="this.style.transform='none'; this.style.boxShadow=''">
            <!-- Type Badge -->
            <div style="position: absolute; top: 1rem; left: 1rem;">
                <span style="padding: 0.35rem 0.75rem; background: {{ $opportunity->type == 'volunteering' ? '#d1fae5' : '#dbeafe' }}; color: {{ $opportunity->type == 'volunteering' ? '#059669' : '#2563eb' }}; border-radius: 99px; font-size: 0.75rem; font-weight: 700;">
                    {{ $opportunity->type == 'volunteering' ? 'تطوع' : 'تدريب' }}
                </span>
            </div>

            <!-- Saved Date -->
            <div style="text-align: left; font-size: 0.75rem; color: #94a3b8; margin-bottom: 1rem;">
                <i class="fas fa-bookmark"></i> {{ $saved->created_at->diffForHumans() }}
            </div>

            <!-- Organization -->
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                <div style="width: 40px; height: 40px; background: #f1f5f9; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-building" style="color: var(--volunteer-green);"></i>
                </div>
                <div style="font-size: 0.85rem; font-weight: 600; color: #64748b;">{{ $opportunity->organization->name }}</div>
            </div>

            <!-- Title -->
            <h3 style="font-size: 1.15rem; font-weight: 700; color: #1e293b; margin: 0 0 0.75rem 0; line-height: 1.4;">
                <a href="{{ route('opportunities.show', $opportunity) }}" style="color: inherit; text-decoration: none;" onmouseover="this.style.color='var(--brand-blue)'" onmouseout="this.style.color='#1e293b'">
                    {{ $opportunity->title }}
                </a>
            </h3>

            <!-- Description -->
            <p style="color: #64748b; font-size: 0.9rem; line-height: 1.6; margin-bottom: 1.25rem; height: 3rem; overflow: hidden;">
                {{ Str::limit($opportunity->description, 100) }}
            </p>

            <!-- Info Tags -->
            <div style="display: flex; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 1.5rem;">
                <div style="display: flex; align-items: center; gap: 0.35rem; font-size: 0.8rem; color: #64748b;">
                    <i class="fas fa-map-marker-alt" style="color: var(--brand-blue);"></i>
                    {{ $opportunity->city->name ?? 'عن بعد' }}
                </div>
                <div style="display: flex; align-items: center; gap: 0.35rem; font-size: 0.8rem; color: #64748b;">
                    <i class="fas fa-clock" style="color: var(--brand-blue);"></i>
                    {{ $opportunity->total_hours }} ساعة
                </div>
                <div style="display: flex; align-items: center; gap: 0.35rem; font-size: 0.8rem; color: #64748b;">
                    <i class="fas fa-users" style="color: var(--brand-blue);"></i>
                    {{ $opportunity->seats }} مقعد
                </div>
            </div>

            <!-- Actions -->
            <div style="display: flex; gap: 0.75rem; border-top: 1px solid #f1f5f9; padding-top: 1.25rem;">
                <a href="{{ route('applications.create', $opportunity) }}" class="btn-volunteer" style="flex: 1; text-align: center; font-size: 0.9rem;">
                    <i class="fas fa-paper-plane"></i> تقديم الآن
                </a>
                <form method="POST" action="{{ route('volunteer.opportunities.unsave', $opportunity) }}" style="flex: 0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="width: 40px; height: 40px; background: #fee2e2; color: #dc2626; border: none; border-radius: 0.5rem; cursor: pointer; transition: all 0.2s;" title="إزالة من المحفوظات" onmouseover="this.style.background='#fecaca'" onmouseout="this.style.background='#fee2e2'">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div style="margin-top: 3rem;">
        {{ $savedOpportunities->links() }}
    </div>
    @else
    <div class="card" style="padding: 4rem; text-align: center;">
        <i class="fas fa-bookmark" style="font-size: 4rem; color: #e2e8f0; margin-bottom: 1.5rem;"></i>
        <h3 style="color: #64748b; font-weight: 700; font-size: 1.5rem;">لا توجد فرص محفوظة</h3>
        <p style="color: #94a3b8; margin: 1rem 0 2rem 0;">قم بحفظ الفرص التي تهمك لتجدها بسهولة لاحقاً</p>
        <a href="{{ route('opportunities.index') }}" class="btn-brand">استكشف الفرص</a>
    </div>
    @endif
</div>
@endsection
