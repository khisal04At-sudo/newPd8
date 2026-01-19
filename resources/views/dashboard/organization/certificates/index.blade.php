@extends('layouts.dashboard')

@section('title', 'إدارة الشهادات')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h2 style="margin: 0; color: #1e293b;">الشهادات الصادرة</h2>
    <span style="color: #64748b; font-size: 0.9rem;">إجمالي الشهادات: {{ $certificates->total() }}</span>
</div>

<div class="card" style="padding: 0; overflow: hidden;">
    <table style="width: 100%; border-collapse: collapse; text-align: right;">
        <thead>
            <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                <th style="padding: 1.25rem 1.5rem; color: #64748b; font-weight: 600; font-size: 0.85rem;">المتطوع</th>
                <th style="padding: 1.25rem 1.5rem; color: #64748b; font-weight: 600; font-size: 0.85rem;">الفرصة</th>
                <th style="padding: 1.25rem 1.5rem; color: #64748b; font-weight: 600; font-size: 0.85rem;">مستلمة في</th>
                <th style="padding: 1.25rem 1.5rem; color: #64748b; font-weight: 600; font-size: 0.85rem;">رقم الشهادة</th>
            </tr>
        </thead>
        <tbody>
            @forelse($certificates as $cert)
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 1.25rem 1.5rem;">
                        <div style="font-weight: 700; color: #1e293b;">{{ $cert->user->name }}</div>
                        <div style="font-size: 0.75rem; color: #64748b;">{{ $cert->user->email }}</div>
                    </td>
                    <td style="padding: 1.25rem 1.5rem; color: #475569; font-size: 0.9rem;">
                        {{ $cert->opportunity->title }}
                    </td>
                    <td style="padding: 1.25rem 1.5rem; color: #475569; font-size: 0.9rem;">
                        {{ $cert->issue_date->format('Y-m-d') }}
                    </td>
                    <td style="padding: 1.25rem 1.5rem;">
                        <code style="background: #f1f5f9; padding: 0.2rem 0.5rem; border-radius: 0.25rem; font-size: 0.85rem; color: #4338ca;">
                            {{ $cert->certificate_number }}
                        </code>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="padding: 4rem; text-align: center; color: #94a3b8;">لم يتم إصدار شهادات بعد</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 1.5rem;">
    {{ $certificates->links() }}
</div>
@endsection
