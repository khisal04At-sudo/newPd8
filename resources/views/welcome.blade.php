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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            overflow-x: hidden;
            color: var(--text-main);
        }

        /* --- Header --- */
        .navbar {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            padding: 0.75rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
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
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            box-shadow: 0 4px 10px rgba(79, 70, 229, 0.3);
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(79, 70, 229, 0.4); }
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
            padding: 8rem 2rem;
            position: relative;
            background: radial-gradient(circle at top left, #eef2ff 0%, transparent 40%),
                        radial-gradient(circle at bottom right, #fff1f2 0%, transparent 40%);
            text-align: center;
            overflow: hidden;
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
            background: linear-gradient(135deg, #1e1b4b 0%, #4338ca 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1.5rem;
            line-height: 1.1;
        }
        .hero p {
            font-size: 1.5rem;
            color: var(--text-muted);
            margin-bottom: 3rem;
            line-height: 1.6;
        }

        /* --- Stats Section --- */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            max-width: 1100px;
            margin: -4rem auto 4rem;
            background: white;
            padding: 3rem;
            border-radius: 2rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
            position: relative;
            z-index: 10;
        }
        .stat-item h3 {
            font-size: 2.5rem;
            color: var(--primary);
            margin: 0;
            font-weight: 800;
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
            background: var(--bg-light);
            border-radius: 1.5rem;
            padding: 1.5rem;
            border: 1px solid #e2e8f0;
            transition: all 0.3s;
            flex-shrink: 0;
        }
        .opp-card:hover { transform: translateY(-10px); background: #fdfdfd; border-color: var(--primary-light); }
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
            <div style="display: flex; align-items: center; gap: 2rem;">
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

            <div class="search-bar">
                <i class="fas fa-search" style="color: #94a3b8;"></i>
                <input type="text" placeholder="ابحث عن فرصة تطوعية...">
                </div>

                <div class="auth-actions">
                    @auth
                        <div class="user-profile" onclick="toggleSidebar()">
                            <span style="font-weight: 600;">{{ auth()->user()->name }}</span>
                            <img src="{{ auth()->user()->avatar_url }}" class="avatar">
                            <i class="fas fa-bars" style="margin-left: 10px;"></i>
                        </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline">تسجيل الدخول</a>
                    <a href="{{ route('choose.account.type') }}" class="btn btn-primary">انضم إلينا</a>
                @endauth
            </div>
        </nav>

        <section class="hero">
            <div class="hero-content">
                <h1>أثيرا: اصنع أثراً يمتد <br> عبر التطوع والتدريب</h1>
                <p>بوابتك الذكية لاكتشاف الفرص التي تنمي مهاراتك وتخدم مجتمعك. انضم إلى الآلاف من المتطوعين المبدعين اليوم.</p>
                <div style="display: flex; gap: 1.5rem; justify-content: center;">
                    <a href="{{ route('opportunities.index') }}" class="btn btn-primary" style="padding: 1.2rem 3rem; font-size: 1.1rem;">استكشف الفرص الآن</a>
                    <a href="#" class="btn btn-outline" style="padding: 1.2rem 3rem; font-size: 1.1rem;">كيف أبدأ؟</a>
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
