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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            margin: 0;
        }
        .navbar-public {
            background: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .nav-links-public {
            display: flex;
            gap: 2rem;
        }
        .nav-links-public a {
            text-decoration: none;
            color: #64748b;
            font-weight: 600;
            transition: color 0.2s;
        }
        .nav-links-public a:hover { color: #4f46e5; }
        .btn-auth {
            padding: 0.6rem 1.5rem;
            border-radius: 99px;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.2s;
        }
    </style>
</head>
<body class="antialiased">
    
    <!-- simple public navbar if not on landing -->
    @if(!request()->is('/'))
    <nav class="navbar-public">
        <a href="{{ url('/') }}" style="text-decoration: none; color: #4f46e5; font-size: 1.5rem; font-weight: 800; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-feather-alt"></i> أثيرا
        </a>
        <div class="nav-links-public">
            <a href="{{ route('opportunities.index') }}">تصفح الفرص</a>
            <a href="#">عن المنصة</a>
            <a href="#">الشركاء</a>
        </div>
        <div>
            @auth
                <a href="{{ route('dashboard') }}" class="btn-auth" style="background: #f1f5f9; color: #475569;">لوحة التحكم</a>
            @else
                <a href="{{ route('login') }}" class="btn-auth" style="color: #64748b;">دخول</a>
                <a href="{{ route('choose.account.type') }}" class="btn-auth" style="background: #4f46e5; color: white;">انضمام</a>
            @endauth
        </div>
    </nav>
    @endif

    <main>
        @yield('content')
    </main>

    <footer style="background: #0f172a; color: #94a3b8; padding: 4rem 2rem; text-align: center; margin-top: 5rem;">
        <p>&copy; 2026 جميع الحقوق محفوظة لمنصة أثيرا التطوعية</p>
    </footer>
</body>
</html>
