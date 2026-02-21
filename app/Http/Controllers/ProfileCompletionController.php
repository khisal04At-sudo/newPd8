<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserInterest;
use App\Helpers\FileUploadHelper;
use Illuminate\Support\Facades\Auth;

class ProfileCompletionController extends Controller
{
    /**
     * Show the profile completion form.
     */
    public function show()
    {
        $user = Auth::user();

        if (!$user->is_verified) {
            return redirect()->route('verify-otp');
        }

        $categories = UserInterest::$categories;
        $userInterests = $user->interests()->pluck('category')->toArray();

        return view('auth.complete-profile', compact('user', 'categories', 'userInterests'));
    }

    /**
     * Store profile data.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'bio'         => 'nullable|string|max:1000',
            'avatar'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'cv'          => 'nullable|mimes:pdf,doc,docx|max:5120',
            'interests'   => 'nullable|array',
            'interests.*' => 'string|in:' . implode(',', array_keys(UserInterest::$categories)),
        ]);

        // Save Bio
        $user->update(['bio' => $request->bio]);

        // Handle Avatar
        if ($request->hasFile('avatar')) {
            $oldAvatar = $user->files()->where('file_category', 'avatar')->first();
            if ($oldAvatar) {
                FileUploadHelper::delete($oldAvatar->id);
            }
            FileUploadHelper::upload($request->file('avatar'), 'avatar', $user->id);
        }

        // Handle CV
        if ($request->hasFile('cv')) {
            $oldCv = $user->files()->where('file_category', 'cv')->first();
            if ($oldCv) {
                FileUploadHelper::delete($oldCv->id);
            }
            FileUploadHelper::upload($request->file('cv'), 'cv', $user->id);
        }

        // Handle Interests
        $user->interests()->delete(); // Reset
        if ($request->has('interests')) {
            foreach ($request->interests as $category) {
                UserInterest::create([
                    'user_id'  => $user->id,
                    'category' => $category,
                ]);
            }
        }

        return redirect()->route('dashboard')->with('success', 'تم إكمال ملفك الشخصي بنجاح!');
    }
}
