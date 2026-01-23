<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>اختر نوع الحساب - أثيرا</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .choice-card {
            background: white;
            border-radius: 1.5rem;
            padding: 3rem 2rem;
            text-align: center;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 3px solid transparent;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }
        
        .choice-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.05) 0%, rgba(34, 197, 94, 0.05) 100%);
            opacity: 0;
            transition: opacity 0.4s ease;
            z-index: 0;
            pointer-events: none;
        }
        
        .choice-card:hover::before {
            opacity: 1;
        }
        
        .choice-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }
        
        .volunteer-card:hover {
            border-color: #6366f1;
        }
        
        .organization-card:hover {
            border-color: #22c55e;
        }
        
        /* Ensure all card content is above the ::before overlay */
        .card-content {
            position: relative;
            z-index: 1;
        }
        
        .icon-circle {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 3rem;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }
        
        .volunteer-card .icon-circle {
            background: linear-gradient(135deg, #818cf8 0%, #6366f1 100%);
            color: white;
        }
        
        .organization-card .icon-circle {
            background: linear-gradient(135deg, #4ade80 0%, #22c55e 100%);
            color: white;
        }
        
        .choice-card:hover .icon-circle {
            transform: rotate(10deg) scale(1.1);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-brand-50 via-white to-volunteer-50 min-h-screen">
    <div class="container max-w-6xl mx-auto px-4 py-16">
        <!-- Header -->
        <div class="text-center mb-16 animate-fade-in">
            <a href="{{ url('/') }}" class="inline-block mb-6">
                <div class="gradient-text text-5xl font-black flex items-center justify-center gap-3">
                    <i class="fas fa-feather-alt"></i>
                    <span>أثيرا</span>
                </div>
            </a>
            <h1 class="text-4xl md:text-5xl font-black text-gray-800 mb-4">
                انضم إلى مجتمعنا
            </h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                اختر نوع الحساب المناسب لك وابدأ رحلتك في التطوع والتدريب
            </p>
        </div>

        <!-- Choice Cards -->
        <div class="grid md:grid-cols-2 gap-8 max-w-5xl mx-auto mb-12">
            <!-- Volunteer Card -->
            <div class="choice-card volunteer-card animate-scale-in">
                <div class="card-content">
                    <div class="icon-circle">
                        <i class="fas fa-hands-helping"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-800 mb-3">متطوع</h2>
                    <p class="text-gray-600 mb-6 leading-relaxed">
                        انضم كمتطوع لاكتشاف فرص التطوع والتدريب، ساهم في خدمة المجتمع واكتسب مهارات جديدة
                    </p>
                    <ul class="text-right mb-8 space-y-3">
                        <li class="flex items-start gap-3 text-gray-700">
                            <i class="fas fa-check-circle text-brand-500 mt-1"></i>
                            <span>تصفح آلاف الفرص التطوعية والتدريبية</span>
                        </li>
                        <li class="flex items-start gap-3 text-gray-700">
                            <i class="fas fa-check-circle text-brand-500 mt-1"></i>
                            <span>احصل على شهادات معتمدة</span>
                        </li>
                        <li class="flex items-start gap-3 text-gray-700">
                            <i class="fas fa-check-circle text-brand-500 mt-1"></i>
                            <span>بناء سيرة ذاتية قوية</span>
                        </li>
                    </ul>
                    <a href="{{ route('register.volunteer') }}" class="btn-brand inline-block w-full" style="position: relative; z-index: 2;">
                        <i class="fas fa-user-plus ml-2"></i>
                        التسجيل كمتطوع
                    </a>
                </div>
            </div>

            <!-- Organization Card -->
            <div class="choice-card organization-card animate-scale-in" style="animation-delay: 0.1s;">
                <div class="card-content">
                    <div class="icon-circle">
                        <i class="fas fa-building"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-800 mb-3">مؤسسة</h2>
                    <p class="text-gray-600 mb-6 leading-relaxed">
                        انضم كمؤسسة لنشر فرص التطوع والتدريب، وابحث عن المتطوعين المناسبين لمؤسستك
                    </p>
                    <ul class="text-right mb-8 space-y-3">
                        <li class="flex items-start gap-3 text-gray-700">
                            <i class="fas fa-check-circle text-volunteer-500 mt-1"></i>
                            <span>نشر فرص التطوع والتدريب</span>
                        </li>
                        <li class="flex items-start gap-3 text-gray-700">
                            <i class="fas fa-check-circle text-volunteer-500 mt-1"></i>
                            <span>إدارة المتطوعين بسهولة</span>
                        </li>
                        <li class="flex items-start gap-3 text-gray-700">
                            <i class="fas fa-check-circle text-volunteer-500 mt-1"></i>
                            <span>إصدار شهادات للمتطوعين</span>
                        </li>
                    </ul>
                    <a href="{{ route('register.organization') }}" class="btn-volunteer inline-block w-full" style="position: relative; z-index: 2;">
                        <i class="fas fa-building ml-2"></i>
                        التسجيل كمؤسسة
                    </a>
                </div>
            </div>
        </div>

        <!-- Already have account -->
        <div class="text-center animate-fade-in" style="animation-delay: 0.2s;">
            <p class="text-gray-700 text-lg mb-4">
                لديك حساب بالفعل؟
            </p>
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-brand-600 hover:text-brand-700 font-bold text-lg transition-colors">
                <i class="fas fa-sign-in-alt"></i>
                <span>تسجيل الدخول</span>
            </a>
        </div>

        <!-- Back to home -->
        <div class="text-center mt-8">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-800 transition-colors">
                <i class="fas fa-arrow-right"></i>
                <span>العودة للصفحة الرئيسية</span>
            </a>
        </div>
    </div>
</body>
</html>
