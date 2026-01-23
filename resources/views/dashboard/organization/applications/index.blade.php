@extends('layouts.dashboard')

@section('title', 'إدارة المتقدمين')

@section('content')
<div style="font-family: 'Cairo', sans-serif;">
    <div style="margin-bottom: 2.5rem;">
        <h2 style="margin: 0; color: #1e293b; font-weight: 850; font-size: 1.75rem;">طلبات الانضمام</h2>
        <p style="color: #64748b; margin-top: 0.25rem; font-size: 0.95rem;">راجع طلبات المتطوعين لاتخاذ قرار القبول أو الرفض</p>
    </div>

    @if(session('success'))
        <div style="background: #ecfdf5; color: #10b981; padding: 1.25rem; border-radius: 1.25rem; margin-bottom: 2rem; font-weight: 800; border: 1px solid #d1fae5; display: flex; align-items: center; gap: 0.75rem;">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="card" style="padding: 0; overflow: hidden; border-radius: 1.5rem; border: 1px solid #f1f5f9; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
        <table style="width: 100%; border-collapse: collapse; text-align: right;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #f1f5f9;">
                    <th style="padding: 1.5rem; color: #475569; font-weight: 800; font-size: 0.9rem;">المتطوع</th>
                    <th style="padding: 1.5rem; color: #475569; font-weight: 800; font-size: 0.9rem;">الفرصة المستهدفة</th>
                    <th style="padding: 1.5rem; color: #475569; font-weight: 800; font-size: 0.9rem;">المرفقات</th>
                    <th style="padding: 1.5rem; color: #475569; font-weight: 800; font-size: 0.9rem;">الحالة</th>
                    <th style="padding: 1.5rem; color: #475569; font-weight: 800; font-size: 0.9rem;">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $app)
                    <tr style="border-bottom: 1px solid #f1f5f9; transition: all 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                        <td style="padding: 1.5rem;">
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <div style="position: relative;">
                                    <img src="{{ $app->user->avatar_url ?? asset('assets/default-avatar.png') }}" style="width: 48px; height: 48px; border-radius: 1rem; object-fit: cover; border: 2px solid white; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                                    @if($app->user->is_verified)
                                    <div style="position: absolute; bottom: -2px; right: -2px; background: #3b82f6; color: white; width: 16px; height: 16px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.6rem; border: 2px solid white;">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    @endif
                                </div>
                                <div>
                                    <div style="font-weight: 800; color: #1e293b; font-size: 1rem;">{{ $app->user->name }}</div>
                                    <div style="font-size: 0.8rem; color: #94a3b8; font-weight: 600;">{{ $app->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 1.5rem;">
                            <div style="font-weight: 700; color: #475569; font-size: 0.95rem; max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $app->opportunity->title }}">
                                {{ $app->opportunity->title }}
                            </div>
                            <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 0.25rem;">
                                <i class="far fa-clock"></i> تم التقديم: {{ $app->created_at->diffForHumans() }}
                            </div>
                        </td>
                        <td style="padding: 1.5rem;">
                            @if($app->resum_file_id)
                               <a href="{{ asset($app->resumFile->file_url ?? '#') }}" target="_blank" style="display: inline-flex; align-items: center; gap: 0.5rem; background: #f1f5f9; color: #475569; padding: 0.4rem 0.8rem; border-radius: 0.75rem; text-decoration: none; font-size: 0.8rem; font-weight: 700; transition: all 0.2s;" onmouseover="this.style.background='#e2e8f0'; this.style.color='#3b82f6'" onmouseout="this.style.background='#f1f5f9'; this.style.color='#475569'">
                                   <i class="fas fa-file-pdf"></i> السيرة الذاتية
                               </a>
                            @else
                               <span style="color: #cbd5e1; font-size: 0.85rem; font-style: italic;">لا توجد مرفقات</span>
                            @endif
                        </td>
                        <td style="padding: 1.5rem;">
                            @php
                                $statusStyles = [
                                    'pending' => ['bg' => '#fffbeb', 'color' => '#d97706', 'label' => 'قيد المراجعة', 'icon' => 'fa-clock'],
                                    'accepted' => ['bg' => '#ecfdf5', 'color' => '#059669', 'label' => 'مقبول', 'icon' => 'fa-check-circle'],
                                    'rejected' => ['bg' => '#fef2f2', 'color' => '#dc2626', 'label' => 'مرفوض', 'icon' => 'fa-times-circle'],
                                ];
                                $style = $statusStyles[$app->status] ?? ['bg' => '#f8fafc', 'color' => '#64748b', 'label' => $app->status, 'icon' => 'fa-question-circle'];
                            @endphp
                            <span style="display: inline-flex; align-items: center; gap: 0.4rem; background: {{ $style['bg'] }}; color: {{ $style['color'] }}; padding: 0.4rem 1rem; border-radius: 2rem; font-size: 0.8rem; font-weight: 800; border: 1px solid rgba(0,0,0,0.03);">
                                <i class="fas {{ $style['icon'] }}"></i>
                                {{ $style['label'] }}
                            </span>
                        </td>
                        <td style="padding: 1.5rem;">
                            @if($app->status === 'pending')
                                <div style="display: flex; gap: 0.75rem;">
                                    <form action="{{ route('organization.applications.updateStatus', $app) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="accepted">
                                        <button type="submit" style="background: #10b981; color: white; border: none; padding: 0.5rem 1.25rem; border-radius: 0.75rem; font-size: 0.85rem; font-weight: 800; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2);" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 6px 12px -1px rgba(16, 185, 129, 0.3)'" onmouseout="this.style.transform='none'; this.style.boxShadow='0 4px 6px -1px rgba(16, 185, 129, 0.2)'">قبول</button>
                                    </form>
                                    <form action="{{ route('organization.applications.updateStatus', $app) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" style="background: white; color: #ef4444; border: 1px solid #fee2e2; padding: 0.5rem 1.25rem; border-radius: 0.75rem; font-size: 0.85rem; font-weight: 800; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='#fef2f2'" onmouseout="this.style.background='white'">رفض</button>
                                    </form>
                                </div>
                            @else
                                <div style="display: flex; align-items: center; gap: 0.5rem; color: #94a3b8; font-size: 0.85rem; font-weight: 600;">
                                    <i class="fas fa-check-double"></i>
                                    تمت المعالجة
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 6rem 1.5rem; text-align: center;">
                            <div style="max-width: 300px; margin: 0 auto;">
                                <div style="width: 80px; height: 80px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                                    <i class="fas fa-user-clock" style="font-size: 2rem; color: #cbd5e1;"></i>
                                </div>
                                <h3 style="color: #1e293b; font-weight: 800; margin-bottom: 0.5rem;">لا توجد طلبات</h3>
                                <p style="color: #64748b; font-size: 0.9rem;">بمجرد أن يتقدم المتطوعون لفرصك، ستظهر طلباتهم هنا</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($applications->hasPages())
        <div style="margin-top: 2.5rem; display: flex; justify-content: center;">
            <div style="background: white; padding: 0.75rem; border-radius: 1rem; border: 1px solid #f1f5f9; box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
                {{ $applications->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
