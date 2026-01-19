<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class AchievementController extends Controller
{
    /**
     * Show achievements list.
     */
    public function index()
    {
        $achievements = Auth::user()->achievements()->withPivot('earned_at')->get();
        return view('dashboard.achievements.index', compact('achievements'));
    }
}
