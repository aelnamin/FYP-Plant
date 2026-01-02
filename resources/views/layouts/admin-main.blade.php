<!DOCTYPE html>
<html lang="en">

<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<style>
    :root {
        --sidebar-bg: #ffffff;
        --sidebar-accent: #5C7F51;
        --sidebar-text: #1F2937;
        --sidebar-hover: #F3F4F6;
        --sidebar-active: #ECF7E7;
        --sidebar-border: #E5E7EB;
        --admin-badge: #DC3545;
    }

    body {
        background-color: #F8F9F5;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }

    .admin-sidebar {
        background: var(--sidebar-bg);
        min-height: 100vh;
        border-right: 1px solid var(--sidebar-border);
        padding: 1.5rem;
        position: sticky;
        top: 0;
        transition: all 0.3s ease;
    }

    .admin-header {
        text-align: center;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--sidebar-border);
    }

    .admin-avatar {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border: 4px solid var(--sidebar-accent);
        box-shadow: 0 4px 12px rgba(92, 127, 81, 0.15);
        display: block;
        margin: 0 auto;
        position: relative;
    }

    .admin-badge {
        position: absolute;
        top: 0;
        right: 10px;
        background: var(--admin-badge);
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        border: 2px solid white;
    }

    .admin-name {
        color: var(--sidebar-accent);
        font-weight: 700;
        font-size: 1rem;
        margin-top: 1rem;
        letter-spacing: -0.025em;
    }

    .admin-role {
        background: linear-gradient(135deg, var(--sidebar-accent) 0%, #4a6c42 100%);
        color: white;
        padding: 0.25rem 1rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
        margin-top: 0.5rem;
    }

    .nav-item {
        margin-bottom: 0.5rem;
    }

    .nav-link {
        color: var(--sidebar-text);
        padding: 0.75rem 1rem;
        border-radius: 0.75rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
    }

    .nav-link::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 3px;
        background: var(--sidebar-accent);
        transform: translateX(-100%);
        transition: transform 0.3s ease;
    }

    .nav-link:hover {
        background-color: var(--sidebar-hover);
        color: var(--sidebar-accent);
        transform: translateX(4px);
    }

    .nav-link:hover::before {
        transform: translateX(0);
    }

    .nav-link.active {
        background-color: var(--sidebar-active);
        color: var(--sidebar-accent);
        font-weight: 600;
    }

    .nav-link.active::before {
        transform: translateX(0);
    }

    .nav-icon {
        width: 20px;
        text-align: center;
        font-size: 1.1rem;
    }

    .nav-badge {
        background: var(--sidebar-accent);
        color: white;
        font-size: 0.7rem;
        padding: 0.2rem 0.5rem;
        border-radius: 10px;
        margin-left: auto;
    }

    .section-divider {
        color: #9CA3AF;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 1.5rem 0 0.75rem 1rem;
    }

    .logout-btn {
        background: transparent;
        color: #6B7280;
        border: 1px solid #E5E7EB;
        padding: 0.75rem 1rem;
        border-radius: 0.75rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        width: 100%;
        transition: all 0.2s ease;
        margin-top: 1rem;
    }

    .logout-btn:hover {
        background-color: #FEF2F2;
        color: #DC2626;
        border-color: #FCA5A5;
        transform: translateY(-1px);
    }

    .main-content {
        padding: 2rem;
        background-color: #F8F9F5;
        min-height: 100vh;
    }

    .mobile-admin-header {
        background: white;
        border-bottom: 1px solid var(--sidebar-border);
        padding: 1rem;
        position: sticky;
        top: 0;
        z-index: 100;
    }

    @media (max-width: 768px) {
        .admin-sidebar {
            position: fixed;
            z-index: 1000;
            transform: translateX(-100%);
            width: 280px;
        }

        .admin-sidebar.active {
            transform: translateX(0);
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
        }

        .main-content {
            padding: 1rem;
            padding-top: 5rem;
        }
    }


    @keyframes pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.1);
        }

        100% {
            transform: scale(1);
        }
    }

    .notification-pulse {
        animation: pulse 2s infinite;
    }
</style>

