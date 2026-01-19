<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class MyOpportunitiesController extends Controller
{
    /**
     * Display a listing of the user's applications.
     */
    public function index()
    {
        $applications = Auth::user()->applications()->with('opportunity.organization')->latest()->get();
        return view('dashboard.opportunities.index', compact('applications'));
    }
}
