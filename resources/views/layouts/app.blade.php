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
        :root {
            --sidebar-width: 260px;
        }
        body {
            background-color: #f8fafc;
            color: #1e293b;
            margin: 0;
            padding-top: 80px; /* Space for fixed header */
        }
        .main-content {
            transition: all 0.3s ease;
            width: 100%;
        }
        .main-content.shifted {
            margin-right: var(--sidebar-width) !important;
            width: calc(100% - var(--sidebar-width)) !important;
        }
        .main-content.expanded {
            margin-right: 0 !important;
            width: 100% !important;
        }
        @media (max-width: 768px) {
            .main-content.shifted {
                margin-right: 0 !important;
                width: 100% !important;
            }
        }
    </style>
</head>
<body class="antialiased">
    
    @auth
        @if(auth()->user()->user_type === 'organization')
            @include('layouts.partials.organization-sidebar')
        @else
            @include('layouts.partials.sidebar')
        @endif
    @endauth

    <div class="main-content {{ (request()->is('dashboard*') || request()->is('volunteer*') || request()->is('organization/*') || request()->is('admin*')) ? 'shifted' : 'expanded' }}" id="mainContent">
        <!-- Shared Navigation -->
        @if(!request()->is('login') && !request()->is('register') && !request()->is('choose-account-type'))
            @include('layouts.partials.public-navbar')
        @endif

    <main>
        @yield('content')
    </main>

    <footer style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: #94a3b8; padding: 5rem 2rem; text-align: center; margin-top: 6rem; border-top: 1px solid rgba(148, 163, 184, 0.1);">
        <div style="max-width: 1200px; margin: 0 auto;">
            <div style="margin-bottom: 2rem;">
                <img src="{{ asset('assets/images/logo.png') }}" alt="أثيرا" style="height: 60px; width: auto; object-fit: contain; margin-bottom: 1rem;">
                <p style="color: #cbd5e1; max-width: 600px; margin: 0 auto;">
                    منصة ليبية رائدة لتمكين المتطوعين وربطهم بالفرص التنموية والمهنية
                </p>
            </div>
            <p style="border-top: 1px solid rgba(148, 163, 184, 0.1); padding-top: 2rem; color: #94a3b8;">
                &copy; 2026 جميع الحقوق محفوظة لمنصة أثيرا التطوعية
            </p>
        </div>
    </footer>

    @include('layouts.partials.photo-modal')
</body>
</html>
