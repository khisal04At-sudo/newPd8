@extends('layouts.admin')

@section('title', 'مراجعة الفرص')
@section('header', 'قائمة انتظار مراجعة الفرص')

@section('content')
<div style="background: white; border-radius: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
    <div style="padding: 1.5rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
        <h3 style="margin: 0; color: #1e293b;">فرص بانتظار الاعتماد ({{ $opportunities->total() }})</h3>
    </div>

    @if(session('success'))
        <div style="margin: 1.5rem; padding: 1rem; background: #dcfce7; color: #16a34a; border-radius: 0.5rem;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('info'))
        <div style="margin: 1.5rem; padding: 1rem; background: #e0f2fe; color: #0369a1; border-radius: 0.5rem;">
            {{ session('info') }}
        </div>
    @endif
    @if(session('error'))
        <div style="margin: 1.5rem; padding: 1rem; background: #fee2e2; color: #dc2626; border-radius: 0.5rem;">
            {{ session('error') }}
        </div>
    @endif

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: right;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 1px solid #f1f5f9;">
                    <th style="padding: 1rem;">الفرصة والمؤسسة</th>
                    <th style="padding: 1rem;">النوع والتصنيف</th>
                    <th style="padding: 1rem;">المدينة</th>
                    <th style="padding: 1rem;">سياية الحضور</th>
                    <th style="padding: 1rem;">التاريخ</th>
                    <th style="padding: 1rem;">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($opportunities as $opp)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 1rem;">
                            <div style="font-weight: 700; color: #1e293b; margin-bottom: 0.25rem;">{{ $opp->title }}</div>
                            <div style="font-size: 0.85rem; color: #64748b; margin-bottom: 0.25rem;">
                                <i class="fas fa-building" style="margin-left: 0.25rem;"></i> {{ $opp->organization->name }}
                            </div>
                            <div style="font-size: 0.75rem; color: #94a3b8; max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $opp->description }}
                            </div>
                        </td>
                        <td style="padding: 1rem;">
                            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                <div style="display: flex; gap: 0.5rem;">
                                    <span style="font-size: 0.75rem; padding: 0.2rem 0.6rem; border-radius: 99px; background: #f1f5f9; color: #475569;">
                                        {{ $opp->type == 'volunteering' ? 'تطوع' : 'تدريب' }}
                                    </span>
                                    <span style="font-size: 0.75rem; padding: 0.2rem 0.6rem; border-radius: 99px; background: #e0f2fe; color: #0369a1;">
                                        {{ $opp->category }}
                                    </span>
                                </div>
                                @if($opp->certificateFile)
                                    <span style="font-size: 0.7rem; color: #16a34a; font-weight: 700;">
                                        <i class="fas fa-certificate"></i> مرفق ملف شهادة
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td style="padding: 1rem;">{{ $opp->city->name ?? '--' }}</td>
                        <td style="padding: 1rem;">
                            <span style="font-size: 0.85rem; color: #475569;">
                                {{ $opp->attendance_required ? 'حضور إلزامي' : 'مرن/عن بعد' }}
                            </span>
                        </td>
                        <td style="padding: 1rem; font-size: 0.85rem; color: #64748b;">{{ $opp->created_at->format('Y-m-d') }}</td>
                        <td style="padding: 1rem;">
                            <a href="{{ route('admin.opportunities.show', $opp) }}" class="btn" 
                               style="background: #4f46e5; color: white; padding: 0.5rem 1rem; border-radius: 0.5rem; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-search-plus"></i> مراجعة التفاصيل
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 4rem; text-align: center; color: #64748b;">
                            <i class="fas fa-clipboard-check" style="font-size: 2.5rem; display: block; margin-bottom: 1rem; color: #cbd5e1;"></i>
                            لا توجد فرص بانتظار المراجعة حالياً.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 1.5rem;">
        {{ $opportunities->links() }}
    </div>
</div>
@endsection
