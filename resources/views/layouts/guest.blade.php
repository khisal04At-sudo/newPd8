<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'أثيرا') }}</title>

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap');

            body {
                font-family: 'Cairo', sans-serif;
            }

            /* Modern Animated Background */
            .auth-background {
                background: linear-gradient(135deg, #f0fdf4 0%, #eff6ff 50%, #fdf2f8 100%);
                position: relative;
                overflow: hidden;
            }

            /* Subtle Animated Abstract Shapes */
            .abstract-shape {
                position: absolute;
                border-radius: 50%;
                filter: blur(80px);
                z-index: 0;
                opacity: 0.5;
                animation: float-slow 25s infinite alternate ease-in-out;
            }

            @keyframes float-slow {
                0% { transform: translate(0, 0) scale(1); }
                100% { transform: translate(100px, 100px) scale(1.2); }
            }

            .shape-1 { width: 500px; height: 500px; background: #10b981; top: -10%; left: -10%; }
            .shape-2 { width: 400px; height: 400px; background: #3b82f6; bottom: -10%; right: -10%; }

            /* Premium Glassmorphism Card */
            .auth-card {
                background: rgba(255, 255, 255, 0.85);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.4);
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
                border-radius: 2.5rem;
                position: relative;
                z-index: 10;
            }

            .logo-container {
                text-align: center;
                margin-bottom: 2.5rem;
                position: relative;
                z-index: 10;
            }

            .logo-text {
                font-size: 2.75rem;
                font-weight: 950;
                background: linear-gradient(135deg, #10b981 0%, #3b82f6 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.75rem;
            }
        </style>
    </head>
    <body class="antialiased font-sans">
        <div class="auth-background min-h-screen flex flex-col justify-center items-center p-6">
            <!-- Shapes -->
            <div class="abstract-shape shape-1"></div>
            <div class="abstract-shape shape-2"></div>
            
            <div class="w-full max-w-md">
                <!-- Logo Zone -->
                <div class="logo-container">
                    <a href="{{ url('/') }}" class="logo-text">
                        <i class="fas fa-feather-alt" style="background: linear-gradient(135deg, #10b981 0%, #3b82f6 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                        أثيرا
                    </a>
                    <p style="color: #64748b; font-weight: 800; margin-top: 0.5rem; font-size: 0.9rem;">
                        منصة التطوع والتدريب الرائدة في ليبيا
                    </p>
                </div>
                
                <!-- The Modern Card -->
                <div class="auth-card p-10">
                    {{ $slot }}
                </div>

                <!-- Footer Back Link -->
                <div class="mt-10 text-center">
                    <a href="{{ url('/') }}" style="color: #64748b; text-decoration: none; font-weight: 800; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.3s;" onmouseover="this.style.color='#10b981'; this.style.gap='0.75rem'" onmouseout="this.style.color='#64748b'; this.style.gap='0.5rem'">
                        <i class="fas fa-arrow-right"></i>
                        العودة للصفحة الرئيسية
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>
