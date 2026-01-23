<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-center gradient-text mb-2">تسجيل حساب جديد</h2>
        <p class="text-center text-gray-600 text-sm">انضم إلى مجتمعنا وكن جزءًا من التغيير الإيجابي</p>
    </div>

    <form method="POST" action="{{ route('register.volunteer.store') }}" class="space-y-5">
        @csrf

        {{-- Name --}}
        <div class="space-y-2">
            <x-input-label for="name" value="الاسم الكامل" class="text-gray-700 font-semibold" />
            <div class="relative">
                <div class="absolute right-4 top-1/2 transform -translate-y-1/2 text-brand-500">
                    <i class="fas fa-user"></i>
                </div>
                <input 
                    id="name" 
                    class="input-modern pr-12" 
                    type="text" 
                    name="name" 
                    value="{{ old('name') }}" 
                    required 
                    autofocus
                    placeholder="أدخل اسمك بالكامل"
                />
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        {{-- Email --}}
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
                    placeholder="example@email.com"
                />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Password --}}
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
                    placeholder="أدخل كلمة المرور"
                />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Password Confirmation --}}
        <div class="space-y-2">
            <x-input-label for="password_confirmation" value="تأكيد كلمة المرور" class="text-gray-700 font-semibold" />
            <div class="relative">
                <div class="absolute right-4 top-1/2 transform -translate-y-1/2 text-brand-500">
                    <i class="fas fa-lock"></i>
                </div>
                <input 
                    id="password_confirmation" 
                    class="input-modern pr-12"
                    type="password"
                    name="password_confirmation"
                    required
                    placeholder="أعد إدخال كلمة المرور"
                />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        {{-- City + Birthday --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-2">
                <x-input-label for="city_id" value="المدينة" class="text-gray-700 font-semibold" />
                <div class="relative">
                    <div class="absolute right-4 top-1/2 transform -translate-y-1/2 text-brand-500">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <select 
                        id="city_id" 
                        name="city_id" 
                        class="input-modern pr-12"
                        required
                    >
                        <option value="">اختر مدينة</option>
                        @foreach ($cities as $city)
                            <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                {{ $city->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <x-input-error :messages="$errors->get('city_id')" class="mt-2" />
            </div>

            <div class="space-y-2">
                <x-input-label for="birth_date" value="تاريخ الميلاد" class="text-gray-700 font-semibold" />
                <div class="relative">
                    <div class="absolute right-4 top-1/2 transform -translate-y-1/2 text-brand-500">
                        <i class="fas fa-calendar"></i>
                    </div>
                    <input 
                        id="birth_date" 
                        type="date" 
                        name="birth_date"
                        value="{{ old('birth_date') }}"
                        class="input-modern pr-12"
                        required
                    />
                </div>
                <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
            </div>
        </div>

        {{-- Gender --}}
        <div class="space-y-2">
            <x-input-label for="gender" value="الجنس" class="text-gray-700 font-semibold" />
            <div class="grid grid-cols-2 gap-4">
                <label class="border-2 border-gray-200 rounded-xl p-3 text-center cursor-pointer hover:border-brand-500 hover:bg-brand-50 transition-all duration-300">
                    <input type="radio" name="gender" value="ذكر" class="ml-2" required {{ old('gender') == 'ذكر' ? 'checked' : '' }}>
                    <span>ذكر</span>
                </label>
                <label class="border-2 border-gray-200 rounded-xl p-3 text-center cursor-pointer hover:border-brand-500 hover:bg-brand-50 transition-all duration-300">
                    <input type="radio" name="gender" value="أنثى" class="ml-2" required {{ old('gender') == 'أنثى' ? 'checked' : '' }}>
                    <span>أنثى</span>
                </label>
            </div>
            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
        </div>

        {{-- Submit Button --}}
        <button type="submit" class="btn-brand w-full text-lg">
            <i class="fas fa-user-plus ml-2"></i>
            إنشاء حساب
        </button>

        {{-- Already have account --}}
        <div class="text-center mt-4">
            <p class="text-gray-700">
                لديك حساب بالفعل؟ 
                <a href="{{ route('login') }}" class="text-brand-600 hover:text-brand-700 font-bold transition-colors">
                    تسجيل الدخول
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
