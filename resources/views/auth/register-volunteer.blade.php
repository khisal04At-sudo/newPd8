<x-guest-layout>

    <div class="min-h-screen bg-[#ebf4fa] flex items-center justify-center p-6">

        <div class="bg-white shadow-xl rounded-2xl grid grid-cols-1 md:grid-cols-2 w-full max-w-5xl">

            {{-- Left Section --}}
            <div class="bg-[#e0e6ec] flex flex-col justify-center items-center p-10 text-center rounded-r-2xl">
                <div class="mb-6">
                    <div class="relative">
                        <img src="{{ asset('images/logo.png') }}" alt="Athira Logo"
                            class="w-32 h-32 mx-auto relative -top-18">
                    </div>
                </div>

                <h2 class="text-3xl font-bold text-[#235481] mb-3">مرحباً بك في أثيرا</h2>
                <p class="text-gray-700 leading-relaxed">
                    انضم إلى مجتمعنا وكن جزءًا من التغيير الإيجابي، فرص تطوعية
                    وتدريبية بانتظارك.
                </p>
            </div>

            {{-- Right Section (Form) --}}
            <div class="p-10">

                <h2 class="text-3xl font-bold mb-2">تسجيل حساب جديد</h2>
                <p class="text-gray-500 mb-6">
                    لديك حساب بالفعل؟
                    <a href="{{ route('login') }}" class="text-[#80c2ff] hover:underline">تسجيل الدخول</a>
                </p>

                <form method="POST" action="{{ route('register.volunteer.store') }}" class="space-y-4">
                    @csrf

                    {{-- Name + Email --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="font-semibold">الاسم</label>
                            <input type="text" name="name"
                                class="mt-2 w-full border-gray-300 rounded-xl p-3 focus:ring-[#235481] focus:border-[#235481]"
                                placeholder="أدخل اسمك بالكامل">
                        </div>

                        <div>
                            <label class="font-semibold">البريد الإلكتروني</label>
                            <input type="email" name="email"
                                class="mt-2 w-full border-gray-300 rounded-xl p-3 focus:ring-[#235481] focus:border-[#235481]"
                                placeholder="example@email.com">
                        </div>
                    </div>

                    {{-- Password + Confirm --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="font-semibold">كلمة المرور</label>
                            <input type="password" name="password"
                                class="mt-2 w-full border-gray-300 rounded-xl p-3 focus:ring-[#235481] focus:border-[#235481]"
                                placeholder="أدخل كلمة المرور">
                        </div>

                        <div>
                            <label class="font-semibold">تأكيد كلمة المرور</label>
                            <input type="password" name="password_confirmation"
                                class="mt-2 w-full border-gray-300 rounded-xl p-3 focus:ring-[#235481] focus:border-[#235481]"
                                placeholder="أعد إدخال كلمة المرور">
                        </div>
                    </div>

                    {{-- City + Birthday --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="font-semibold">المدينة</label>
                            <select name="city_id" required>
                                
                                <option value="">اختر مدينة</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}">{{ $city->name }}</option>
                                @endforeach
                            </select>

                        </div>

                        <div>
                            <label class="font-semibold">تاريخ الميلاد</label>
                            <input type="date" name="birth_date"


                                class="mt-2 w-full border-gray-300 rounded-xl p-3 focus:ring-[#235481] focus:border-[#235481]">
                        </div>
                    </div>

                    {{-- Gender --}}
                    <div>
                        <label class="font-semibold block mb-2">الجنس</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="border rounded-xl p-3 text-center cursor-pointer hover:bg-gray-100">
                                <input type="radio" name="gender" value="ذكر"> ذكر
                            </label>

                            <label class="border rounded-xl p-3 text-center cursor-pointer hover:bg-gray-100">
                                <input type="radio" name="gender" value="أنثى"> أنثى
                            </label>
                        </div>
                    </div>
                    {{-- <input type="hidden" name="user_type" value="volunteer"> --}}


                    {{-- Submit --}}
                    <button class="w-full bg-[#235481] text-white p-3 rounded-xl text-lg hover:bg-[#235481] transition">
                        إنشاء حساب
                    </button>

                </form>
            </div>

        </div>
    </div>

</x-guest-layout>
