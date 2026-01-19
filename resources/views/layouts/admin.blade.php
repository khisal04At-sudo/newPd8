<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة الإدارة - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --sidebar-width: 260px;
            --primary: #1e293b;
            --accent: #4f46e5;
        }
        body {
            background: #f8fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            display: flex;
        }
        .sidebar {
            width: var(--sidebar-width);
            background: var(--primary);
            color: white;
            height: 100vh;
            position: fixed;
            right: 0;
            top: 0;
            overflow-y: auto;
        }
        .sidebar-brand {
            padding: 2rem;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 800;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-menu {
            padding: 1rem 0;
            list-style: none;
            margin: 0;
        }
        .sidebar-menu li a {
            display: flex;
            align-items: center;
            padding: 1rem 2rem;
            color: #94a3b8;
            text-decoration: none;
            transition: all 0.2s;
            gap: 1rem;
        }
        .sidebar-menu li a:hover, .sidebar-menu li a.active {
            color: white;
            background: rgba(255,255,255,0.05);
            border-right: 4px solid var(--accent);
        }
        .main-content {
            margin-right: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
        }
        .admin-header {
            background: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .content-body {
            padding: 2rem;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-feather-alt"></i> أثيرا - الإدارة
        </div>
        <ul class="sidebar-menu">
            <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="fas fa-chart-pie"></i> الرئيسية</a></li>
            <li><a href="#"><i class="fas fa-users"></i> المتطوعين</a></li>
            <li><a href="{{ route('admin.organizations.index') }}" class="{{ request()->routeIs('admin.organizations.*') ? 'active' : '' }}"><i class="fas fa-building"></i> المؤسسات</a></li>
            <li><a href="{{ route('admin.opportunities.index') }}" class="{{ request()->routeIs('admin.opportunities.*') ? 'active' : '' }}"><i class="fas fa-clipboard-check"></i> مراجعة الفرص</a></li>
            <li><a href="#"><i class="fas fa-briefcase"></i> الفرص المنشورة</a></li>
            <li><a href="#"><i class="fas fa-award"></i> الشهادات</a></li>
            <li style="margin-top: 2rem; border-top: 1px solid rgba(255,255,255,0.1);">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" style="background: none; border: none; color: #f87171; padding: 1rem 2rem; font-size: 1rem; font-family: inherit; cursor: pointer; display: flex; align-items: center; gap: 1rem; width: 100%; text-align: right;">
                        <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
                    </button>
                </form>
            </li>
        </ul>
    </div>

    <div class="main-content">
        <header class="admin-header">
            <h2 style="margin: 0; font-size: 1.25rem;">@yield('header')</h2>
            <div style="display: flex; align-items: center; gap: 1rem;">
                <span style="color: #64748b;">مرحباً، {{ auth()->user()->name }}</span>
                <i class="fas fa-user-circle" style="font-size: 1.5rem; color: var(--primary);"></i>
            </div>
        </header>

        <div class="content-body">
            @yield('content')
        </div>
    </div>
</body>
</html>
