<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserInterest;
use Illuminate\Support\Facades\Auth;


class UserProfileController extends Controller
{
    /**
     * Show the user profile.
     */
    public function show()
    {
        $user = Auth::user()->load(['interests', 'city', 'certificates.file']);
        return view('dashboard.profile.show', compact('user'));
    }

    /**
     * Show the form for editing the user profile.
     */
    public function edit()
    {
        $user = Auth::user()->load('city');
        $cities = \App\Models\City::all();
        $categories = UserInterest::$categories;
        $userInterests = $user->interests()->pluck('category')->toArray();
        return view('dashboard.profile.edit', compact('user', 'cities', 'categories', 'userInterests'));
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
            'cv' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
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

        if ($request->hasFile('cv')) {
            // Delete old cv if exists
            $oldCv = $user->files()->where('file_category', 'cv')->first();
            if ($oldCv) {
                $oldCv->deleteFile();
            }

            $file = $request->file('cv');
            $path = $file->store('users/cvs', 'public');

            \App\Models\File::create([
                'user_id' => $user->id,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientOriginalExtension(),
                'file_url' => $path,
                'file_size' => $file->getSize(),
                'file_category' => 'cv',
            ]);
        }

        // Save Interests
        $user->interests()->delete();
        if ($request->has('interests') && is_array($request->interests)) {
            $validCategories = array_keys(UserInterest::$categories);
            foreach ($request->interests as $category) {
                if (in_array($category, $validCategories)) {
                    UserInterest::create(['user_id' => $user->id, 'category' => $category]);
                }
            }
        }

        return redirect()->route('dashboard.profile')->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }
}
