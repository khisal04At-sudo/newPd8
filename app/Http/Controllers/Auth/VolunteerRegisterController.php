<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\City;
use App\Mail\SendOtpMail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class VolunteerRegisterController extends Controller
{
    /**
     * عرض صفحة تسجيل المتطوع
     */
    public function create(): View
    {
        $cities = City::all();

        return view('auth.register-volunteer', compact('cities'));
    }

    /**
     * حفظ بيانات المتطوع
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password'    => ['required', 'confirmed', Rules\Password::defaults()],
            'city_id'     => ['required', 'exists:cities,id'],
            'gender'      => ['required', 'in:ذكر,أنثى'],
            'birth_date'  => ['required', 'date', 'before:today'],
        ], [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.confirmed' => 'كلمة المرور غير متطابقة',
            'city_id.required' => 'المدينة مطلوبة',
            'gender.required' => 'الجنس مطلوب',
            'birth_date.required' => 'تاريخ الميلاد مطلوب',
            'birth_date.before' => 'تاريخ الميلاد غير صحيح',
        ]);

        // توليد OTP
        $otpCode = rand(100000, 999999);

        // إنشاء المستخدم
        $user = User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'city_id'        => $request->city_id,
            'gender'         => $request->gender,
            'birth_date'     => $request->birth_date,
            'user_type'      => 'user', // نوع المستخدم
            'otp_code'       => $otpCode,
            'otp_expires_at' => now()->addMinutes(5), // صلاحية 5 دقائق
            'last_otp_sent_at' => now(),
            'is_verified'    => false,
            'is_active'      => true,
            'status'         => 0, // حساب جديد - غير مفعل
        ]);

        // إرسال OTP بالبريد
        try {
            Mail::to($user->email)->send(new SendOtpMail($otpCode, $user->name));
        } catch (\Exception $e) {
            \Log::error('Failed to send OTP email: ' . $e->getMessage());
            // Continue anyway - show OTP in logs for development
            \Log::info("OTP for {$user->email}: {$otpCode}");
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect()
            ->route('verify-otp')
            ->with('success', 'تم إرسال رمز التحقق إلى بريدك الإلكتروني');
    }
}
