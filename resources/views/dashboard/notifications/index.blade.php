@extends('layouts.dashboard')

@section('title', 'التنبيهات')

@section('content')
<div class="card">
    <h3 style="margin-top: 0; margin-bottom: 25px;">الإشعارات والتنبيهات</h3>
    
    <div class="notifications-list">
        @forelse($notifications as $notification)
            <div style="padding: 20px; border-bottom: 1px solid #f1f5f9; display: flex; gap: 20px; align-items: center; {{ !$notification->is_read ? 'background: #f8fafc;' : '' }}">
                <div style="width: 45px; height: 45px; border-radius: 50%; background: {{ $notification->type_color == 'indigo' ? '#e0e7ff' : ($notification->type_color == 'emerald' ? '#dcfce7' : ($notification->type_color == 'amber' ? '#fef3c7' : '#f1f5f9')) }}; display: flex; align-items: center; justify-content: center; color: {{ $notification->type_color == 'indigo' ? '#4338ca' : ($notification->type_color == 'emerald' ? '#059669' : ($notification->type_color == 'amber' ? '#d97706' : '#475569')) }};">
                    <i class="fas {{ $notification->type_icon }}"></i>
                </div>
                <div style="flex: 1;">
                    <div style="font-weight: 600; color: #1e293b;">{{ $notification->title }}</div>
                    <div style="color: #64748b; font-size: 14px; margin-top: 4px;">{{ $notification->message }}</div>
                    <div style="color: #94a3b8; font-size: 12px; margin-top: 8px;">{{ $notification->created_at->diffForHumans() }}</div>
                </div>
                @if(!$notification->is_read)
                    <div style="width: 10px; height: 10px; border-radius: 50%; background: #4f46e5;"></div>
                @endif
            </div>
        @empty
            <div style="text-align: center; padding: 50px; color: #94a3b8;">
                <i class="fas fa-bell-slash" style="font-size: 50px; margin-bottom: 20px; display: block;"></i>
                لا توجد إشعارات حالياً.
            </div>
        @endforelse
    </div>

    <div style="margin-top: 25px;">
        {{ $notifications->links() }}
    </div>
</div>
@endsection
