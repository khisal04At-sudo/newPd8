<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - أثيرا</title>

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background-color: #f8fafc;
            color: #1e293b;
            margin: 0;
            padding-top: 80px; /* Space for fixed header */
        }
        .navbar-public {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 1.25rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(226, 232, 240, 0.5);
        }
        .nav-links-public {
            display: flex;
            gap: 2.5rem;
        }
        .nav-links-public a {
            text-decoration: none;
            color: #64748b;
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
        }
        .nav-links-public a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, #10b981, #3b82f6);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }
        .nav-links-public a:hover { 
            color: #3b82f6; 
        }
        .nav-links-public a:hover::after {
            transform: scaleX(1);
        }
        .btn-auth {
            padding: 0.75rem 1.75rem;
            border-radius: 999px;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.3s ease;
        }
        .logo-link {
            text-decoration: none;
            font-size: 1.75rem;
            font-weight: 900;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: linear-gradient(135deg, #10b981 0%, #2563eb 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            transition: transform 0.3s ease;
        }
        .logo-link:hover {
            transform: scale(1.05);
        }
    </style>

</head>
<body class="antialiased">
    
    <!-- simple public navbar if not on landing -->
    @if(!request()->is('/'))
    <nav class="navbar-public">
        <a href="{{ url('/') }}" class="logo-link">
            <i class="fas fa-feather-alt"></i> أثيرا
        </a>
        <div class="nav-links-public">
            <a href="{{ route('opportunities.index') }}">تصفح الفرص</a>
            <a href="#">عن المنصة</a>
            <a href="#">الشركاء</a>
        </div>
        <div>
            @auth
                <a href="{{ route('dashboard') }}" class="btn-auth btn-brand">
                    <i class="fas fa-th-large ml-2"></i>
                    لوحة التحكم
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-auth" style="color: #64748b; border: 2px solid #e2e8f0; background: white;">دخول</a>
                <a href="{{ route('choose.account.type') }}" class="btn-auth btn-brand">انضمام</a>
            @endauth
        </div>
    </nav>
    @endif

    <main>
        @yield('content')
    </main>

    <footer style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: #94a3b8; padding: 5rem 2rem; text-align: center; margin-top: 6rem; border-top: 1px solid rgba(148, 163, 184, 0.1);">
        <div style="max-width: 1200px; margin: 0 auto;">
            <div style="margin-bottom: 2rem;">
                <div style="font-size: 2rem; font-weight: 900; background: linear-gradient(135deg, #22c55e 0%, #6366f1 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin-bottom: 1rem;">
                    <i class="fas fa-feather-alt"></i> أثيرا
                </div>
                <p style="color: #cbd5e1; max-width: 600px; margin: 0 auto;">
                    منصة ليبية رائدة لتمكين المتطوعين وربطهم بالفرص التنموية والمهنية
                </p>
            </div>
            <p style="border-top: 1px solid rgba(148, 163, 184, 0.1); padding-top: 2rem; color: #94a3b8;">
                &copy; 2026 جميع الحقوق محفوظة لمنصة أثيرا التطوعية
            </p>
        </div>
    </footer>
</body>
</html>
