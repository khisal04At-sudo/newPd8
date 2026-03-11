<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - @yield('title', 'الرئيسية')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --sidebar-width: 260px;
            --header-height: 80px;
            --primary-color: #3b82f6;
            --bg-color: {{ auth()->user()->user_type === 'user' ? '#eff6ff' : '#f1faed' }};
        }
        body {
            background-color: var(--bg-color);
            background-color: var(--bg-color) !important;
            font-family: 'Cairo', sans-serif;
            margin: 0;
            transition: background-color 0.3s ease;
        }
        .main-content {
            padding: 20px;
            min-height: 100vh;
            transition: all 0.3s ease;
            width: 100%;
            padding-top: calc(var(--header-height) + 20px); /* Adjust for fixed header */
            background-color: var(--bg-color);
        }
        .main-content.shifted {
            margin-right: var(--sidebar-width) !important;
            width: calc(100% - var(--sidebar-width)) !important;
        }
        .main-content.expanded {
            margin-right: 0 !important;
            width: 100% !important;
        }
        .header {
            height: var(--header-height);
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 40px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(226, 232, 240, 0.5);
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
            :root {
                --header-height: 64px;
            }
            .main-content {
                margin-right: 0;
                padding-top: calc(var(--header-height) + 15px);
                padding-left: 15px;
                padding-right: 15px;
            }
            .header {
                padding: 0 20px;
            }
            .welcome-msg {
                font-size: 14px;
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
                <a href="{{ url('/') }}" style="display: flex; align-items: center; padding: 0 10px; border-left: 2px solid #e2e8f0; margin-left: 5px;">
                    <img src="{{ asset('assets/images/logo.jpg') }}" alt="أثيرا" style="height: 45px; width: auto; object-fit: contain;">
                </a>
                <div class="welcome-msg">
                    أهلاً بك، <strong>{{ auth()->user()->name }}</strong>
                </div>
            </div>
            <div class="user-nav">
                @if(auth()->user()->user_type === 'user')
                    <div class="points" style="color: #4f46e5; font-weight: bold;">
                        <i class="fas fa-coins"></i> {{ auth()->user()->points }} نقطة
                    </div>
                @endif
                <div style="position: relative; cursor: pointer;" onclick="openPhotoModal('{{ auth()->user()->avatar_url }}', '{{ auth()->user()->name }}', true)">
                    <img src="{{ auth()->user()->avatar_url }}" class="avatar-sm" style="border: 2px solid white; box-shadow: 0 4px 10px rgba(0,0,0,0.1); transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                </div>
            </div>
        </div>

        @if(session('success'))
            <div style="background: #f0fdf4; color: #16a34a; padding: 1rem; border-radius: 0.75rem; border: 1px solid #bbf7d0; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background: #fef2f2; color: #dc2626; padding: 1rem; border-radius: 0.75rem; border: 1px solid #fecaca; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background: #fffbeb; color: #92400e; padding: 1rem; border-radius: 0.75rem; border: 1px solid #fef3c7; margin-bottom: 1.5rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem; font-weight: 800;">
                    <i class="fas fa-exclamation-triangle"></i>
                    يرجى تصحيح الأخطاء التالية:
                </div>
                <ul style="margin: 0; padding-right: 1.5rem; font-size: 0.9rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>

    @include('layouts.partials.photo-modal')
</body>
</html>
