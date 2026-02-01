@extends('layouts.dashboard')

@section('title', 'تتبع المشاركين - ' . $opportunity->title)

@section('content')
<div style="font-family: 'Cairo', sans-serif;">
    <div style="margin-bottom: 2.5rem;">
        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
            <a href="{{ route('organization.opportunities.index') }}" style="color: #64748b; text-decoration: none; background: white; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 0.75rem; border: 1px solid #e2e8f0;">
                <i class="fas fa-arrow-right"></i>
            </a>
            <h2 style="margin: 0; color: #1e293b; font-weight: 850; font-size: 1.75rem;">تتبع المشاركين</h2>
        </div>
        <p style="color: #64748b; margin-right: 3.5rem; font-size: 0.95rem;">توثيق الحضور والالتزام لفرصة: <span style="font-weight: 700; color: #334155;">{{ $opportunity->title }}</span></p>
    </div>

    <div class="card" style="padding: 0; overflow: hidden; border-radius: 1.5rem; border: 1px solid #f1f5f9; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
        <table style="width: 100%; border-collapse: collapse; text-align: right;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #f1f5f9;">
                    <th style="padding: 1.5rem; color: #475569; font-weight: 800; font-size: 0.9rem;">المشارك</th>
                    <th style="padding: 1.5rem; color: #475569; font-weight: 800; font-size: 0.9rem;">الساعات المنجزة</th>
                    <th style="padding: 1.5rem; color: #475569; font-weight: 800; font-size: 0.9rem;">درجة الالتزام</th>
                    <th style="padding: 1.5rem; color: #475569; font-weight: 800; font-size: 0.9rem;">ملاحظات الأداء</th>
                    <th style="padding: 1.5rem; color: #475569; font-weight: 800; font-size: 0.9rem; text-align: center;">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $app)
                    <tr style="border-bottom: 1px solid #f1f5f9; transition: all 0.2s;">
                        <form action="{{ route('organization.applications.updateTracking', $app) }}" method="POST">
                            @csrf
                            <td style="padding: 1.5rem;">
                                <div style="display: flex; align-items: center; gap: 1rem;">
                                    <div style="width: 40px; height: 40px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #64748b; font-weight: 700; font-size: 0.9rem;">
                                        {{ mb_substr($app->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 800; color: #1e293b; font-size: 1rem;">{{ $app->user->name }}</div>
                                        <div style="font-size: 0.8rem; color: #94a3b8;">{{ $app->status == 'completed' ? 'مكتمل' : 'قيد التنفيذ' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 1.5rem;">
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <input type="number" name="attended_hours" value="{{ $app->attended_hours }}" min="0" max="{{ $opportunity->total_hours }}"
                                           style="width: 80px; padding: 0.5rem; border-radius: 0.5rem; border: 1px solid #e2e8f0; font-family: 'Cairo', sans-serif; font-weight: 700;">
                                    <span style="color: #64748b; font-size: 0.85rem;">/ {{ $opportunity->total_hours }} ساعة</span>
                                </div>
                            </td>
                            <td style="padding: 1.5rem;">
                                <select name="commitment_score" style="width: 120px; padding: 0.5rem; border-radius: 0.5rem; border: 1px solid #e2e8f0; font-family: 'Cairo', sans-serif; font-weight: 700;">
                                    <option value="">اختر التقييم</option>
                                    @for($i=5; $i>=1; $i--)
                                        <option value="{{ $i }}" {{ $app->commitment_score == $i ? 'selected' : '' }}>
                                            {{ $i }} {{ $i >= 3 ? '⭐' : '⭐' }}
                                        </option>
                                    @endfor
                                </select>
                            </td>
                            <td style="padding: 1.5rem;">
                                <textarea name="evaluation_notes" placeholder="ملاحظات إضافية..." rows="1"
                                          style="width: 100%; min-width: 200px; padding: 0.5rem; border-radius: 0.5rem; border: 1px solid #e2e8f0; font-family: 'Cairo', sans-serif; font-size: 0.85rem;">{{ $app->evaluation_notes }}</textarea>
                            </td>
                            <td style="padding: 1.5rem; text-align: center;">
                                <button type="submit" 
                                        style="background: #3b82f6; color: white; border: none; padding: 0.5rem 1rem; border-radius: 0.6rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 0.4rem; transition: all 0.2s;"
                                        onmouseover="this.style.background='#2563eb'"
                                        onmouseout="this.style.background='#3b82f6'">
                                    <i class="fas fa-save"></i> حفظ
                                </button>
                            </td>
                        </form>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 4rem 1.5rem; text-align: center; color: #64748b;">
                            <i class="fas fa-user-clock" style="font-size: 2.5rem; opacity: 0.5; margin-bottom: 1rem; display: block;"></i>
                            لا يوجد مشاركين مقبولين حالياً لبدء التتبع.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
