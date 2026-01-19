@extends('layouts.dashboard')

@section('title', 'الرسائل')

@section('content')
<div class="card" style="padding: 0; min-height: 500px; display: grid; grid-template-columns: 350px 1fr;">
    <!-- Conversations List -->
    <div style="border-left: 1px solid #f1f5f9;">
        <div style="padding: 20px; border-bottom: 1px solid #f1f5f9;">
            <h3 style="margin: 0;">المحادثات</h3>
        </div>
        <div class="conv-list">
            @forelse($recentMessages as $conversationId => $messages)
                @php $lastMsg = $messages->first(); @endphp
                <div style="padding: 15px 20px; border-bottom: 1px solid #f8fafc; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
                    <div style="display: flex; gap: 15px; align-items: center;">
                        <img src="{{ $lastMsg->sender_id == auth()->id() ? $lastMsg->receiver->avatar_url : $lastMsg->sender->avatar_url }}" style="width: 45px; height: 45px; border-radius: 50%;">
                        <div style="flex: 1;">
                            <div style="font-weight: 600; font-size: 14px;">{{ $lastMsg->sender_id == auth()->id() ? $lastMsg->receiver->name : $lastMsg->sender->name }}</div>
                            <div style="font-size: 12px; color: #64748b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; width: 180px;">{{ $lastMsg->content }}</div>
                        </div>
                        <div style="font-size: 10px; color: #94a3b8;">{{ $lastMsg->created_at->diffForHumans() }}</div>
                    </div>
                </div>
            @empty
                <p style="text-align: center; color: #94a3b8; padding: 40px;">لا توجد محادثات نشطة</p>
            @endforelse
        </div>
    </div>

    <!-- Message Content -->
    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; background: #f8fafc;">
        <i class="fas fa-comments" style="font-size: 60px; color: #e2e8f0; margin-bottom: 20px;"></i>
        <p style="color: #94a3b8;">اختر محادثة لبدء المراسلة</p>
    </div>
</div>
@endsection
