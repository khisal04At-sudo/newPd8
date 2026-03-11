<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تسجيل مؤسسة - أثيرا</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap');

        body {
            font-family: 'Cairo', sans-serif;
        }

        /* ── Shared background (same as guest layout) ── */
        .auth-background {
            background: linear-gradient(135deg, #f0fdf4 0%, #eff6ff 50%, #fdf2f8 100%);
            position: relative;
            overflow: hidden;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .abstract-shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            z-index: 0;
            opacity: 0.5;
            animation: float-slow 25s infinite alternate ease-in-out;
        }

        @keyframes float-slow {
            0%   { transform: translate(0, 0) scale(1); }
            100% { transform: translate(100px, 100px) scale(1.2); }
        }

        .shape-1 { width: 500px; height: 500px; background: #10b981; top: -10%;  left: -10%;  }
        .shape-2 { width: 400px; height: 400px; background: #3b82f6; bottom: -10%; right: -10%; }

        /* ── Card ── */
        .auth-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
            border-radius: 2.5rem;
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 820px;
        }

        /* ── Form internals ── */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.25rem;
        }
        .form-group { margin-bottom: 0; }
        .form-group.full-width { grid-column: span 2; }

        label {
            display: block;
            margin-bottom: 0.4rem;
            color: #475569;
            font-weight: 600;
            font-size: 0.88rem;
        }

        input, select {
            width: 100%;
            padding: 0.7rem 1rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 0.75rem;
            outline: none;
            font-family: inherit;
            font-size: 0.92rem;
            background: white;
            transition: all 0.2s;
            box-sizing: border-box;
        }
        input:focus, select:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.12);
        }

        .btn-submit {
            background: linear-gradient(135deg, #10b981 0%, #3b82f6 100%);
            color: white;
            padding: 1rem;
            border: none;
            border-radius: 0.875rem;
            width: 100%;
            font-size: 1.05rem;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            margin-top: 0.5rem;
            font-family: inherit;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
        }

        .error-list {
            background: #fee2e2;
            color: #b91c1c;
            padding: 1rem;
            border-radius: 0.75rem;
            margin-bottom: 1.25rem;
            font-size: 0.88rem;
        }

        .gradient-text {
            background: linear-gradient(135deg, #10b981 0%, #3b82f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        @media (max-width: 640px) {
            .form-grid { grid-template-columns: 1fr; }
            .form-group.full-width { grid-column: span 1; }
        }
    </style>
</head>
<body>
<div class="auth-background">
    <!-- Background shapes -->
    <div class="abstract-shape shape-1"></div>
    <div class="abstract-shape shape-2"></div>

    <div style="width: 100%; max-width: 820px; position: relative; z-index: 10;">

        <!-- Logo -->
        <div style="text-align: center; margin-bottom: 2rem;">
            <a href="{{ url('/') }}">
                <img src="{{ asset('assets/images/logo.png') }}" alt="أثيرا" style="height: 65px; width: auto; margin: 0 auto; object-fit: contain; display: block;">
            </a>
            <p style="color: #64748b; font-weight: 800; margin-top: 0.5rem; font-size: 0.88rem;">منصة التطوع والتدريب الرائدة في ليبيا</p>
        </div>

        <!-- Card -->
        <div class="auth-card" style="padding: 2.5rem;">

            <!-- Header -->
            <div style="text-align: center; margin-bottom: 2rem;">
                <div style="font-size: 2.2rem; margin-bottom: 0.5rem;">🏛️</div>
                <h2 class="gradient-text" style="font-size: 1.75rem; font-weight: 800; margin: 0 0 0.4rem;">إنشاء حساب مؤسسة</h2>
                <p style="color: #64748b; font-size: 0.92rem; margin: 0;">انضم إلى أثيرا وساهم في تمكين المجتمع</p>
            </div>

            @if ($errors->any())
                <div class="error-list">
                    <ul style="margin: 0; padding-right: 1.25rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.organization.store') }}" method="POST">
                @csrf
                <div class="form-grid">

                    <div class="form-group full-width">
                        <label for="name"><i class="fas fa-building" style="color: #10b981; margin-left: 0.35rem;"></i>اسم المؤسسة</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="أدخل اسم المؤسسة الرسمي">
                    </div>

                    <div class="form-group">
                        <label for="email"><i class="fas fa-envelope" style="color: #10b981; margin-left: 0.35rem;"></i>البريد الإلكتروني</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="example@org.ly">
                    </div>

                    <div class="form-group">
                        <label for="phone"><i class="fas fa-phone" style="color: #10b981; margin-left: 0.35rem;"></i>رقم الهاتف</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required placeholder="09XXXXXXXX">
                    </div>

                    <div class="form-group">
                        <label for="city_id"><i class="fas fa-map-marker-alt" style="color: #10b981; margin-left: 0.35rem;"></i>المدينة</label>
                        <select name="city_id" id="city_id" required>
                            <option value="">اختر المدينة</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="organization_type"><i class="fas fa-tags" style="color: #10b981; margin-left: 0.35rem;"></i>نوع المؤسسة</label>
                        <select name="organization_type" id="organization_type" required>
                            <option value="">اختر النوع</option>
                            <option value="volunteering" {{ old('organization_type') == 'volunteering' ? 'selected' : '' }}>تطوعية</option>
                            <option value="training"     {{ old('organization_type') == 'training'     ? 'selected' : '' }}>تدريبية</option>
                            <option value="both"         {{ old('organization_type') == 'both'         ? 'selected' : '' }}>كلاهما</option>
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label for="sector"><i class="fas fa-layer-group" style="color: #10b981; margin-left: 0.35rem;"></i>القطاع</label>
                        <select name="sector" id="sector" required>
                            <option value="">اختر القطاع</option>
                            <option value="private"    {{ old('sector') == 'private'    ? 'selected' : '' }}>قطاع خاص (شركة أو مؤسسة)</option>
                            <option value="public"     {{ old('sector') == 'public'     ? 'selected' : '' }}>قطاع عام (حكومي)</option>
                            <option value="initiative" {{ old('sector') == 'initiative' ? 'selected' : '' }}>مبادرات وفرق تطوعية (غير مسجلة)</option>
                            <option value="non_profit" {{ old('sector') == 'non_profit' ? 'selected' : '' }}>مؤسسة أو جمعية غير ربحية (مسجلة)</option>
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label for="registration_number"><i class="fas fa-id-card" style="color: #10b981; margin-left: 0.35rem;"></i>رقم التسجيل <span style="color: #94a3b8; font-weight: 400;">(اختياري)</span></label>
                        <input type="text" name="registration_number" id="registration_number" value="{{ old('registration_number') }}" placeholder="رقم القيد التجاري أو الإشهار">
                    </div>

                    <div class="form-group">
                        <label for="password"><i class="fas fa-lock" style="color: #10b981; margin-left: 0.35rem;"></i>كلمة المرور</label>
                        <input type="password" name="password" id="password" required placeholder="••••••••">
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation"><i class="fas fa-lock" style="color: #10b981; margin-left: 0.35rem;"></i>تأكيد كلمة المرور</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="••••••••">
                    </div>

                </div>

                <button type="submit" class="btn-submit" style="margin-top: 1.5rem;">
                    <i class="fas fa-building" style="margin-left: 0.5rem;"></i>
                    تسجيل الحساب
                </button>

                <p style="text-align: center; margin-top: 1.25rem; font-size: 0.9rem; color: #64748b;">
                    لديك حساب بالفعل؟
                    <a href="{{ route('login') }}" style="color: #10b981; font-weight: 700; text-decoration: none;">تسجيل الدخول</a>
                </p>
            </form>
        </div>

        <!-- Back link -->
        <div style="text-align: center; margin-top: 2rem;">
            <a href="{{ url('/') }}" style="color: #64748b; text-decoration: none; font-weight: 800; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.3s;"
               onmouseover="this.style.color='#10b981'; this.style.gap='0.75rem'"
               onmouseout="this.style.color='#64748b'; this.style.gap='0.5rem'">
                <i class="fas fa-arrow-right"></i>
                العودة للصفحة الرئيسية
            </a>
        </div>
    </div>
</div>
</body>
</html>
