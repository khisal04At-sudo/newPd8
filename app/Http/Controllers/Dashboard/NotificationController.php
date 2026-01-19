<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Show notifications list.
     */
    public function index()
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(15);
        Auth::user()->notifications()->where('is_read', false)->update(['is_read' => true]);
        return view('dashboard.notifications.index', compact('notifications'));
    }
}
