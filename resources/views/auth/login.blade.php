<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-center gradient-text mb-2">تسجيل الدخول</h2>
        <p class="text-center text-gray-600 text-sm">مرحباً بعودتك! أدخل بياناتك للمتابعة</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div class="space-y-2">
            <x-input-label for="email" value="البريد الإلكتروني" class="text-gray-700 font-semibold" />
            <div class="relative">
                <div class="absolute right-4 top-1/2 transform -translate-y-1/2 text-brand-500">
                    <i class="fas fa-envelope"></i>
                </div>
                <input 
                    id="email" 
                    class="input-modern pr-12" 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus 
                    autocomplete="username"
                    placeholder="أدخل بريدك الإلكتروني"
                />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="space-y-2">
            <x-input-label for="password" value="كلمة المرور" class="text-gray-700 font-semibold" />
            <div class="relative">
                <div class="absolute right-4 top-1/2 transform -translate-y-1/2 text-brand-500">
                    <i class="fas fa-lock"></i>
                </div>
                <input 
                    id="password" 
                    class="input-modern pr-12"
                    type="password"
                    name="password"
                    required 
                    autocomplete="current-password"
                    placeholder="أدخل كلمة المرور"
                />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input 
                    id="remember_me" 
                    type="checkbox" 
                    class="rounded border-gray-300 text-brand-600 shadow-sm focus:ring-brand-500 focus:ring-2 cursor-pointer transition-all" 
                    name="remember"
                >
                <span class="mr-2 text-sm text-gray-700 group-hover:text-brand-600 transition-colors">تذكرني</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-brand-600 hover:text-brand-700 font-semibold transition-colors" href="{{ route('password.request') }}">
                    هل نسيت كلمة المرور؟
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-brand w-full text-lg">
            <i class="fas fa-sign-in-alt ml-2"></i>
            تسجيل الدخول
        </button>

        <!-- Divider -->
        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-4 bg-white text-gray-600">أو</span>
            </div>
        </div>

        <!-- Register Link -->
        <div class="text-center">
            <p class="text-gray-700">
                ليس لديك حساب؟ 
                <a href="{{ route('choose.account.type') }}" class="text-volunteer-600 hover:text-volunteer-700 font-bold transition-colors">
                    سجّل الآن
                </a>
            </p>
        </div>
    </form>

    <style>
        .gradient-text {
            background: linear-gradient(135deg, #16a34a 0%, #4338ca 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</x-guest-layout>
