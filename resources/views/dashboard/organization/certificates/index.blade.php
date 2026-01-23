@extends('layouts.dashboard')

@section('title', 'إدارة الشهادات')

@section('content')
<div style="font-family: 'Cairo', sans-serif;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem;">
        <div>
            <h2 style="margin: 0; color: #1e293b; font-weight: 850; font-size: 1.75rem;">الشهادات الصادرة</h2>
            <p style="color: #64748b; margin-top: 0.25rem; font-size: 0.95rem;">تتبع الشهادات الممنوحة للمتطوعين المتميزين</p>
        </div>
        <div style="background: white; padding: 0.75rem 1.5rem; border-radius: 1rem; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); display: flex; align-items: center; gap: 0.75rem;">
            <i class="fas fa-certificate" style="color: #f59e0b;"></i>
            <span style="color: #475569; font-weight: 700;">إجمالي الشهادات:</span>
            <span style="color: #1e293b; font-weight: 900; font-size: 1.1rem;">{{ $certificates->total() }}</span>
        </div>
    </div>

    <div class="card" style="padding: 0; overflow: hidden; border-radius: 1.5rem; border: 1px solid #f1f5f9; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
        <table style="width: 100%; border-collapse: collapse; text-align: right;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #f1f5f9;">
                    <th style="padding: 1.5rem; color: #475569; font-weight: 800; font-size: 0.9rem;">المتطوع</th>
                    <th style="padding: 1.5rem; color: #475569; font-weight: 800; font-size: 0.9rem;">الفرصة المكتملة</th>
                    <th style="padding: 1.5rem; color: #475569; font-weight: 800; font-size: 0.9rem;">تاريخ الإصدار</th>
                    <th style="padding: 1.5rem; color: #475569; font-weight: 800; font-size: 0.9rem;">رقم التحقق</th>
                </tr>
            </thead>
            <tbody>
                @forelse($certificates as $cert)
                    <tr style="border-bottom: 1px solid #f1f5f9; transition: all 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <td style="padding: 1.5rem;">
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <div style="width: 40px; height: 40px; background: #eff6ff; color: #3b82f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; font-weight: 900;">
                                    {{ mb_substr($cert->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div style="font-weight: 800; color: #1e293b; font-size: 1rem;">{{ $cert->user->name }}</div>
                                    <div style="font-size: 0.8rem; color: #94a3b8; font-weight: 600;">{{ $cert->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 1.5rem;">
                            <div style="font-weight: 700; color: #475569; font-size: 0.95rem;">
                                {{ $cert->opportunity->title }}
                            </div>
                            <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 0.25rem;">
                                <i class="fas fa-check-circle" style="color: #10b981;"></i> تم الإتمام بنجاح
                            </div>
                        </td>
                        <td style="padding: 1.5rem;">
                            <div style="color: #1e293b; font-weight: 800; font-size: 0.9rem;">
                                {{ $cert->issue_date->format('Y/m/d') }}
                            </div>
                        </td>
                        <td style="padding: 1.5rem;">
                            <code style="background: #f1f5f9; color: #4338ca; padding: 0.4rem 0.75rem; border-radius: 0.5rem; font-size: 0.85rem; font-weight: 800; border: 1px solid #e2e8f0; letter-spacing: 0.5px;">
                                {{ $cert->certificate_number }}
                            </code>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding: 6rem 1.5rem; text-align: center;">
                            <div style="max-width: 300px; margin: 0 auto;">
                                <div style="width: 80px; height: 80px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                                    <i class="fas fa-award" style="font-size: 2.5rem; color: #cbd5e1;"></i>
                                </div>
                                <h3 style="color: #1e293b; font-weight: 800; margin-bottom: 0.5rem;">لا توجد شهادات</h3>
                                <p style="color: #64748b; font-size: 0.9rem;">لم يتم إصدار أي شهادات تطوعية من قبل مؤسستكم حتى الآن</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($certificates->hasPages())
        <div style="margin-top: 2.5rem; display: flex; justify-content: center;">
            <div style="background: white; padding: 0.75rem; border-radius: 1rem; border: 1px solid #f1f5f9; box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
                {{ $certificates->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
