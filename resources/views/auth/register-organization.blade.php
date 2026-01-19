<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل مؤسسة - أثيرا</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --bg-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        body {
            background: #f8fafc;
            font-family: 'Inter', 'Noto Sans Arabic', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            margin: 0;
        }
        .register-container {
            background: white;
            border-radius: 1.5rem;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 800px;
            overflow: hidden;
            display: flex;
        }
        .info-side {
            background: var(--bg-gradient);
            color: white;
            padding: 3rem;
            width: 35%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
        }
        .form-side {
            padding: 3rem;
            width: 65%;
        }
        .form-header {
            margin-bottom: 2rem;
        }
        .form-header h2 {
            font-size: 1.8rem;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        .form-group {
            margin-bottom: 1.25rem;
        }
        .form-group.full-width {
            grid-column: span 2;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #475569;
            font-weight: 600;
            font-size: 0.9rem;
        }
        input, select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            outline: none;
            transition: all 0.2s;
        }
        input:focus, select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
        .btn-submit {
            background: var(--primary);
            color: white;
            padding: 1rem;
            border: none;
            border-radius: 0.75rem;
            width: 100%;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 1rem;
        }
        .btn-submit:hover {
            background: var(--primary-dark);
        }
        .error-list {
            background: #fee2e2;
            color: #b91c1c;
            padding: 1rem;
            border-radius: 0.75rem;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }
        @media (max-width: 768px) {
            .register-container {
                flex-direction: column;
            }
            .info-side {
                width: 100%;
                padding: 2rem;
            }
            .form-side {
                width: 100%;
                padding: 2rem;
            }
            .form-grid {
                grid-template-columns: 1fr;
            }
            .form-group.full-width {
                grid-column: span 1;
            }
        }
    </style>
</head>
<body>

    <div class="register-container">
        <div class="info-side">
            <i class="fas fa-building" style="font-size: 4rem; margin-bottom: 2rem;"></i>
            <h3>بوابة المؤسسات</h3>
            <p>انضم إلى أثيرا وساهم في تمكين المجتمع من خلال توفير فرص تطوعية وتدريبية متميزة.</p>
        </div>
        
        <div class="form-side">
            <div class="form-header">
                <h2>إنشاء حساب مؤسسة</h2>
                <p style="color: #64748b;">املأ البيانات التالية لتبدأ رحلتك معنا</p>
            </div>

            @if ($errors->any())
                <div class="error-list">
                    <ul style="margin: 0; padding-right: 1.5rem;">
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
                        <label for="name">اسم المؤسسة</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="أدخل اسم المؤسسة الرسمي">
                    </div>

                    <div class="form-group">
                        <label for="email">البريد الإلكتروني</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="example@org.ly">
                    </div>

                    <div class="form-group">
                        <label for="phone">رقم الهاتف</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required placeholder="09XXXXXXXX">
                    </div>

                    <div class="form-group">
                        <label for="city_id">المدينة</label>
                        <select name="city_id" id="city_id" required>
                            <option value="">اختر المدينة</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="organization_type">نوع المؤسسة</label>
                        <select name="organization_type" id="organization_type" required>
                            <option value="">اختر النوع</option>
                            <option value="volunteering" {{ old('organization_type') == 'volunteering' ? 'selected' : '' }}>تطوعية</option>
                            <option value="training" {{ old('organization_type') == 'training' ? 'selected' : '' }}>تدريبية</option>
                            <option value="both" {{ old('organization_type') == 'both' ? 'selected' : '' }}>كلاهما</option>
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label for="sector">القطاع</label>
                        <select name="sector" id="sector" required>
                            <option value="">اختر القطاع</option>
                            <option value="private" {{ old('sector') == 'private' ? 'selected' : '' }}>قطاع خاص (شركة أو مؤسسة)</option>
                            <option value="public" {{ old('sector') == 'public' ? 'selected' : '' }}>قطاع عام (حكومي)</option>
                            <option value="initiative" {{ old('sector') == 'initiative' ? 'selected' : '' }}>مبادرات وفرق تطوعية (غير مسجلة)</option>
                            <option value="non_profit" {{ old('sector') == 'non_profit' ? 'selected' : '' }}>مؤسسة أو جمعية غير ربحية (مسجلة)</option>
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label for="registration_number">رقم التسجيل (إن وجد)</label>
                        <input type="text" name="registration_number" id="registration_number" value="{{ old('registration_number') }}" placeholder="رقم القيد التجاري أو الإشهار">
                    </div>

                    <div class="form-group">
                        <label for="password">كلمة المرور</label>
                        <input type="password" name="password" id="password" required placeholder="••••••••">
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">تأكيد كلمة المرور</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" class="btn-submit">تسجيل الحساب</button>

                <p style="text-align: center; margin-top: 1.5rem; font-size: 0.9rem; color: #64748b;">
                    لديك حساب بالفعل؟ <a href="{{ route('login') }}" style="color: var(--primary); font-weight: 600; text-decoration: none;">تسجيل الدخول</a>
                </p>
            </form>
        </div>
    </div>

</body>
</html>
