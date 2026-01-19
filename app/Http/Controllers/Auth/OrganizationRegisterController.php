<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\City;
use App\Models\Organization;
use App\Mail\SendOtpMail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class OrganizationRegisterController extends Controller
{
    /**
     * عرض صفحة تسجيل المنظمة
     */
    public function createOrganization(): View
    {
        $cities = City::all();
        return view('auth.register-organization', compact('cities'));
    }

    /**
     * حفظ بيانات المنظمة
     */
    public function storeOrganization(Request $request): RedirectResponse
    {
        $request->validate([
            'name'                => ['required', 'string', 'max:255'],
            'email'               => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password'            => ['required', 'confirmed', Rules\Password::defaults()],
            'phone'               => ['required', 'string', 'max:20'],
            'city_id'             => ['required', 'exists:cities,id'],
            'organization_type'   => ['required', 'in:volunteering,training,both'],
            'sector'              => ['required', 'in:private,public,initiative,non_profit'],
            'registration_number' => ['nullable', 'string', 'max:100'],
        ], [
            'name.required' => 'اسم المؤسسة مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
            'phone.required' => 'رقم الهاتف مطلوب',
            'city_id.required' => 'المدينة مطلوبة',
            'organization_type.required' => 'نوع المؤسسة مطلوب',
            'sector.required' => 'قطاع المؤسسة مطلوب',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.confirmed' => 'برجاء تأكيد كلمة المرور',
        ]);

        return DB::transaction(function () use ($request) {
            // توليد OTP
            $otpCode = rand(100000, 999999);

            // إنشاء المستخدم المرتبط بالمؤسسة
            $user = User::create([
                'name'           => $request->name,
                'email'          => $request->email,
                'password'       => Hash::make($request->password),
                'city_id'        => $request->city_id,
                'user_type'      => 'organization',
                'otp_code'       => $otpCode,
                'otp_expires_at' => now()->addMinutes(5),
                'last_otp_sent_at' => now(),
                'is_verified'    => false,
                'status'         => 0, // غير مفعل
            ]);

            // إنشاء بيانات المؤسسة
            Organization::create([
                'user_id'             => $user->id,
                'name'                => $request->name,
                'email'               => $request->email, // Optional if kept in users
                'phone'               => $request->phone,
                'city_id'             => $request->city_id,
                'organization_type'   => $request->organization_type,
                'sector'              => $request->sector,
                'registration_number' => $request->registration_number,
                'verified'            => false,
            ]);

            // إرسال OTP بالبريد
            try {
                Mail::to($user->email)->send(new SendOtpMail($otpCode, $user->name));
            } catch (\Exception $e) {
                \Log::error('Failed to send OTP email: ' . $e->getMessage());
                \Log::info("OTP for {$user->email}: {$otpCode}");
            }

            event(new Registered($user));

            Auth::login($user);

            return redirect()
                ->route('verify-otp')
                ->with('success', 'تم تسجيل حسابك بنجاح. يرجى تفعيل الحساب برمز OTP المرسل لبريدك');
        });
    }
}
