<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - @yield('title', 'الرئيسية')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --sidebar-width: 260px;
            --header-height: 70px;
            --primary-color: #4f46e5;
            --bg-color: #f8fafc;
        }
        body {
            background-color: var(--bg-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
        }
        .main-content {
            padding: 20px;
            min-height: 100vh;
            transition: all 0.3s ease;
            width: 100%;
        }
        .main-content.shifted {
            margin-right: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
        }
        .header {
            height: var(--header-height);
            background: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            border-radius: 10px;
        }
        .user-nav {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .avatar-sm {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        .card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .main-content.expanded {
            margin-right: 0;
        }
        .toggle-sidebar {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #475569;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        @media (max-width: 768px) {
            .main-content {
                margin-right: 0;
            }
        }
    </style>
</head>
<body>
@if(auth()->check() && auth()->user()->user_type === 'organization')
    @include('layouts.partials.organization-sidebar')
@else
    @include('layouts.partials.sidebar')
@endif

    <div class="main-content shifted" id="mainContent">
        <div class="header">
            <div style="display: flex; align-items: center; gap: 15px;">
                <button class="toggle-sidebar" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="welcome-msg">
                    أهلاً بك، <strong>{{ auth()->user()->name }}</strong>
                </div>
            </div>
            <div class="user-nav">
                @if(auth()->user()->user_type === 'volunteer')
                    <div class="points" style="color: #4f46e5; font-weight: bold;">
                        <i class="fas fa-coins"></i> {{ auth()->user()->points }} نقطة
                    </div>
                @endif
                <img src="{{ auth()->user()->avatar_url }}" class="avatar-sm">
            </div>
        </div>

        @if(session('success'))
            <div style="background: #efe; color: #16a34a; padding: 15px; border-radius: 10px; border: 1px solid #bbf7d0; margin-bottom: 20px;">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>
