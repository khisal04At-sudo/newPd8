<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>أثيرا - منصة التطوع والتدريب</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-light: #818cf8;
            --secondary: #7c3aed;
            --accent: #f43f5e;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --bg-light: #f8fafc;
        }

        body {
            background: var(--bg-light);
            margin: 0;
            overflow-x: hidden;
            color: var(--text-main);
        }

        /* --- Header --- */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 1rem 2rem;
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
            transition: all 0.3s ease;
        }

        .logo-area {
            display: flex;
            align-items: center;
            gap: 1rem;
            text-decoration: none;
            color: var(--primary);
            font-size: 1.5rem;
            font-weight: 800;
        }
        .logo-area i { font-size: 1.8rem; }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 2rem;
        }
        .nav-links a {
            text-decoration: none;
            color: var(--text-muted);
            font-weight: 500;
            transition: color 0.2s;
        }
        .nav-links a:hover { color: var(--primary); }

        .search-bar {
            background: #f1f5f9;
            border-radius: 999px;
            padding: 0.5rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            width: 300px;
        }
        .search-bar input {
            background: none;
            border: none;
            outline: none;
            width: 100%;
            font-size: 0.9rem;
        }

        .auth-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .btn {
            padding: 0.6rem 1.5rem;
            border-radius: 999px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s;
            cursor: pointer;
            border: none;
        }
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.35);
        }
        .btn-primary:hover { 
            transform: translateY(-3px) scale(1.02); 
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.5); 
        }
        .btn-outline {
            background: white;
            border: 2px solid #e2e8f0;
            color: var(--text-muted);
        }
        .btn-outline:hover { border-color: var(--primary); color: var(--primary); }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
        }
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary-light);
        }

        /* --- Hero Section --- */
        .hero {
            padding: 14rem 2rem 10rem;
            position: relative;
            background: #0f172a; /* Fallback background */
            text-align: center;
            overflow: hidden;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 80vh;
        }
        
        .hero-bg-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        .hero-bg-media {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(0.8);
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(30, 27, 75, 0.7) 0%, rgba(15, 23, 42, 0.5) 100%);
            z-index: 1;
        }
        
        .hero-content {
            max-width: 900px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
        }

        .hero h1 {
            font-size: 4.5rem;
            font-weight: 900;
            color: white;
            margin-bottom: 1.5rem;
            line-height: 1.1;
            animation: slideUp 0.8s ease-out;
            text-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }

        .hero p {
            font-size: 1.5rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 3rem;
            line-height: 1.6;
            text-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        /* --- Stats Section --- */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: -5rem auto 5rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 3.5rem;
            border-radius: 2rem;
            box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.2),
                        0 0 0 1px rgba(255, 255, 255, 0.5);
            position: relative;
            z-index: 10;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .stat-item {
            text-align: center;
            transition: transform 0.3s ease;
        }
        
        .stat-item:hover {
            transform: translateY(-5px);
        }
        
        .stat-item h3 {
            font-size: 3rem;
            background: linear-gradient(135deg, #10b981 0%, #3b82f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0;
            font-weight: 900;
        }
        .stat-item p {
            color: var(--text-muted);
            font-weight: 600;
            margin: 0.5rem 0 0;
        }

        /* --- Carousel Section --- */
        .carousel-container {
            padding: 4rem 0;
            background: white;
            overflow: hidden;
            width: 100%;
        }
        .carousel-title {
            text-align: center;
            margin-bottom: 3rem;
        }
        .carousel-track {
            display: flex;
            gap: 2rem;
            animation: scroll 40s linear infinite;
            width: max-content;
        }
        .carousel-track:hover { animation-play-state: paused; }

        .opp-card {
            width: 320px;
            background: white;
            border-radius: 1.5rem;
            padding: 1.5rem;
            border: 1px solid #f1f5f9;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            flex-shrink: 0;
            position: relative;
            overflow: hidden;
        }
        
        .opp-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #10b981 0%, #3b82f6 100%);
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }
        
        .opp-card:hover::before {
            transform: scaleX(1);
        }
        
        .opp-card:hover { 
            transform: translateY(-12px); 
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
            border-color: rgba(99, 102, 241, 0.3); 
        }
        .opp-card .badge {
            background: #dbeafe;
            color: var(--primary);
            padding: 0.25rem 0.75rem;
            border-radius: 99px;
            font-size: 0.8rem;
            font-weight: bold;
        }
        .opp-card h4 { font-size: 1.25rem; margin: 1rem 0 0.5rem; }
        .opp-card p { color: var(--text-muted); font-size: 0.9rem; line-height: 1.4; height: 3.8rem; overflow: hidden; }
        .opp-footer { display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem; font-size: 0.85rem; }

        @keyframes scroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(calc(-320px * 5 - 2rem * 5)); }
        }

        /* --- Footer --- */
        footer {
            background: #0f172a;
            color: #94a3b8;
            padding: 5rem 2rem 2rem;
            margin-top: 4rem;
        }
        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 4rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        .footer-col h4 { color: white; margin-bottom: 1.5rem; }
        .footer-col ul { list-style: none; padding: 0; }
        .footer-col ul li { margin-bottom: 0.75rem; }
        .footer-col ul li a { color: #94a3b8; text-decoration: none; transition: color 0.2s; }
        .footer-col ul li a:hover { color: white; }
        
        .footer-bottom {
            border-top: 1px solid #334155;
            margin-top: 4rem;
            padding-top: 2rem;
            text-align: center;
        }

        /* Sidebar Overlay Logic */
        .main-content { 
            transition: all 0.3s ease; 
            width: 100%;
            position: relative;
        }
        .main-content.shifted { 
            margin-right: 260px; 
            width: calc(100% - 260px);
        }
        @media (max-width: 768px) { 
            .main-content.shifted { 
                margin-right: 0; 
                width: 100%;
            } 
        }
    </style>
</head>
<body>
@auth
    @if(auth()->user()->user_type === 'organization')
        @include('layouts.partials.organization-sidebar')
    @else
        @include('layouts.partials.sidebar')
    @endif
@endauth

    <div class="main-content" id="mainContent">
        <nav class="navbar">
            <div style="display: flex; align-items: center; gap: 1.5rem;">
                @auth
                    <button class="toggle-sidebar" onclick="toggleSidebar()" style="background:none; border:none; font-size:1.5rem; color:var(--primary); cursor:pointer; display: flex; align-items: center;">
                        <i class="fas fa-bars"></i>
                    </button>
                @endauth
                <a href="{{ url('/') }}" class="logo-area">
                    <i class="fas fa-feather-alt"></i>
                    أثيرا
                </a>
                <div class="nav-links">
                    <a href="{{ route('opportunities.index') }}">تصفح الفرص</a>
                    <a href="#">عن المنصة</a>
                    <a href="#">الشركاء</a>
                </div>
            </div>

           

                <div class="auth-actions">
                    @auth
                        <div class="user-profile" onclick="toggleSidebar()">
                            <span style="font-weight: 600;">{{ auth()->user()->name }}</span>
                            <img src="{{ auth()->user()->avatar_url }}" class="avatar">
                        </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline">تسجيل الدخول</a>
                    <a href="{{ route('choose.account.type') }}" class="btn btn-primary">انضم إلينا</a>
                @endauth
            </div>
        </nav>

        <section class="hero">
            <!-- Background Media Container -->
            <div class="hero-bg-container">
                {{-- Example video - can be replaced with actual path --}}
                <!-- {{-- <video autoplay muted loop playsinline class="hero-bg-media">
                    <source src="{{ asset('assets/videos/hero-bg.mp4') }}" type="video/mp4">
                </video> --}} -->
                
                {{-- Placeholder Image - you can change this to any image path --}}
                <img src="assets/images/image.png" class="hero-bg-media" alt="Background">
            </div>

            <!-- Glassy Overlay -->
            <div class="hero-overlay"></div>

            <div class="hero-content">
                <h1>أثيرا: اصنع أثراً يمتد <br> عبر التطوع والتدريب</h1>
                <p>بوابتك الذكية لاكتشاف الفرص التي تنمي مهاراتك وتخدم مجتمعك. انضم إلى الآلاف من المتطوعين المبدعين اليوم.</p>
                <div style="display: flex; gap: 1.5rem; justify-content: center;">
                    <a href="{{ route('opportunities.index') }}" class="btn btn-primary" style="padding: 1.2rem 3rem; font-size: 1.1rem;">استكشف الفرص الآن</a>
                    <a href="#" class="btn btn-outline" style="padding: 1.2rem 3rem; font-size: 1.1rem; border-color: rgba(255,255,255,0.3); background: rgba(255,255,255,0.1); color: white;">كيف أبدأ؟</a>
                </div>
            </div>
        </section>

        <section class="stats-grid">
            <div class="stat-item">
                <h3>+{{ $stats['opportunities_count'] }}</h3>
                <p>عدد الفرص</p>
            </div>
            <div class="stat-item">
                <h3>+{{ $stats['partners_count'] }}</h3>
                <p>شريك مسجل</p>
            </div>
            <div class="stat-item">
                <h3>+{{ $stats['certificates_count'] }}</h3>
                <p>شهادات مُصدّرة</p>
            </div>
            <div class="stat-item">
                <h3>+{{ $stats['organizations_count'] }}</h3>
                <p>عدد المؤسسات</p>
            </div>
            <div class="stat-item">
                <h3>+{{ number_format($stats['total_hours']) }}</h3>
                <p>ساعات التطوع والتدريب</p>
            </div>
        </section>

        <section class="carousel-container">
            <div class="carousel-title">
                <h2 style="font-size: 2.5rem; font-weight: 800;">أحدث الفرص التطوعية</h2>
                <p style="color: var(--text-muted);">اكتشف الفرص التي تم إضافتها مؤخراً وساهم بمهاراتك</p>
            </div>
            <div class="carousel-track">
                @foreach($latestOpportunities as $opp)
                    <div class="opp-card">
                        <span class="badge">{{ $opp->type }}</span>
                        <h4>{{ $opp->title }}</h4>
                        <p>{{ Str::limit($opp->description, 100) }}</p>
                        <div class="opp-footer">
                            <span><i class="fas fa-building" style="color: var(--primary);"></i> {{ $opp->organization->name }}</span>
                            <span style="color: var(--accent); font-weight: bold;">{{ $opp->seats }} مقاعد</span>
                        </div>
                    </div>
                @endforeach
                {{-- Duplicate for infinity effect if few items --}}
                @if($latestOpportunities->count() < 10)
                    @foreach($latestOpportunities as $opp)
                        <div class="opp-card">
                            <span class="badge">{{ $opp->type }}</span>
                            <h4>{{ $opp->title }}</h4>
                            <p>{{ Str::limit($opp->description, 100) }}</p>
                            <div class="opp-footer">
                                <span><i class="fas fa-building" style="color: var(--primary);"></i> {{ $opp->organization->name }}</span>
                                <span style="color: var(--accent); font-weight: bold;">{{ $opp->seats }} مقاعد</span>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </section>

        <section style="padding: 6rem 2rem; background: #1e1b4b; color: white; text-align: center;">
            <h2 style="font-size: 3rem; margin-bottom: 2rem;">هل أنت مستعد لمشاركة موهبتك؟</h2>
            <p style="font-size: 1.25rem; color: #a5b4fc; max-width: 700px; margin: 0 auto 3rem;">انضم إلى مجتمع أثيرا وكن فعالاً في تطوير مجتمعك. نحن نؤمن بأن كل فعل تطوعي هو استثمار في المستقبل.</p>
            <a href="{{ route('choose.account.type') }}" class="btn btn-primary" style="padding: 1.2rem 4rem; font-size: 1.2rem; background: white; color: var(--primary);">ابدأ الآن مجاناً</a>
        </section>

        <footer>
            <div class="footer-grid">
                <div class="footer-col">
                    <h4 style="font-size: 1.5rem; color: #fff;">أثيرا</h4>
                    <p>منصة ليبية رائدة لتمكين المتطوعين وربطهم بالفرص التنموية والمهنية.</p>
                    <div style="display: flex; gap: 1rem; margin-top: 2rem; font-size: 1.5rem;">
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
                <div class="footer-col">
                    <h4>روابط سريعة</h4>
                    <ul>
                        <li><a href="#">عن أثيرا</a></li>
                        <li><a href="#">تصفح الفرص</a></li>
                        <li><a href="#">الأسئلة الشائعة</a></li>
                        <li><a href="#">انضم كمنظمة</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>الدعم والقانونية</h4>
                    <ul>
                        <li><a href="#">شروط الاستخدام</a></li>
                        <li><a href="#">سياسة الخصوصية</a></li>
                        <li><a href="#">مركز المساعدة</a></li>
                        <li><a href="#">اتصل بنا</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>النشرة البريدية</h4>
                    <p>اشترك لتصلك أحدث الفرص التطوعية مباشرة.</p>
                    <div style="display: flex; gap: 0.5rem; margin-top: 1rem;">
                        <input type="email" placeholder="بريدك الإلكتروني" style="padding: 0.75rem; border-radius: 0.5rem; border: none; width: 100%;">
                        <button class="btn btn-primary" style="padding: 0.75rem 1.5rem; border-radius: 0.5rem;">اشترك</button>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 جميع الحقوق محفوظة لمنصة أثيرا التطوعية</p>
            </div>
        </footer>
    </div>

    <script>
        @auth
            document.addEventListener('DOMContentLoaded', () => {
                // Initial state for landing page should be collapsed sidebar
                const sidebar = document.getElementById('sidebar');
                const mainContent = document.getElementById('mainContent');
                if(sidebar) sidebar.classList.add('collapsed');
                if(mainContent) mainContent.classList.remove('shifted');
            });
        @endauth
    </script>
</body>
</html>
