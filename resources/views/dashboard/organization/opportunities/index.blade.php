@extends('layouts.dashboard')

@section('title', 'إدارة الفرص')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h2 style="margin: 0; color: #1e293b;">الفرص الحالية</h2>
    <a href="{{ route('organization.opportunities.create') }}" 
       style="background: #4f46e5; color: white; padding: 0.75rem 1.5rem; border-radius: 0.75rem; text-decoration: none; font-weight: 700; display: flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.3);">
        <i class="fas fa-plus"></i> إضافة فرصة جديدة
    </a>
</div>

<div class="card" style="padding: 0; overflow: hidden;">
    <table style="width: 100%; border-collapse: collapse; text-align: right;">
        <thead>
            <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                <th style="padding: 1.25rem 1.5rem; color: #64748b; font-weight: 600; font-size: 0.85rem;">العنوان</th>
                <th style="padding: 1.25rem 1.5rem; color: #64748b; font-weight: 600; font-size: 0.85rem;">النوع</th>
                <th style="padding: 1.25rem 1.5rem; color: #64748b; font-weight: 600; font-size: 0.85rem;">المقاعد</th>
                <th style="padding: 1.25rem 1.5rem; color: #64748b; font-weight: 600; font-size: 0.85rem;">المتقدمين</th>
                <th style="padding: 1.25rem 1.5rem; color: #64748b; font-weight: 600; font-size: 0.85rem;">الحالة</th>
                <th style="padding: 1.25rem 1.5rem; color: #64748b; font-weight: 600; font-size: 0.85rem;">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($opportunities as $opp)
                <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 1.25rem 1.5rem;">
                        <div style="font-weight: 700; color: #1e293b;">{{ $opp->title }}</div>
                        <div style="font-size: 0.75rem; color: #64748b;">{{ $opp->start_date->format('Y-m-d') }}</div>
                    </td>
                    <td style="padding: 1.25rem 1.5rem;">
                        <span style="background: {{ $opp->type == 'volunteering' ? '#dbeafe' : '#fef9c3' }}; color: {{ $opp->type == 'volunteering' ? '#1e40af' : '#854d0e' }}; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.8rem; font-weight: 600;">
                            {{ $opp->type == 'volunteering' ? 'تطوع' : 'تدريب' }}
                        </span>
                    </td>
                    <td style="padding: 1.25rem 1.5rem; color: #475569;">{{ $opp->seats }}</td>
                    <td style="padding: 1.25rem 1.5rem; color: #475569;">{{ $opp->applications_count }}</td>
                    <td style="padding: 1rem; text-align: center;">
                        @php
                            $statusLabels = [
                                0 => ['text' => 'بانتظار المراجعة', 'bg' => '#fef3c7', 'color' => '#d97706'],
                                1 => ['text' => 'منشورة', 'bg' => '#dcfce7', 'color' => '#16a34a'],
                                2 => ['text' => 'تعديلات مطلوبة', 'bg' => '#e0f2fe', 'color' => '#0369a1'],
                                3 => ['text' => 'مرفوضة', 'bg' => '#fee2e2', 'color' => '#dc2626'],
                                8 => ['text' => 'ملغاة', 'bg' => '#f1f5f9', 'color' => '#64748b'],
                                9 => ['text' => 'مغلقة', 'bg' => '#f1f5f9', 'color' => '#64748b'],
                            ];
                            $s = $statusLabels[(int)$opp->status] ?? ['text' => 'غير معروف', 'bg' => '#eee', 'color' => '#666'];
                        @endphp
                        <div style="display: flex; align-items: center; gap: 0.5rem; color: {{ $s['color'] }}; font-weight: 600; font-size: 0.85rem;">
                            <i class="fas fa-circle" style="font-size: 0.5rem;"></i>
                            {{ $s['text'] }}
                        </div>
                    </td>
                    <td style="padding: 1.25rem 1.5rem;">
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('organization.opportunities.edit', $opp) }}" style="color: #64748b; background: #f1f5f9; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 0.5rem; text-decoration: none;">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('organization.opportunities.destroy', $opp) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="color: #ef4444; background: #fee2e2; border: none; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 0.5rem; cursor: pointer;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="padding: 4rem; text-align: center; color: #94a3b8;">
                        <i class="fas fa-folder-open" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                        لا توجد فرص منشورة حالياً
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 1.5rem;">
    {{ $opportunities->links() }}
</div>
@endsection
