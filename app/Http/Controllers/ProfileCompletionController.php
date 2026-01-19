<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserSkill;
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
        
        // Ensure user is verified but hasn't completed profile (logic can be expanded)
        if (!$user->is_verified) {
            return redirect()->route('verify-otp');
        }

        return view('auth.complete-profile', compact('user'));
    }

    /**
     * Store profile data.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'bio' => 'nullable|string|max:1000',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'cv' => 'nullable|mimes:pdf,doc,docx|max:5120',
            'skills' => 'nullable|array',
            'skills.*.name' => 'required_with:skills|string|max:100',
            'skills.*.level' => 'required_with:skills|in:beginner,intermediate,advanced,expert',
        ]);

        // Save Bio
        $user->update(['bio' => $request->bio]);

        // Handle Avatar
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
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

        // Handle Skills
        if ($request->has('skills')) {
            $user->skills()->delete(); // Reset skills
            foreach ($request->skills as $skillData) {
                if (!empty($skillData['name'])) {
                    UserSkill::create([
                        'user_id' => $user->id,
                        'skill_name' => $skillData['name'],
                        'proficiency_level' => $skillData['level'],
                    ]);
                }
            }
        }

        return redirect()->route('dashboard')->with('success', 'تم إكمال ملفك الشخصي بنجاح!');
    }
}

