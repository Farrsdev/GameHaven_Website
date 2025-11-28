<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Navbar - GameHaven</title>
    <style>
        :root {
            --primary-blue: #6a9eff;
            --dark-blue: #4a7de8;
            --light-blue: #f0f5ff;
            --accent-blue: #8ab4ff;
            --bg-white: #ffffff;
            --text-dark: #1e293b;
            --text-gray: #64748b;
            --text-light: #ffffff;
            --border-color: #e2e8f0;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
            --border-radius: 8px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .admin-navbar {
            background: var(--bg-white);
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid var(--border-color);
        }

        .navbar-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            height: 70px;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-light);
            font-weight: bold;
            font-size: 18px;
        }

        .brand-text {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-dark);
        }

        .admin-indicator {
            background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
            color: var(--text-light);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 12px;
        }

        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .nav-action-btn {
            background: none;
            border: none;
            color: var(--text-gray);
            cursor: pointer;
            padding: 8px;
            border-radius: var(--border-radius);
            transition: var(--transition);
            position: relative;
        }

        .nav-action-btn:hover {
            background-color: var(--light-blue);
            color: var(--primary-blue);
        }

        .notification-badge {
            position: absolute;
            top: 4px;
            right: 4px;
            background-color: #ef4444;
            color: white;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 12px;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
        }

        .user-menu:hover {
            background-color: var(--light-blue);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-light);
            font-weight: bold;
            font-size: 14px;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            font-size: 14px;
            color: var(--text-dark);
        }

        .user-role {
            font-size: 12px;
            color: var(--text-gray);
        }

        .navbar-menu {
            background: var(--light-blue);
            border-top: 1px solid var(--border-color);
            padding: 0 24px;
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
            gap: 8px;
            padding: 16px 20px;
            color: var(--text-gray);
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            transition: var(--transition);
            border-bottom: 3px solid transparent;
        }

        .nav-menu-link:hover {
            color: var(--primary-blue);
            background-color: rgba(106, 158, 255, 0.1);
        }

        .nav-menu-link.active {
            color: var(--primary-blue);
            border-bottom-color: var(--primary-blue);
            background-color: rgba(106, 158, 255, 0.1);
        }

        .nav-icon {
            font-size: 16px;
            width: 20px;
            text-align: center;
        }

        /* Dropdown Menu */
        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            background: var(--bg-white);
            min-width: 200px;
            box-shadow: var(--shadow);
            border-radius: var(--border-radius);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: var(--transition);
            z-index: 1001;
        }

        .nav-menu-item:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            display: block;
            padding: 12px 16px;
            color: var(--text-dark);
            text-decoration: none;
            transition: var(--transition);
            border-bottom: 1px solid var(--border-color);
        }

        .dropdown-item:hover {
            background-color: var(--light-blue);
            color: var(--primary-blue);
        }

        .dropdown-item:last-child {
            border-bottom: none;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .navbar-top {
                padding: 0 16px;
                height: 60px;
            }

            .brand-text {
                font-size: 20px;
            }

            .user-info {
                display: none;
            }

            .navbar-menu {
                padding: 0 16px;
                overflow-x: auto;
            }

            .nav-menu-items {
                min-width: max-content;
            }

            .nav-menu-link {
                padding: 12px 16px;
                font-size: 13px;
            }
        }

        @media (max-width: 480px) {
            .admin-indicator {
                display: none;
            }

            .nav-action-btn span {
                display: none;
            }

            .nav-menu-link span {
                display: none;
            }

            .nav-icon {
                font-size: 18px;
            }
        }
    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <nav class="admin-navbar">
        <!-- Top Bar -->
        <div class="navbar-top">
            <div class="navbar-brand">
                <div class="logo">GH</div>
                <div class="brand-text">GameHaven</div>
                <div class="admin-indicator">Admin</div>
            </div>

            <div class="navbar-actions">
                <button class="nav-action-btn">
                    <i class="fas fa-search"></i>
                </button>
                <button class="nav-action-btn">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
                </button>
                <div class="user-menu">
                    <div class="user-avatar">
                        {{ substr(session('username') ?? 'A', 0, 1) }}
                    </div>
                    <div class="user-info">
                        <div class="user-name">{{ session('username') ?? 'Administrator' }}</div>
                        <div class="user-role">Super Admin</div>
                    </div>
                    <i class="fas fa-chevron-down" style="font-size: 12px;"></i>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <div class="navbar-menu">
            <ul class="nav-menu-items">
                <li class="nav-menu-item">
                    <a href="/admin/dashboard" class="nav-menu-link active">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-menu-item">
                    <a href="/admin/games" class="nav-menu-link">
                        <i class="nav-icon fas fa-gamepad"></i>
                        <span>Games</span>
                    </a>
                </li>
                <li class="nav-menu-item">
                    <a href="/admin/users" class="nav-menu-link">
                        <i class="nav-icon fas fa-users"></i>
                        <span>Users</span>
                    </a>
                </li>
                <li class="nav-menu-item">
                    <a href="/admin/analytics" class="nav-menu-link">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <span>Analytics</span>
                    </a>
                </li>
                <li class="nav-menu-item">
                    <a href="/admin/profile" class="nav-menu-link">
                        <i class="nav-icon fas fa-user"></i>
                        <span>Profile</span>
                    </a>
                </li>

            </ul>
        </div>
    </nav>

    <main style="padding: 20px">
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

            // User menu dropdown functionality
            const userMenu = document.querySelector('.user-menu');
            userMenu.addEventListener('click', function() {
                // Add your dropdown logic here
                console.log('User menu clicked');
            });
        });
    </script>
</body>

</html>
