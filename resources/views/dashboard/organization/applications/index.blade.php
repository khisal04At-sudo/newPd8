@extends('layouts.dashboard')

@section('title', 'إدارة المتقدمين')

@section('content')
<h2 style="margin-bottom: 2rem; color: #1e293b;">طلبات الانضمام</h2>

<div class="card" style="padding: 0; overflow: hidden;">
    <table style="width: 100%; border-collapse: collapse; text-align: right;">
        <thead>
            <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                <th style="padding: 1.25rem 1.5rem; color: #64748b; font-weight: 600; font-size: 0.85rem;">المتطوع</th>
                <th style="padding: 1.25rem 1.5rem; color: #64748b; font-weight: 600; font-size: 0.85rem;">الفرصة</th>
                <th style="padding: 1.25rem 1.5rem; color: #64748b; font-weight: 600; font-size: 0.85rem;">السيرة الذاتية</th>
                <th style="padding: 1.25rem 1.5rem; color: #64748b; font-weight: 600; font-size: 0.85rem;">الحالة</th>
                <th style="padding: 1.25rem 1.5rem; color: #64748b; font-weight: 600; font-size: 0.85rem;">إجراء</th>
            </tr>
        </thead>
        <tbody>
            @forelse($applications as $app)
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 1.25rem 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <img src="{{ $app->user->avatar_url }}" style="width: 35px; height: 35px; border-radius: 50%; object-fit: cover;">
                            <div>
                                <div style="font-weight: 700; color: #1e293b;">{{ $app->user->name }}</div>
                                <div style="font-size: 0.75rem; color: #64748b;">{{ $app->user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 1.25rem 1.5rem; color: #475569; font-size: 0.9rem;">
                        {{ $app->opportunity->title }}
                    </td>
                    <td style="padding: 1.25rem 1.5rem;">
                        @if($app->resum_file_id)
                           <a href="{{ asset($app->resumFile->file_url ?? '#') }}" target="_blank" style="color: #4f46e5; text-decoration: none; font-size: 0.85rem;">
                               <i class="fas fa-file-pdf"></i> عرض السيرة
                           </a>
                        @else
                           <span style="color: #94a3b8; font-size: 0.85rem;">لا يوجد</span>
                        @endif
                    </td>
                    <td style="padding: 1.25rem 1.5rem;">
                        @php
                            $statusColors = ['pending' => '#f59e0b', 'accepted' => '#16a34a', 'rejected' => '#ef4444'];
                            $statusLabels = ['pending' => 'قيد المراجعة', 'accepted' => 'مقبول', 'rejected' => 'مرفوض'];
                        @endphp
                        <span style="color: {{ $statusColors[$app->status] ?? '#64748b' }}; font-weight: 600; font-size: 0.85rem;">
                            {{ $statusLabels[$app->status] ?? $app->status }}
                        </span>
                    </td>
                    <td style="padding: 1.25rem 1.5rem;">
                        @if($app->status === 'pending')
                            <div style="display: flex; gap: 0.5rem;">
                                <form action="{{ route('organization.applications.updateStatus', $app) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="accepted">
                                    <button type="submit" style="background: #16a34a; color: white; border: none; padding: 0.4rem 0.8rem; border-radius: 0.4rem; font-size: 0.8rem; cursor: pointer;">قبول</button>
                                </form>
                                <form action="{{ route('organization.applications.updateStatus', $app) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" style="background: #ef4444; color: white; border: none; padding: 0.4rem 0.8rem; border-radius: 0.4rem; font-size: 0.8rem; cursor: pointer;">رفض</button>
                                </form>
                            </div>
                        @else
                            <span style="color: #94a3b8; font-size: 0.8rem;">تم اتخاذ قرار</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="padding: 4rem; text-align: center; color: #94a3b8;">لا توجد طلبات انضمام حالياً</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 1.5rem;">
    {{ $applications->links() }}
</div>
@endsection
