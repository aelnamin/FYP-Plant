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
    }

    body {
        background-color: #F8F9F5;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }

    .sidebar {
        background: var(--sidebar-bg);
        min-height: 100vh;
        border-right: 1px solid var(--sidebar-border);
        padding: 1.5rem;
        position: sticky;
        top: 0;
        transition: all 0.3s ease;
    }

    .sidebar-header {
        text-align: center;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--sidebar-border);
    }

    .profile-avatar {
        width: 140px;
        height: 140px;
        object-fit: cover;
        border: 4px solid var(--sidebar-accent);
        box-shadow: 0 6px 18px rgba(92, 127, 81, 0.15);
        transition: all 0.3s ease;
        display: block;
        margin: 0 auto;
    }

    .profile-avatar:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 16px rgba(92, 127, 81, 0.25);
    }

    .seller-name {
        color: var(--sidebar-accent);
        font-weight: 700;
        font-size: 1.1rem;
        margin-top: 1rem;
        letter-spacing: -0.025em;
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
    }

    @media (max-width: 768px) {
        .sidebar {
            position: fixed;
            z-index: 1000;
            transform: translateX(-100%);
        }

        .sidebar.active {
            transform: translateX(0);
        }

        .main-content {
            padding: 1rem;
        }
    }
</style>

<body>
    <div class="container-fluid g-0">
        <div class="row g-0">
            {{-- SIDEBAR --}}
            <aside class="col-md-2 col-lg-2 sidebar d-none d-md-block">
                <div class="sidebar-header">
                    <img src="{{ Auth::user()->profile_picture
    ? asset(Auth::user()->profile_picture)
    : asset('images/default.png') }}" class="rounded-circle profile-avatar" alt="Profile Picture">

                    <h4 class="seller-name mt-3 mb-0">
                        {{ Auth::user()->sellerProfile->business_name ?? Auth::user()->name }}
                    </h4>
                    <small class="text-muted d-block mt-1">Seller Account</small>
                </div>

                <nav class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('sellers.dashboard') }}"
                            class="nav-link {{ request()->routeIs('sellers.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-line"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('sellers.inventory.index') }}"
                            class="nav-link {{ request()->routeIs('sellers.inventory.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-box-open"></i>
                            <span>Manage Products</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('sellers.orders.index') }}"
                            class="nav-link {{ request()->routeIs('sellers.orders.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-shopping-bag"></i>
                            <span>My Orders</span>

                            @if($notShippedOrdersCount > 0)
                                <span class="badge bg-warning ms-auto">
                                    {{ $notShippedOrdersCount }}
                                </span>
                            @endif
                        </a>
                    </li>


                    <li class="nav-item">
                        <a href="{{ route('sellers.profile') }}"
                            class="nav-link {{ request()->routeIs('sellers.profile') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-cog"></i>
                            <span>My Profile</span>
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

            {{-- MOBILE MENU TOGGLE (Hidden on desktop) --}}
            <div class="d-md-none fixed-top p-3 bg-white shadow-sm">
                <button class="btn btn-outline-success" type="button" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <span class="ms-3 fw-semibold">Seller Dashboard</span>
            </div>

            {{-- MAIN CONTENT --}}
            <main class="col-md-10 col-lg-10 main-content">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function () {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.querySelector('.sidebar');

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function () {
                    sidebar.classList.toggle('active');
                });
            }

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function (event) {
                if (window.innerWidth < 768) {
                    if (!sidebar.contains(event.target) &&
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
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });

            // Add pending orders count (example - you'll need to implement the actual count)
            // fetch('/api/seller/pending-orders-count')
            //     .then(response => response.json())
            //     .then(data => {
            //         const badge = document.getElementById('pending-orders-count');
            //         if (badge && data.count > 0) {
            //             badge.textContent = data.count;
            //         }
            //     });
        });

        // Add smooth transitions
        document.documentElement.style.scrollBehavior = 'smooth';
    </script>

    @yield('scripts')
</body>

</html>