<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\Message;

class MessageController extends Controller
{
    /**
     * Show messages list.
     */
    public function index()
    {
        $userId = Auth::id();
        $recentMessages = Message::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['sender', 'receiver'])
            ->latest()
            ->get()
            ->groupBy('conversation_id');
            
        return view('dashboard.messages.index', compact('recentMessages'));
    }
}