<body>
    <div class="container-fluid g-0">
        <div class="row g-0">
            {{-- MOBILE HEADER --}}
            <div class="d-md-none mobile-admin-header">
                <div class="d-flex align-items-center justify-content-between">
                    <button class="btn btn-outline-success" type="button" id="adminSidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="text-center">
                        <h5 class="mb-0" style="color: var(--sidebar-accent);">
                            <i class="fas fa-shield-alt me-2"></i>Admin Panel
                        </h5>
                    </div>
                    <div class="position-relative">
                        <i class="fas fa-bell text-muted" style="font-size: 1.2rem;"></i>
                        @if($pendingCount ?? 0 > 0)
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-pulse">
                                {{ min($pendingCount, 9) }}{{ $pendingCount > 9 ? '+' : '' }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ADMIN SIDEBAR --}}
            <aside class="col-md-3 col-lg-2 admin-sidebar d-none d-md-block">
                <div class="admin-header">
                    <h4 class="admin-name mt-3 mb-0">
                        {{ Auth::user()->name }}
                    </h4>
                </div>

                <nav class="nav flex-column">
                    <div class="section-divider">Dashboard</div>
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}"
                            class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-pie"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <div class="section-divider">User Management</div>
                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}"
                            class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <span>Manage Users</span>
                            @if(($newUsersToday ?? 0) > 0)
                                <span class="nav-badge">{{ $newUsersToday }}</span>
                            @endif
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.sellers.index') }}"
                            class="nav-link {{ request()->routeIs('admin.sellers.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-store"></i>
                            <span>Manage Sellers</span>
                            @if(($pendingSellers ?? 0) > 0)
                                <span class="nav-badge notification-pulse">{{ $pendingSellers }}</span>
                            @endif
                        </a>
                    </li>

                    <div class="section-divider">Product Management</div>
                    <li class="nav-item">
                        <a href="{{ route('admin.products.index') }}"
                            class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-boxes"></i>
                            <span>Manage Products</span>
                            @if(($pendingProducts ?? 0) > 0)
                                <span class="nav-badge notification-pulse">{{ $pendingProducts }}</span>
                            @endif
                        </a>
                    </li>

                    <div class="section-divider">Order Management</div>
                    <li class="nav-item">
                        <a href="{{ route('admin.orders.index') }}"
                            class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-shopping-cart"></i>
                            <span>Orders</span>
                            @if(($pendingOrders ?? 0) > 0)
                                <span class="nav-badge">{{ $pendingOrders }}</span>
                            @endif
                        </a>
                    </li>

                    <div class="section-divider">System</div>
                    <li class="nav-item">
                        <a href="{{ route('admin.reports.index') }}"
                            class="nav-link {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <span>Analytics</span>
                        </a>
                    </li>

                    <li class="nav-item mt-4">
                        <form action="{{ route('logout') }}" method="POST" class="w-100">
                            @csrf
                            <button type="submit" class="logout-btn">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Log Out</span>
                            </button>
                        </form>
                    </li>
                </nav>
            </aside>

            {{-- MAIN CONTENT --}}
            <main class="col-md-9 col-lg-10 main-content">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function () {
            const sidebarToggle = document.getElementById('adminSidebarToggle');
            const sidebar = document.querySelector('.admin-sidebar');

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function () {
                    sidebar.classList.toggle('active');
                });
            }

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function (event) {
                if (window.innerWidth < 768) {
                    if (sidebar &&
                        !sidebar.contains(event.target) &&
                        sidebarToggle &&
                        !sidebarToggle.contains(event.target) &&
                        sidebar.classList.contains('active')) {
                        sidebar.classList.remove('active');
                    }
                }
            });

            // Update active link based on current URL
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link');

            navLinks.forEach(link => {
                if (currentPath.includes(link.getAttribute('href'))) {
                    link.classList.add('active');
                }
            });

            // Add smooth transitions
            document.documentElement.style.scrollBehavior = 'smooth';

            // Mobile sidebar auto-close on link click
            if (window.innerWidth < 768) {
                document.querySelectorAll('.nav-link').forEach(link => {
                    link.addEventListener('click', () => {
                        sidebar.classList.remove('active');
                    });
                });
            }
        });

        // Resize handler
        window.addEventListener('resize', function () {
            const sidebar = document.querySelector('.admin-sidebar');
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('active');
            }
        });
    </script>

    @yield('scripts')
</body>

</html>