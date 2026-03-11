<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة الإدارة - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --volunteer-green: #10b981;
            --volunteer-green-dark: #059669;
            --brand-blue: #3b82f6;
            --brand-blue-dark: #2563eb;
            --sidebar-width: 280px;
            --glass-bg: rgba(255, 255, 255, 0.9);
            --glass-border: rgba(255, 255, 255, 0.3);
            --bg-gradient: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }
        
        body {
            background: #f8fafc;
            font-family: 'Cairo', sans-serif;
            margin: 0;
            display: flex;
            color: #1e293b;
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: white;
            height: 100vh;
            position: fixed;
            right: 0;
            top: 0;
            overflow-y: auto;
            border-left: 1px solid #e2e8f0;
            z-index: 1000;
            box-shadow: -4px 0 15px rgba(0,0,0,0.02);
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            padding: 2.5rem 2rem;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--volunteer-green);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }

        .sidebar-brand i {
            font-size: 1.75rem;
            background: linear-gradient(135deg, var(--volunteer-green), var(--brand-blue));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .sidebar-menu {
            padding: 1rem;
            list-style: none;
            margin: 0;
            flex-grow: 1;
        }

        .sidebar-menu li {
            margin-bottom: 0.5rem;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.25rem;
            color: #64748b;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            gap: 1rem;
            border-radius: 0.75rem;
            font-weight: 600;
        }

        .sidebar-menu li a:hover {
            color: var(--volunteer-green);
            background: #f0fdf4;
            transform: translateX(-5px);
        }

        .sidebar-menu li a.active {
            color: white;
            background: linear-gradient(135deg, var(--volunteer-green), var(--volunteer-green-dark));
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
        }

        .sidebar-menu li a i {
            font-size: 1.25rem;
            width: 1.5rem;
            text-align: center;
        }

        .sidebar-footer {
            padding: 1.5rem;
            border-top: 1px solid #f1f5f9;
        }

        /* Main Content */
        .main-content {
            margin-right: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
            background: var(--bg-gradient);
        }

        /* Header */
        .admin-header {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            padding: 1rem 2.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }

        .header-title {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.5rem 1rem;
            background: white;
            border-radius: 99px;
            border: 1px solid #e2e8f0;
            transition: all 0.2s;
            cursor: pointer;
        }

        .user-profile:hover {
            border-color: var(--volunteer-green);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .content-body {
            padding: 2.5rem;
            animation: fadeIn 0.4s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Common Components */
        .card {
            background: white;
            border-radius: 1.25rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid #f1f5f9;
            transition: all 0.3s;
        }

        .card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .btn-logout {
            background: none;
            border: none;
            color: #ef4444;
            padding: 0.875rem 1.25rem;
            font-size: 1rem;
            font-family: inherit;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 1rem;
            width: 100%;
            text-align: right;
            border-radius: 0.75rem;
            transition: all 0.2s;
            font-weight: 600;
        }

        .btn-logout:hover {
            background: #fef2f2;
            color: #dc2626;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('assets/images/logo.jpg') }}" alt="أثيرا" style="height: 40px; width: auto; object-fit: contain;">
            <span style="font-size: 1.25rem;">- الإدارة</span>
        </div>
        <ul class="sidebar-menu">
            <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="fas fa-grid-2"></i> الرئيسية</a></li>
            <li><a href="#"><i class="fas fa-users"></i> المتطوعين</a></li>
            <li><a href="{{ route('admin.organizations.index') }}" class="{{ request()->routeIs('admin.organizations.*') ? 'active' : '' }}"><i class="fas fa-building"></i> المؤسسات</a></li>
            <li><a href="{{ route('admin.opportunities.index') }}" class="{{ request()->routeIs('admin.opportunities.*') ? 'active' : '' }}"><i class="fas fa-clipboard-check"></i> مراجعة الفرص</a></li>
            <li><a href="#"><i class="fas fa-briefcase"></i> الفرص المنشورة</a></li>
            <li><a href="#"><i class="fas fa-award"></i> الشهادات</a></li>
        </ul>
        <div class="sidebar-footer">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
                </button>
            </form>
        </div>
    </div>

    <div class="main-content">
        <header class="admin-header">
            <h2 class="header-title">@yield('header')</h2>
            <div class="user-profile">
                <div style="text-align: left;">
                    <div style="font-weight: 700; font-size: 0.9rem; color: #1e293b;">{{ auth()->user()->name }}</div>
                    <div style="font-size: 0.75rem; color: #64748b;">مدير النظام</div>
                </div>
                <div style="width: 40px; height: 40px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <i class="fas fa-user-shield" style="font-size: 1.25rem; color: var(--volunteer-green);"></i>
                </div>
            </div>
        </header>

        <div class="content-body">
            @yield('content')
        </div>
    </div>
</body>
</html>
