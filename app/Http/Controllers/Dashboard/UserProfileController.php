<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class UserProfileController extends Controller
{
    /**
     * Show the user profile.
     */
    public function show()
    {
        $user = Auth::user()->load(['skills', 'city', 'certificates.file']);
        return view('dashboard.profile.show', compact('user'));
    }
}
