<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\City;

class OrganizationProfileController extends Controller
{
    /**
     * Show the organization profile.
     */
    public function show()
    {
        $user = Auth::user()->load('organization', 'city');
        return view('dashboard.organization.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user()->load('organization');
        $cities = City::all();
        return view('dashboard.organization.profile', compact('user', 'cities'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $org = $user->organization;

        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'social_links' => 'nullable|array',
        ]);

        $orgData = $request->only(['name', 'description', 'phone', 'address', 'social_links']);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('organizations/logos', 'public');
            $orgData['logo_url'] = $path;
        }

        $org->update($orgData);

        return back()->with('success', 'تم تحديث الملف المؤسسي بنجاح.');
    }
}
