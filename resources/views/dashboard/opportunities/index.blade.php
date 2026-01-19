@extends('layouts.dashboard')

@section('title', 'فرصي التطوعية')

@section('content')
<div class="card">
    <h3 style="margin-top: 0; margin-bottom: 25px;">قائمة طلبات التطوع والتدريب</h3>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; text-align: right;">
            <thead>
                <tr style="border-bottom: 2px solid #f1f5f9;">
                    <th style="padding: 15px;">الفرصة</th>
                    <th style="padding: 15px;">المنظمة</th>
                    <th style="padding: 15px;">التاريخ</th>
                    <th style="padding: 15px;">الحالة</th>
                    <th style="padding: 15px;">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $app)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 15px;">
                            <div style="font-weight: 600;">{{ $app->opportunity->title }}</div>
                            <div style="font-size: 12px; color: #64748b;">{{ $app->opportunity->type == 'volunteering' ? 'تطوع' : 'تدريب' }}</div>
                        </td>
                        <td style="padding: 15px;">{{ $app->opportunity->organization->name }}</td>
                        <td style="padding: 15px;">{{ $app->created_at->format('Y/m/d') }}</td>
                        <td style="padding: 15px;">
                            @php
                                $statusColors = [
                                    'pending' => ['bg' => '#fef3c7', 'text' => '#92400e', 'label' => 'قيد المراجعة'],
                                    'accepted' => ['bg' => '#dcfce7', 'text' => '#166534', 'label' => 'مقبول'],
                                    'rejected' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'label' => 'مرفوض'],
                                    'completed' => ['bg' => '#e0e7ff', 'text' => '#3730a3', 'label' => 'مكتمل'],
                                ];
                                $status = $statusColors[$app->status] ?? ['bg' => '#f1f5f9', 'text' => '#475569', 'label' => $app->status];
                            @endphp
                            <span style="background: {{ $status['bg'] }}; color: {{ $status['text'] }}; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                                {{ $status['label'] }}
                            </span>
                        </td>
                        <td style="padding: 15px;">
                            <a href="#" style="color: #4f46e5; text-decoration: none; font-size: 14px;"><i class="fas fa-eye"></i> عرض التفاصيل</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 40px; text-align: center; color: #94a3b8;">
                            لم تقم بالتقديم على أي فرصة حتى الآن.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
