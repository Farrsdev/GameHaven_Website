<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GameHaven - Your Gaming Paradise')</title>
<style>
        :root {
            --primary-dark: #0f172a;
            --secondary-dark: #1e293b;
            --accent-blue: #3b82f6;
            --soft-blue: #60a5fa;
            --light-blue: #dbeafe;
            --text-light: #f8fafc;
            --text-gray: #94a3b8;
            --text-dark: #1e293b;
            --gradient-primary: linear-gradient(135deg, #1e40af, #3b82f6);
            --gradient-secondary: linear-gradient(135deg, #0f172a, #1e40af);
            --shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --border-radius: 12px;
            --border-radius-lg: 20px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
            color: var(--text-light);
            min-height: 100vh;
        }

        .user-navbar {
            background: var(--gradient-secondary);
            backdrop-filter: blur(20px);
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .navbar-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            height: 80px;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 16px;
            text-decoration: none;
        }

        .logo {
            width: 45px;
            height: 45px;
            background: var(--gradient-primary);
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-light);
            font-weight: bold;
            font-size: 20px;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
            transition: var(--transition);
        }

        .logo:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
        }

        .brand-text {
            font-size: 28px;
            font-weight: 800;
            background: linear-gradient(135deg, #60a5fa, #dbeafe);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.5px;
        }

        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .nav-action-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: var(--text-light);
            cursor: pointer;
            padding: 12px;
            border-radius: var(--border-radius);
            transition: var(--transition);
            position: relative;
            backdrop-filter: blur(10px);
        }

        .nav-action-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: var(--soft-blue);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.2);
        }

        .notification-badge {
            position: absolute;
            top: 8px;
            right: 8px;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4);
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 16px;
            border-radius: var(--border-radius-lg);
            cursor: pointer;
            transition: var(--transition);
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }

        .user-menu:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: var(--soft-blue);
            transform: translateY(-2px);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--gradient-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-light);
            font-weight: 700;
            font-size: 16px;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            font-size: 14px;
            color: var(--text-light);
        }

        .user-balance {
            font-size: 12px;
            color: var(--soft-blue);
            font-weight: 500;
        }

        .navbar-menu {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(20px);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 0 32px;
        }

        .nav-menu-items {
            display: flex;
            align-items: center;
            list-style: none;
        }

        .nav-menu-item {
            position: relative;
        }

        .nav-menu-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px 24px;
            color: var(--text-gray);
            text-decoration: none;
            font-weight: 500;
            font-size: 15px;
            transition: var(--transition);
            border-bottom: 3px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .nav-menu-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.1), transparent);
            transition: var(--transition);
        }

        .nav-menu-link:hover {
            color: var(--text-light);
            background: rgba(59, 130, 246, 0.1);
        }

        .nav-menu-link:hover::before {
            left: 100%;
        }

        /* Enhanced Active State dengan Indikator yang Lebih Menonjol */
        .nav-menu-link.active {
            color: var(--text-light);
            border-bottom-color: var(--accent-blue);
            background: rgba(59, 130, 246, 0.2);
            position: relative;
        }

        .nav-menu-link.active::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 100%;
            height: 3px;
            background: var(--gradient-primary);
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.7);
            animation: pulse-glow 2s infinite;
        }

        /* Tambahan: Indikator titik aktif di samping */
        .nav-menu-link.active::before {
            content: '';
            position: absolute;
            left: 8px;
            top: 50%;
            transform: translateY(-50%);
            width: 6px;
            height: 6px;
            background: var(--accent-blue);
            border-radius: 50%;
            box-shadow: 0 0 10px rgba(59, 130, 246, 0.8);
            animation: pulse-dot 1.5s infinite;
        }

        @keyframes pulse-glow {
            0%, 100% {
                box-shadow: 0 0 20px rgba(59, 130, 246, 0.7);
            }
            50% {
                box-shadow: 0 0 25px rgba(59, 130, 246, 0.9);
            }
        }

        @keyframes pulse-dot {
            0%, 100% {
                transform: translateY(-50%) scale(1);
                opacity: 1;
            }
            50% {
                transform: translateY(-50%) scale(1.2);
                opacity: 0.8;
            }
        }

        .nav-icon {
            font-size: 18px;
            width: 24px;
            text-align: center;
            transition: var(--transition);
        }

        .nav-menu-link:hover .nav-icon {
            transform: scale(1.1);
        }

        .nav-menu-link.active .nav-icon {
            color: var(--accent-blue);
            transform: scale(1.1);
        }

        /* Tambahan: Badge notifikasi untuk menu item */
        .menu-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            border-radius: 10px;
            padding: 2px 6px;
            font-size: 10px;
            font-weight: 600;
            min-width: 18px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4);
        }

        /* Tambahan: Tooltip untuk menu */
        .nav-menu-item {
            position: relative;
        }

        .nav-menu-item:hover .menu-tooltip {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .menu-tooltip {
            position: absolute;
            bottom: -40px;
            left: 50%;
            transform: translateX(-50%) translateY(10px);
            background: rgba(15, 23, 42, 0.95);
            color: var(--text-light);
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            z-index: 1002;
        }

        .menu-tooltip::before {
            content: '';
            position: absolute;
            top: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-bottom: 5px solid rgba(15, 23, 42, 0.95);
        }

        /* Profile Dropdown */
        .profile-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            min-width: 200px;
            display: none;
            z-index: 1001;
        }

        .profile-dropdown.show {
            display: block;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 20px;
            color: var(--text-gray);
            text-decoration: none;
            transition: var(--transition);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            position: relative;
        }

        .dropdown-item:hover {
            background: rgba(59, 130, 246, 0.1);
            color: var(--text-light);
        }

        .dropdown-item:last-child {
            border-bottom: none;
        }

        .dropdown-icon {
            width: 20px;
            text-align: center;
            color: var(--soft-blue);
        }

        /* Tambahan: Indikator untuk dropdown item aktif */
        .dropdown-item.active {
            color: var(--text-light);
            background: rgba(59, 130, 246, 0.15);
        }

        .dropdown-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: var(--gradient-primary);
        }

        /* Main Content Styles */
        .main-content {
            min-height: calc(100vh - 140px);
            padding: 0;
        }

        /* Admin Indicator */
        .admin-indicator {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            margin-left: 8px;
            animation: pulse-admin 2s infinite;
        }

        @keyframes pulse-admin {
            0%, 100% {
                box-shadow: 0 0 5px rgba(245, 158, 11, 0.5);
            }
            50% {
                box-shadow: 0 0 15px rgba(245, 158, 11, 0.8);
            }
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .navbar-top {
                padding: 0 20px;
                height: 70px;
            }

            .brand-text {
                font-size: 22px;
            }

            .user-info {
                display: none;
            }

            .navbar-menu {
                padding: 0 20px;
                overflow-x: auto;
            }

            .nav-menu-items {
                min-width: max-content;
            }

            .nav-menu-link {
                padding: 16px 20px;
                font-size: 14px;
            }

            .nav-menu-link.active::before {
                left: 6px;
                width: 4px;
                height: 4px;
            }

            .nav-action-btn {
                padding: 10px;
            }

            .profile-dropdown {
                position: fixed;
                top: 70px;
                left: 0;
                right: 0;
                border-radius: 0;
                border-left: none;
                border-right: none;
            }

            .menu-tooltip {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .navbar-top {
                padding: 0 16px;
            }

            .logo {
                width: 38px;
                height: 38px;
                font-size: 18px;
            }

            .brand-text {
                font-size: 20px;
            }

            .nav-action-btn span {
                display: none;
            }

            .nav-menu-link span {
                display: none;
            }

            .nav-icon {
                font-size: 20px;
            }

            .user-menu {
                padding: 6px;
            }

            .user-avatar {
                width: 35px;
                height: 35px;
                font-size: 14px;
            }
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.8);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--gradient-primary);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--accent-blue);
        }
    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <nav class="user-navbar">
        <!-- Top Bar -->
        <div class="navbar-top">
            <a href="{{ url('/home') }}" class="navbar-brand">
                <div class="logo">GH</div>
                <div class="brand-text">GameHaven</div>
            </a>

            <div class="navbar-actions">
                <button class="nav-action-btn" title="Profile" id="profileBtn">
                    <i class="fas fa-user"></i>
                </button>
                <button class="nav-action-btn" title="Shopping Cart">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="notification-badge">3</span>
                </button>
                <div class="user-menu" id="userMenu">
                    <div class="user-avatar">
                        {{ session('username') ? strtoupper(substr(session('username'), 0, 1)) : 'U' }}
                    </div>
                    <div class="user-info">
                        <div class="user-name">
                            {{ session('username') ?? 'Gamer' }}
                            @if(session('role') == 1)
                                <span class="admin-indicator">ADMIN</span>
                            @endif
                        </div>
                        <div class="user-balance">Rp 1.250.000</div>
                    </div>
                    <i class="fas fa-chevron-down" style="font-size: 12px; color: var(--soft-blue);"></i>
                </div>
            </div>
        </div>

        <!-- Profile Dropdown -->
        <div class="profile-dropdown" id="profileDropdown">
            <a href="{{ url('/profile') }}" class="dropdown-item {{ request()->is('profile') ? 'active' : '' }}">
                <i class="dropdown-icon fas fa-user-circle"></i>
                <span>My Profile</span>
            </a>
            @if(session('role') == 1)
                <a href="{{ url('/admin/dashboard') }}" class="dropdown-item">
                    <i class="dropdown-icon fas fa-crown"></i>
                    <span>Admin Dashboard</span>
                </a>
            @endif
            <form action="{{ url('/logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="dropdown-item" style="border-top: 1px solid rgba(255, 255, 255, 0.1); margin-top: 8px; width: 100%; text-align: left; background: none; border: none; color: inherit; cursor: pointer;">
                    <i class="dropdown-icon fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>

        <!-- Navigation Menu -->
        <div class="navbar-menu">
            <ul class="nav-menu-items">
                <li class="nav-menu-item">
                    <a href="{{ url('/home') }}" class="nav-menu-link {{ request()->is('home') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <span>Home</span>
                        <div class="menu-tooltip">Dashboard Home</div>
                    </a>
                </li>
                <li class="nav-menu-item">
                    <a href="{{ url('/games') }}" class="nav-menu-link {{ request()->is('games') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-gamepad"></i>
                        <span>Games</span>
                        <span class="menu-badge">New</span>
                        <div class="menu-tooltip">Browse All Games</div>
                    </a>
                </li>
                <li class="nav-menu-item">
                    <a href="{{ url('/store') }}" class="nav-menu-link {{ request()->is('store') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-store"></i>
                        <span>Store</span>
                        <div class="menu-tooltip">Game Store</div>
                    </a>
                </li>
                <li class="nav-menu-item">
                    <a href="{{ url('/purchased') }}" class="nav-menu-link {{ request()->is('purchased') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-shopping-bag"></i>
                        <span>My Games</span>
                        <div class="menu-tooltip">Your Purchased Games</div>
                    </a>
                </li>
                <li class="nav-menu-item">
                    <a href="{{ url('/downloads') }}" class="nav-menu-link {{ request()->is('downloads') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-download"></i>
                        <span>Downloads</span>
                        <div class="menu-tooltip">Download Manager</div>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

     <script>
        // Highlight active menu item based on current URL
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const menuLinks = document.querySelectorAll('.nav-menu-link');
            
            menuLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });

            // Highlight active dropdown items
            const dropdownItems = document.querySelectorAll('.dropdown-item[href]');
            dropdownItems.forEach(item => {
                if (item.getAttribute('href') === currentPath) {
                    item.classList.add('active');
                }
            });

            // Profile dropdown functionality
            const profileBtn = document.getElementById('profileBtn');
            const userMenu = document.getElementById('userMenu');
            const profileDropdown = document.getElementById('profileDropdown');

            function toggleProfileDropdown() {
                profileDropdown.classList.toggle('show');
            }

            profileBtn.addEventListener('click', toggleProfileDropdown);
            userMenu.addEventListener('click', toggleProfileDropdown);

            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (!profileBtn.contains(event.target) && !userMenu.contains(event.target) && !profileDropdown.contains(event.target)) {
                    profileDropdown.classList.remove('show');
                }
            });

            // Close dropdown on escape key
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    profileDropdown.classList.remove('show');
                }
            });

            // Add smooth scrolling for better UX
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Add hover effects for menu items
            menuLinks.forEach(link => {
                link.addEventListener('mouseenter', function() {
                    if (!this.classList.contains('active')) {
                        this.style.background = 'rgba(59, 130, 246, 0.05)';
                    }
                });

                link.addEventListener('mouseleave', function() {
                    if (!this.classList.contains('active')) {
                        this.style.background = '';
                    }
                });
            });
        });

        // Add parallax effect to background
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const parallax = document.querySelector('body');
            if (parallax) {
                parallax.style.backgroundPosition = `center ${scrolled * 0.5}px`;
            }
        });
    </script>
</body>
</html>