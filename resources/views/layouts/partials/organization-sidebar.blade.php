<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h3>لوحة تحكم المؤسسة</h3>
    </div>
    <ul class="sidebar-menu">
        <li><a href="{{ url('/') }}"><i class="fas fa-globe"></i> الواجهة الرئيسية</a></li>
        @auth
            <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="fas fa-home"></i> الرئيسية</a></li>
            <li><a href="{{ route('organization.profile.edit') }}" class="{{ request()->routeIs('organization.profile.*') ? 'active' : '' }}"><i class="fas fa-building"></i> الملف المؤسسي</a></li>
            <li><a href="{{ route('organization.opportunities.index') }}" class="{{ request()->routeIs('organization.opportunities.*') ? 'active' : '' }}"><i class="fas fa-briefcase"></i> إدارة الفرص</a></li>
            <li><a href="{{ route('organization.applications.index') }}" class="{{ request()->routeIs('organization.applications.*') ? 'active' : '' }}"><i class="fas fa-users"></i> المتقدمين</a></li>
            <li><a href="{{ route('organization.certificates.index') }}" class="{{ request()->routeIs('organization.certificates.*') ? 'active' : '' }}"><i class="fas fa-certificate"></i> الشهادات</a></li>
            <li><a href="{{ route('dashboard.messages') }}" class="{{ request()->routeIs('dashboard.messages') ? 'active' : '' }}"><i class="fas fa-comments"></i> الرسائل</a></li>
            <li><a href="{{ route('dashboard.notifications') }}" class="{{ request()->routeIs('dashboard.notifications') ? 'active' : '' }}"><i class="fas fa-bell"></i> التنبيهات <span class="badge" style="background:#ef4444; padding:2px 6px; border-radius:10px; font-size:10px;">{{ auth()->user()->unread_notifications_count }}</span></a></li>
            
            <li style="margin-top: 50px;">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" style="background:none; border:none; color:#f87171; padding: 15px 25px; cursor:pointer; width:100%; text-align:right; font-size:16px;">
                        <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
                    </button>
                </form>
            </li>
        @endauth
    </ul>
</div>

<style>
    .sidebar {
        width: 260px;
        height: 100vh;
        background: #0f172a; /* Darker for Org */
        color: white;
        position: fixed;
        right: 0;
        top: 0;
        padding-top: 20px;
        transition: all 0.3s;
        z-index: 1000;
        direction: rtl;
    }
    /* Reuse styles from sidebar.blade.php for consistency or import them */
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
        background: #1e293b;
        color: white;
        border-right: 4px solid #f59e0b; /* Orange for Org */
    }
    .sidebar.collapsed {
        transform: translateX(100%);
    }
    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(100%);
        }
        .sidebar.show {
            transform: translateX(0);
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
