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
            'city_id' => 'required|exists:cities,id',
            'address' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'social_links' => 'nullable|array',
        ]);

        $orgData = $request->only(['name', 'description', 'phone', 'address', 'city_id', 'social_links']);

        if ($request->hasFile('logo')) {
            // 1. Unify with standard File model (used by avatars)
            $oldAvatar = $user->getAvatar();
            if ($oldAvatar) {
                $oldAvatar->deleteFile();
            }

            // 2. Compatibility: Delete old logo_url file if it exists separately
            if ($org->logo_url) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($org->logo_url);
            }

            $file = $request->file('logo');
            $path = $file->store('organizations/logos', 'public');
            
            // Update legacy column for backward compatibility
            $orgData['logo_url'] = $path;

            // Create standard File record
            \App\Models\File::create([
                'user_id' => $user->id,
                'organization_id' => $org->id,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientOriginalExtension(),
                'file_url' => $path,
                'file_size' => $file->getSize(),
                'file_category' => 'avatar',
            ]);
        }

        // Keep User synced with Organization for identity consistency
        $user->update([
            'name' => $request->name,
            'city_id' => $request->city_id,
        ]);

        $org->update($orgData);

        return back()->with('success', 'تم تحديث الملف المؤسسي بنجاح.');
    }
}
