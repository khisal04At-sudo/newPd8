@extends('layouts.admin')

@section('title', 'مراجعة الفرص')
@section('header', 'قائمة انتظار مراجعة الفرص')

@section('content')
<div class="card" style="overflow: hidden;">
    <div style="padding: 1.5rem 2rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; background: #fafafa;">
        <h3 style="margin: 0; color: #1e293b; font-size: 1.1rem; font-weight: 700;">
            <i class="fas fa-clipboard-list" style="color: var(--volunteer-green); margin-left: 0.5rem;"></i>
            فرص بانتظار الاعتماد ({{ $opportunities->total() }})
        </h3>
    </div>

    @if(session('success'))
        <div style="margin: 1.5rem 2rem; padding: 1rem; background: #ecfdf5; color: #059669; border-radius: 0.75rem; display: flex; align-items: center; gap: 0.75rem; border: 1px solid #d1fae5;">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif
    @if(session('info'))
        <div style="margin: 1.5rem 2rem; padding: 1rem; background: #eff6ff; color: #1d4ed8; border-radius: 0.75rem; display: flex; align-items: center; gap: 0.75rem; border: 1px solid #dbeafe;">
            <i class="fas fa-info-circle"></i>
            {{ session('info') }}
        </div>
    @endif
    @if(session('error'))
        <div style="margin: 1.5rem 2rem; padding: 1rem; background: #fef2f2; color: #991b1b; border-radius: 0.75rem; display: flex; align-items: center; gap: 0.75rem; border: 1px solid #fee2e2;">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: right;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 1px solid #f1f5f9;">
                    <th style="padding: 1.25rem 2rem; color: #64748b; font-weight: 600; font-size: 0.85rem; text-transform: uppercase;">الفرصة والمؤسسة</th>
                    <th style="padding: 1.25rem 1rem; color: #64748b; font-weight: 600; font-size: 0.85rem; text-transform: uppercase;">التصنيف</th>
                    <th style="padding: 1.25rem 1rem; color: #64748b; font-weight: 600; font-size: 0.85rem; text-transform: uppercase;">المدينة والتنفيذ</th>
                    <th style="padding: 1.25rem 1rem; color: #64748b; font-weight: 600; font-size: 0.85rem; text-transform: uppercase;">التاريخ</th>
                    <th style="padding: 1.25rem 2rem; color: #64748b; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; text-align: left;">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($opportunities as $opp)
                    <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#fcfcfc'" onmouseout="this.style.background='none'">
                        <td style="padding: 1.5rem 2rem;">
                            <div style="font-weight: 700; color: #1e293b; margin-bottom: 0.35rem; font-size: 1rem;">{{ $opp->title }}</div>
                            <div style="font-size: 0.85rem; color: #64748b; display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-building" style="font-size: 0.75rem;"></i> {{ $opp->organization->name }}
                            </div>
                        </td>
                        <td style="padding: 1.5rem 1rem;">
                            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                <div style="display: flex; flex-wrap: wrap; gap: 0.4rem;">
                                    <span style="font-size: 0.7rem; padding: 0.25rem 0.6rem; border-radius: 6px; background: #f1f5f9; color: #475569; font-weight: 700;">
                                        {{ $opp->type == 'volunteering' ? 'تطوع' : 'تدريب' }}
                                    </span>
                                    <span style="font-size: 0.7rem; padding: 0.25rem 0.6rem; border-radius: 6px; background: #e0f2fe; color: #0369a1; font-weight: 700;">
                                        {{ $opp->category }}
                                    </span>
                                </div>
                                @if($opp->certificateFile)
                                    <span style="font-size: 0.7rem; color: #10b981; font-weight: 700; display: flex; align-items: center; gap: 0.25rem;">
                                        <i class="fas fa-certificate"></i> مرفق ملف شهادة
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td style="padding: 1.5rem 1rem;">
                            <div style="font-weight: 600; color: #475569; margin-bottom: 0.25rem;">{{ $opp->city->name ?? '--' }}</div>
                            <div style="font-size: 0.75rem; color: #94a3b8;">
                                <i class="fas fa-map-marker-alt"></i> {{ $opp->execution_method == 'remote' ? 'عن بعد' : 'حضوري' }}
                            </div>
                        </td>
                        <td style="padding: 1.5rem 1rem; font-size: 0.85rem; color: #64748b;">
                            <div style="font-weight: 600;">{{ $opp->created_at->format('Y-m-d') }}</div>
                            <div style="font-size: 0.7rem;">{{ $opp->created_at->diffForHumans() }}</div>
                        </td>
                        <td style="padding: 1.5rem 2rem; text-align: left;">
                            <a href="{{ route('admin.opportunities.show', $opp) }}" class="btn" 
                               style="background: white; color: var(--brand-blue); border: 1px solid #e2e8f0; padding: 0.5rem 1.25rem; border-radius: 0.5rem; text-decoration: none; font-size: 0.85rem; font-weight: 700; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.2s;">
                                <i class="fas fa-eye"></i> مراجعة والبت
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 4rem 2rem; text-align: center; color: #64748b;">
                            <div style="width: 80px; height: 80px; background: #f8fafc; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                                <i class="fas fa-check-double" style="font-size: 2rem; color: #cbd5e1;"></i>
                            </div>
                            <div style="font-weight: 700; font-size: 1.1rem; color: #1e293b; margin-bottom: 0.5rem;">عمل رائع!</div>
                            لا توجد فرص بانتظار المراجعة حالياً.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($opportunities->hasPages())
        <div style="padding: 1.5rem 2rem; border-top: 1px solid #f1f5f9; background: #fafafa;">
            {{ $opportunities->links() }}
        </div>
    @endif
</div>
@endsection
