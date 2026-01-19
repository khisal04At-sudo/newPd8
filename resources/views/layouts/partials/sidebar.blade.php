<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h3>منصة التطوع</h3>
    </div>
    <ul class="sidebar-menu">
        <li><a href="{{ url('/') }}"><i class="fas fa-globe"></i> الواجهة الرئيسية</a></li>
        @auth
            <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="fas fa-home"></i> الرئيسية</a></li>
            <li><a href="{{ route('dashboard.profile') }}" class="{{ request()->routeIs('dashboard.profile') ? 'active' : '' }}"><i class="fas fa-user"></i> البروفايل</a></li>
            <li><a href="{{ route('dashboard.opportunities') }}" class="{{ request()->routeIs('dashboard.opportunities') ? 'active' : '' }}"><i class="fas fa-briefcase"></i> الفرص التطوعية</a></li>
            <li><a href="{{ route('dashboard.notifications') }}" class="{{ request()->routeIs('dashboard.notifications') ? 'active' : '' }}"><i class="fas fa-bell"></i> التنبيهات <span class="badge" style="background:#ef4444; padding:2px 6px; border-radius:10px; font-size:10px;">{{ auth()->user()->unread_notifications_count }}</span></a></li>
            <li><a href="{{ route('dashboard.achievements') }}" class="{{ request()->routeIs('dashboard.achievements') ? 'active' : '' }}"><i class="fas fa-star"></i> الإنجازات</a></li>
            <li><a href="{{ route('dashboard.messages') }}" class="{{ request()->routeIs('dashboard.messages') ? 'active' : '' }}"><i class="fas fa-comments"></i> الرسائل</a></li>
            <li><a href="#"><i class="fas fa-cog"></i> الإعدادات</a></li>
            <li style="margin-top: 50px;">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" style="background:none; border:none; color:#f87171; padding: 15px 25px; cursor:pointer; width:100%; text-align:right; font-size:16px;">
                        <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
                    </button>
                </form>
            </li>
        @else
            <li><a href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> تسجيل الدخول</a></li>
            <li><a href="{{ route('choose.account.type') }}"><i class="fas fa-user-plus"></i> إنشاء حساب</a></li>
        @endauth
    </ul>
</div>

<style>
    .sidebar {
        width: 260px;
        height: 100vh;
        background: #1e293b;
        color: white;
        position: fixed;
        right: 0;
        top: 0;
        padding-top: 20px;
        transition: all 0.3s;
        z-index: 1000;
        direction: rtl;
    }
    .sidebar.collapsed {
        transform: translateX(100%);
    }
    .sidebar-header {
        padding: 0 25px 20px;
        text-align: center;
        border-bottom: 1px solid #334155;
        margin-bottom: 20px;
    }
    .sidebar-menu {
        list-style: none;
        padding: 0;
    }
    .sidebar-menu li a {
        padding: 15px 25px;
        display: flex;
        align-items: center;
        color: #cbd5e1;
        text-decoration: none;
        transition: all 0.2s;
        gap: 12px;
    }
    .sidebar-menu li a:hover, .sidebar-menu li a.active {
        background: #334155;
        color: white;
        border-right: 4px solid #4f46e5;
    }
    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(100%);
        }
        .sidebar.show {
            transform: translateX(0);
        }
    }
    .shifted {
        margin-right: 260px !important;
    }
    .expanded {
        margin-right: 0 !important;
    }
    .main-content {
        transition: all 0.3s ease;
    }
    @media (max-width: 768px) {
        .shifted {
            margin-right: 0 !important;
        }
    }
</style>

<script>
    if (typeof toggleSidebar !== 'function') {
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent') || document.querySelector('.main-content');
            
            if (window.innerWidth > 768) {
                sidebar.classList.toggle('collapsed');
                if (mainContent) mainContent.classList.toggle('shifted');
                if (mainContent) mainContent.classList.toggle('expanded');
            } else {
                sidebar.classList.toggle('show');
            }
        }
    }
</script>
