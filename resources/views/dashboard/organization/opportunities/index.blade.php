@extends('layouts.dashboard')

@section('title', 'إدارة الفرص')

@section('content')
<div style="font-family: 'Cairo', sans-serif;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem;">
        <div>
            <h2 style="margin: 0; color: #1e293b; font-weight: 850; font-size: 1.75rem;">إدارة الفرص</h2>
            <p style="color: #64748b; margin-top: 0.25rem; font-size: 0.95rem;">تتبع ودرة حياة فرصك التطوعية والتدريبية</p>
        </div>
        <a href="{{ route('organization.opportunities.create') }}" 
           style="background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; padding: 0.9rem 1.75rem; border-radius: 1rem; text-decoration: none; font-weight: 800; display: flex; align-items: center; gap: 0.75rem; box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.2); transition: all 0.3s;"
           onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 12px 20px -3px rgba(37, 99, 235, 0.3)'"
           onmouseout="this.style.transform='none'; this.style.boxShadow='0 10px 15px -3px rgba(37, 99, 235, 0.2)'">
            <i class="fas fa-plus"></i> إضافة فرصة جديدة
        </a>
    </div>

    <div class="card" style="padding: 0; overflow: hidden; border-radius: 1.5rem; border: 1px solid #f1f5f9; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
        <table style="width: 100%; border-collapse: collapse; text-align: right;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #f1f5f9;">
                    <th style="padding: 1.5rem; color: #475569; font-weight: 800; font-size: 0.9rem;">الفرصة</th>
                    <th style="padding: 1.5rem; color: #475569; font-weight: 800; font-size: 0.9rem;">النوع والتصنيف</th>
                    <th style="padding: 1.5rem; color: #475569; font-weight: 800; font-size: 0.9rem; text-align: center;">المقاعد</th>
                    <th style="padding: 1.5rem; color: #475569; font-weight: 800; font-size: 0.9rem; text-align: center;">المتقدمين</th>
                    <th style="padding: 1.5rem; color: #475569; font-weight: 800; font-size: 0.9rem;">الحالة</th>
                    <th style="padding: 1.5rem; color: #475569; font-weight: 800; font-size: 0.9rem;">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($opportunities as $opp)
                    <tr style="border-bottom: 1px solid #f1f5f9; transition: all 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <td style="padding: 1.5rem;">
                            <div style="font-weight: 800; color: #1e293b; font-size: 1.05rem; margin-bottom: 0.25rem;">{{ $opp->title }}</div>
                            <div style="font-size: 0.85rem; color: #94a3b8; display: flex; align-items: center; gap: 0.5rem;">
                                <i class="far fa-calendar-alt"></i>
                                تاريخ التشغيل: {{ $opp->start_date->format('Y/m/d') }}
                            </div>
                        </td>
                        <td style="padding: 1.5rem;">
                            <div style="display: flex; flex-direction: column; gap: 0.4rem;">
                                <span style="display: inline-flex; align-items: center; width: fit-content; background: {{ $opp->type == 'volunteering' ? '#ecfdf5' : '#eff6ff' }}; color: {{ $opp->type == 'volunteering' ? '#059669' : '#2563eb' }}; padding: 0.2rem 0.75rem; border-radius: 0.5rem; font-size: 0.75rem; font-weight: 800; border: 1px solid {{ $opp->type == 'volunteering' ? '#d1fae5' : '#dbeafe' }};">
                                    <i class="{{ $opp->type == 'volunteering' ? 'fas fa-hand-holding-heart' : 'fas fa-graduation-cap' }}" style="margin-left: 0.4rem;"></i>
                                    {{ $opp->type == 'volunteering' ? 'تطوع' : 'تدريب' }}
                                </span>
                                <span style="font-size: 0.8rem; color: #64748b; font-weight: 600;">
                                    {{ $opp->category }}
                                </span>
                            </div>
                        </td>
                        <td style="padding: 1.5rem; text-align: center;">
                            <span style="font-weight: 800; color: #1e293b; background: #f1f5f9; padding: 0.4rem 0.8rem; border-radius: 0.75rem; font-size: 0.9rem;">
                                {{ $opp->seats }}
                            </span>
                        </td>
                        <td style="padding: 1.5rem; text-align: center;">
                            <a href="{{ route('organization.applications.index', ['opportunity_id' => $opp->id]) }}" style="text-decoration: none;">
                                <span style="font-weight: 800; color: #3b82f6; background: rgba(59, 130, 246, 0.1); padding: 0.4rem 0.8rem; border-radius: 0.75rem; font-size: 0.9rem; border: 1px solid rgba(59, 130, 246, 0.1);">
                                    {{ $opp->applications_count }} متقدم
                                </span>
                            </a>
                        </td>
                        <td style="padding: 1.5rem;">
                            @php
                                $statusLabels = [
                                    0 => ['text' => 'قيد المراجعة', 'bg' => '#fffbeb', 'color' => '#d97706', 'icon' => 'fa-clock'],
                                    1 => ['text' => 'منشورة', 'bg' => '#ecfdf5', 'color' => '#059669', 'icon' => 'fa-check-circle'],
                                    2 => ['text' => 'تعديلات مطلوبة', 'bg' => '#eff6ff', 'color' => '#2563eb', 'icon' => 'fa-info-circle'],
                                    3 => ['text' => 'مرفوضة', 'bg' => '#fef2f2', 'color' => '#dc2626', 'icon' => 'fa-times-circle'],
                                    8 => ['text' => 'ملغاة', 'bg' => '#f8fafc', 'color' => '#64748b', 'icon' => 'fa-ban'],
                                    9 => ['text' => 'مغلقة', 'bg' => '#f8fafc', 'color' => '#64748b', 'icon' => 'fa-lock'],
                                ];
                                $s = $statusLabels[(int)$opp->status] ?? ['text' => 'غير معروف', 'bg' => '#eee', 'color' => '#666', 'icon' => 'fa-question-circle'];
                            @endphp
                            <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: {{ $s['bg'] }}; color: {{ $s['color'] }}; padding: 0.4rem 1rem; border-radius: 2rem; font-weight: 800; font-size: 0.8rem; border: 1px solid rgba(0,0,0,0.03);">
                                <i class="fas {{ $s['icon'] }}"></i>
                                {{ $s['text'] }}
                            </div>
                        </td>
                        <td style="padding: 1.5rem;">
                            <div style="display: flex; gap: 0.75rem;">
                                <a href="{{ route('opportunities.show', $opp) }}" target="_blank" title="عرض" style="color: #64748b; background: white; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 0.75rem; text-decoration: none; border: 1px solid #e2e8f0; transition: all 0.2s;" onmouseover="this.style.color='#3b82f6'; this.style.borderColor='#3b82f6'" onmouseout="this.style.color='#64748b'; this.style.borderColor='#e2e8f0'">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('organization.opportunities.edit', $opp) }}" title="تعديل" style="color: #64748b; background: white; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 0.75rem; text-decoration: none; border: 1px solid #e2e8f0; transition: all 0.2s;" onmouseover="this.style.color='#10b981'; this.style.borderColor='#10b981'" onmouseout="this.style.color='#64748b'; this.style.borderColor='#e2e8f0'">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('organization.opportunities.destroy', $opp) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذه الفرصة نهائياً؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="حذف" style="color: #ef4444; background: white; border: 1px solid #fee2e2; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border-radius: 0.75rem; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#fee2e2'; this.style.color='#dc2626'" onmouseout="this.style.background='white'; this.style.color='#ef4444'">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 6rem 1.5rem; text-align: center;">
                            <div style="max-width: 300px; margin: 0 auto;">
                                <div style="width: 80px; height: 80px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                                    <i class="fas fa-folder-open" style="font-size: 2rem; color: #cbd5e1;"></i>
                                </div>
                                <h3 style="color: #1e293b; font-weight: 800; margin-bottom: 0.5rem;">لا توجد فرص حالياً</h3>
                                <p style="color: #64748b; font-size: 0.9rem; margin-bottom: 1.5rem;">ابدأ بإضافة فرصتك الأولى لتصل إلى المتطوعين</p>
                                <a href="{{ route('organization.opportunities.create') }}" 
                                   style="display: inline-block; background: #3b82f6; color: white; padding: 0.75rem 1.5rem; border-radius: 0.75rem; text-decoration: none; font-weight: 700; font-size: 0.9rem;">
                                    إضافة فرصة جديدة
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($opportunities->hasPages())
        <div style="margin-top: 2rem; display: flex; justify-content: center;">
            <div style="background: white; padding: 0.75rem; border-radius: 1rem; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
                {{ $opportunities->links() }}
            </div>
        </div>
    @endif
</div>

<style>
    /* Custom pagination styling if needed to match theme */
    .pagination {
        display: flex;
        gap: 0.5rem;
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .page-item .page-link {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        color: #64748b;
        text-decoration: none;
        font-weight: 700;
        transition: all 0.2s;
    }
    .page-item.active .page-link {
        background: #3b82f6;
        color: white;
    }
</style>
@endsection
