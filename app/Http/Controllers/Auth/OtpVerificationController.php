<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpMail;
use Carbon\Carbon;

class OtpVerificationController extends Controller
{
    /**
     * عرض صفحة إدخال OTP
     */
    public function show()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'يجب تسجيل الدخول أولاً');
        }

        if ($user->is_verified && $user->status == 1) {
            return redirect()->route('dashboard');
        }

        return view('auth.verify-otp', [
            'email' => $user->email,
            'canResend' => $user->canResendOtp(),
        ]);
    }

    /**
     * التحقق من رمز OTP
     */
    public function verify(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|digits:6',
        ], [
            'otp_code.required' => 'الرجاء إدخال رمز التحقق',
            'otp_code.digits' => 'رمز التحقق يجب أن يكون 6 أرقام',
        ]);

        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'يجب تسجيل الدخول أولاً');
        }

        // Log للتحقق من القيم الحالية
        \Log::info('OTP Verification Attempt', [
            'user_id' => $user->id,
            'email' => $user->email,
            'entered_otp' => $request->otp_code,
            'stored_otp' => $user->otp_code,
            'otp_expires_at' => $user->otp_expires_at?->format('Y-m-d H:i:s'),
            'is_expired' => $user->otp_expires_at && Carbon::now()->isAfter($user->otp_expires_at),
        ]);

        // Check if OTP is valid
        if (!$user->isOtpValid($request->otp_code)) {
            // Check if OTP is expired
            if ($user->otp_expires_at && Carbon::now()->isAfter($user->otp_expires_at)) {
                \Log::warning('OTP Expired', ['user_id' => $user->id]);
                return back()->withErrors([
                    'otp_code' => 'انتهت صلاحية رمز التحقق. الرجاء طلب رمز جديد.'
                ])->withInput();
            }

            \Log::warning('OTP Invalid', ['user_id' => $user->id, 'entered' => $request->otp_code]);
            return back()->withErrors([
                'otp_code' => 'رمز التحقق غير صحيح. الرجاء المحاولة مرة أخرى.'
            ])->withInput();
        }

        // OTP is valid - activate account
        $user->update([
            'is_verified' => true,
            'status' => 1, // Active status
            'email_verified_at' => now(),
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);

        if ($user->user_type === 'organization') {
            return redirect()
                ->route('organization.verify.documents')
                ->with('success', 'تم تأكيد حسابك بنجاح! يرجى رفع مستندات التحقق لاعتماد المؤسسة.');
        }

        return redirect()
            ->route('profile.complete')
            ->with('success', 'تم تأكيد حسابك بنجاح! الرجاء إكمال ملفك الشخصي.');
    }

    /**
     * إعادة إرسال رمز OTP
     */
    public function resend(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'يجب تسجيل الدخول أولاً'
            ], 401);
        }

        // Check if already verified
        if ($user->is_verified && $user->status == 1) {
            return response()->json([
                'success' => false,
                'message' => 'الحساب مفعل بالفعل'
            ]);
        }

        // Check resend cooldown
        if (!$user->canResendOtp()) {
            $lastSent = Carbon::parse($user->last_otp_sent_at);
            $secondsPassed = $lastSent->diffInSeconds(Carbon::now(), false);
            // diffInSeconds with false = signed: negative means last_otp_sent_at is in the future (timezone bug)
            // In any case, cap at 0 minimum
            $secondsRemaining = max(0, 60 - max(0, $secondsPassed));
            return response()->json([
                'success' => false,
                'message' => "الرجاء الانتظار {$secondsRemaining} ثانية قبل إعادة الإرسال",
                'seconds_remaining' => $secondsRemaining
            ]);
        }

        // Generate new OTP
        $otpCode = rand(100000, 999999);

        // Update user with new OTP using DB directly to avoid model cache issues
        \Illuminate\Support\Facades\DB::table('users')->where('id', $user->id)->update([
            'otp_code' => $otpCode,
            'otp_expires_at' => now()->addMinutes(5)->toDateTimeString(),
            'last_otp_sent_at' => now()->toDateTimeString(),
        ]);

        // تحديث كائن المستخدم من قاعدة البيانات للتأكد من الحفظ
        $user->refresh();

        // Log للتحقق من حفظ البيانات بشكل صحيح
        \Log::info('OTP Resend - Data Saved', [
            'user_id' => $user->id,
            'email' => $user->email,
            'otp_code' => $otpCode,
            'otp_expires_at' => $user->otp_expires_at?->format('Y-m-d H:i:s'),
            'last_otp_sent_at' => $user->last_otp_sent_at?->format('Y-m-d H:i:s'),
        ]);

        // Send OTP via email
        try {
            Mail::to($user->email)->send(new SendOtpMail($otpCode, $user->name));
        } catch (\Exception $e) {
            \Log::error('Failed to send OTP email: ' . $e->getMessage());
            // Continue anyway - user can still use the OTP
        }

        return response()->json([
            'success' => true,
            'message' => 'تم إرسال رمز تحقق جديد إلى بريدك الإلكتروني',
            'cooldown_seconds' => 60
        ]);
    }
}
