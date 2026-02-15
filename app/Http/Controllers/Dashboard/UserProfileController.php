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

    /**
     * Show the form for editing the user profile.
     */
    public function edit()
    {
        $user = Auth::user()->load('city');
        $cities = \App\Models\City::all();
        return view('dashboard.profile.edit', compact('user', 'cities'));
    }

    /**
     * Update the user profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'city_id' => ['required', 'exists:cities,id'],
            'phone' => ['nullable', 'string', 'max:20', 'unique:users,phone,' . $user->id],
            'birth_date' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:ذكر,أنثى'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $user->update($request->only(['name', 'bio', 'city_id', 'phone', 'birth_date', 'gender']));

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            $oldAvatar = $user->getAvatar();
            if ($oldAvatar) {
                $oldAvatar->deleteFile();
            }

            $file = $request->file('avatar');
            $path = $file->store('users/avatars', 'public');

            \App\Models\File::create([
                'user_id' => $user->id,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientOriginalExtension(),
                'file_url' => $path,
                'file_size' => $file->getSize(),
                'file_category' => 'avatar',
            ]);
        }

        return redirect()->route('dashboard.profile')->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }
}
