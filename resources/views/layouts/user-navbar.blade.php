<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GameHaven')</title>
    <style>
        :root {
            --bg-primary: #0a0a0a;
            --bg-secondary: #111111;
            --text-primary: #ffffff;
            --text-secondary: #888888;
            --accent: #3b82f6;
            --border: #333333;
            --transition: all 0.2s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body {
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
        }

        /* Simplified Navbar */
        .navbar {
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 60px;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--text-primary);
            font-weight: 600;
            font-size: 18px;
        }

        .logo {
            width: 32px;
            height: 32px;
            background: var(--accent);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .nav-btn {
            background: none;
            border: 1px solid var(--border);
            color: var(--text-secondary);
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            transition: var(--transition);
            font-size: 14px;
        }

        .nav-btn:hover {
            border-color: var(--accent);
            color: var(--text-primary);
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            transition: var(--transition);
        }

        .user-menu:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
            font-weight: 500;
        }

        /* Simplified Navigation */
        .nav-menu {
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border);
        }

        .nav-items {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            list-style: none;
            overflow-x: auto;
        }

        .nav-item {
            flex-shrink: 0;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 16px 20px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            border-bottom: 2px solid transparent;
            transition: var(--transition);
            white-space: nowrap;
        }

        .nav-link:hover {
            color: var(--text-primary);
        }

        .nav-link.active {
            color: var(--accent);
            border-bottom-color: var(--accent);
        }

        .nav-icon {
            font-size: 16px;
            width: 18px;
        }

        /* Main Content */
        .main-content {
            min-height: calc(100vh - 120px);
        }

        /* Profile Dropdown */
        .profile-dropdown {
            position: absolute;
            top: 100%;
            right: 20px;
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 8px;
            min-width: 180px;
            display: none;
            z-index: 1000;
        }

        .profile-dropdown.show {
            display: block;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 14px;
            transition: var(--transition);
            border-bottom: 1px solid var(--border);
        }

        .dropdown-item:hover {
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-primary);
        }

        .dropdown-item:last-child {
            border-bottom: none;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .navbar-container {
                padding: 0 16px;
                height: 56px;
            }

            .nav-items {
                padding: 0 16px;
            }

            .nav-link {
                padding: 14px 16px;
                font-size: 13px;
            }

            .user-info {
                display: none;
            }
        }
    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <a href="{{ url('/home') }}" class="navbar-brand">
                <div class="logo">G</div>
                GameHaven
            </a>

            <div class="navbar-actions">
                <button class="nav-btn">
                    <i class="fas fa-shopping-cart"></i>
                </button>
                <div class="user-menu" id="userMenu">
                    <div class="user-avatar">
                        {{ session('username') ? strtoupper(substr(session('username'), 0, 1)) : 'U' }}
                    </div>
                    <div class="user-info">
                        {{ session('username') ?? 'User' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Dropdown -->
        <div class="profile-dropdown" id="profileDropdown">
            <a href="{{ url('/profile') }}" class="dropdown-item">
                <i class="fas fa-user"></i>
                <span>Profile</span>
            </a>
            @if(session('role') == 1)
                <a href="{{ url('/admin/dashboard') }}" class="dropdown-item">
                    <i class="fas fa-crown"></i>
                    <span>Admin</span>
                </a>
            @endif
            <form action="{{ url('/logout') }}" method="POST">
                @csrf
                <button type="submit" class="dropdown-item" style="background: none; border: none; width: 100%; text-align: left; color: inherit; cursor: pointer;">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </nav>

    <!-- Navigation Menu -->
    <nav class="nav-menu">
        <ul class="nav-items">
            <li class="nav-item">
                <a href="{{ url('/home') }}" class="nav-link {{ request()->is('home') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-home"></i>
                    <span>Home</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/games') }}" class="nav-link {{ request()->is('games') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-gamepad"></i>
                    <span>Games</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/store') }}" class="nav-link {{ request()->is('store') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-store"></i>
                    <span>Store</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/purchased') }}" class="nav-link {{ request()->is('purchased') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-shopping-bag"></i>
                    <span>My Games</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ url('/downloads') }}" class="nav-link {{ request()->is('downloads') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-download"></i>
                    <span>Download History</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userMenu = document.getElementById('userMenu');
            const profileDropdown = document.getElementById('profileDropdown');

            userMenu.addEventListener('click', function(e) {
                e.stopPropagation();
                profileDropdown.classList.toggle('show');
            });

            document.addEventListener('click', function() {
                profileDropdown.classList.remove('show');
            });

            const currentPath = window.location.pathname;
            document.querySelectorAll('.nav-link').forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>