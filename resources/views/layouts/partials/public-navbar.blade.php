<nav class="navbar-public">
    <div class="nav-section logo-side">
        @auth
            <button class="toggle-sidebar" onclick="typeof toggleSidebar === 'function' && toggleSidebar()" style="background:none; border:none; font-size:1.5rem; color:#3b82f6; cursor:pointer; display: flex; align-items: center; margin-left: 1rem;">
                <i class="fas fa-bars"></i>
            </button>
        @endauth
        <a href="{{ url('/') }}" class="logo-area" style="display: flex; align-items: center; text-decoration: none;">
            <img src="{{ asset('assets/images/logo-removebg-preview.png') }}" alt="أثيرا" style="height: 50px; width: auto; object-fit: contain;">
        </a>
    </div>

    <div class="nav-section links-center">
        <a href="{{ route('opportunities.index') }}">تصفح الفرص</a>
        <a href="#">عن المنصة</a>
        <a href="{{ route('organizations.index') }}">المؤسسات الشريكة</a>
    </div>

    <div class="nav-section auth-side">
        @auth
            <div class="user-profile" style="display: flex; align-items: center; gap: 0.75rem;">
                <a href="{{ auth()->user()->user_type === 'organization' ? route('organization.profile.edit') : route('dashboard.profile') }}" 
                   style="font-weight: 600; color: #1e293b; text-decoration: none;" 
                   onmouseover="this.style.color='#3b82f6'" onmouseout="this.style.color='#1e293b'">
                   {{ auth()->user()->name }}
                </a>
                <img src="{{ auth()->user()->avatar_url }}" class="avatar" 
                     onclick="openPhotoModal('{{ auth()->user()->avatar_url }}', '{{ auth()->user()->name }}')"
                     style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #3b82f6; cursor: pointer; transition: transform 0.2s;"
                     onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
            </div>
        @else
            <div class="auth-actions" style="display: flex; gap: 1rem;">
                <a href="{{ route('login') }}" class="btn-auth btn-outline">دخول</a>
                <a href="{{ route('choose.account.type') }}" class="btn-auth btn-primary">انضمام</a>
            </div>
        @endauth
    </div>
</nav>
