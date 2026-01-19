<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\City;
use App\Mail\SendOtpMail;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function createVolunteer(): View
    {
        $cities = City::all();
        return view('auth.register-volunteer', compact('cities'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */


    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'city_id' => ['required', 'exists:cities,id'],
            'gender' => ['required', 'in:ذكر,أنثى'],
            'birth_date' => ['required', 'date'],
        ]);

        $otp_code = rand(100000, 999999);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'city_id' => $request->city_id,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
            'otp_code' => $otp_code,
            'otp_expires_at' => now()->addMinutes(5),
            'user_type' => 'user',

        ]);
        // $user->update([
        //     'otp_code' => $otp_code,
        //     'otp_expires_at' => now()->addMinutes(5)
        // ]);

        // Mail::to($user->email)->send(new SendOtpMail($otp_code));


        event(new Registered($user));

        Auth::login($user);
        return redirect()->route('verify-otp')->with('success', 'تم إرسال الكود إلى بريدك الإلكتروني');
    }

    public function storeVolunteer(Request $request): RedirectResponse
    {
        return $this->store($request);
    }

    /**
     * Display the registration view for organizations.
     */

    public function createOrganization()
    {
        return view('auth.register-organization');
    }
    public function storeOrganization(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
