@extends('layouts.admin-main')

@section('title', 'Dashboard')

@section('content')

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-color: #5C7F51;
            --primary-light: rgba(92, 127, 81, 0.1);
            --success-light: rgba(76, 175, 80, 0.1);
            --warning-light: rgba(255, 193, 7, 0.1);
            --danger-light: rgba(220, 53, 69, 0.1);
            --border-radius: 12px;
            --transition: all 0.25s ease;
        }

        .stat-card {
            background: white;
            border: none;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--primary-color);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #2c3e50;
            line-height: 1.2;
        }

        .stat-label {
            color: #6c757d;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .module-card {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            transition: var(--transition);
        }

        .module-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(92, 127, 81, 0.1);
        }

        .module-icon {
            width: 48px;
            height: 48px;
            background: var(--primary-light);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .status-card {
            border: none;
            border-radius: var(--border-radius);
            padding: 1.25rem;
            transition: var(--transition);
        }

        .status-card:hover {
            transform: translateY(-2px);
        }

        .pending-bg {
            background: var(--warning-light);
            color: #856404;
        }

        .paid-bg {
            background: var(--success-light);
            color: #155724;
        }

        .cancelled-bg {
            background: var(--danger-light);
            color: #721c24;
        }

        .product-card {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 0.75rem;
            transition: var(--transition);
        }

        .product-card:hover {
            background: #f8f9fa;
            border-color: var(--primary-light);
        }

        .section-title {
            color: #2c3e50;
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 1.25rem;
            position: relative;
            padding-bottom: 0.5rem;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 3px;
            background: var(--primary-color);
            border-radius: 2px;
        }

        .scroll-container {
            max-height: 280px;
            overflow-y: auto;
            padding-right: 0.5rem;
        }

        .scroll-container::-webkit-scrollbar {
            width: 4px;
        }

        .scroll-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .scroll-container::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        .scroll-container::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>

    <div class="container mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-semibold text-dark mb-1">Dashboard</h4>
                <p class="text-muted mb-0">Welcome back, Administrator</p>
            </div>
            <div class="text-muted small">
                Last updated: {{ now()->format('M d, Y') }}
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <a href="{{ route('admin.users.index') }}" class="text-decoration-none">
                    <div class="stat-card">
                        <div class="stat-label">Total Users</div>
                        <div class="stat-number">{{ $totalUsers }}</div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.products.index') }}" class="text-decoration-none">
                    <div class="stat-card">
                        <div class="stat-label">Total Products</div>
                        <div class="stat-number">{{ $totalProducts }}</div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.orders.index') }}" class="text-decoration-none">
                    <div class="stat-card">
                        <div class="stat-label">Total Orders</div>
                        <div class="stat-number">{{ $totalOrders ?? '0' }}</div>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.orders.index', ['status' => 'paid']) }}" class="text-decoration-none">
                    <div class="stat-card">
                        <div class="stat-label">Total Revenue</div>
                        <div class="stat-number">RM {{ number_format($totalRevenue ?? 0, 2) }}</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Order Status & Pending Items -->
        <div class="row g-4 mb-4">
            <!-- Order Status -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm p-3 h-100">
                    <h6 class="section-title">Order Status</h6>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="status-card pending-bg">
                                <div class="small mb-1">Pending</div>
                                <h4 class="mb-0">{{ $pendingOrders ?? '0' }}</h4>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="status-card paid-bg">
                                <div class="small mb-1">Paid</div>
                                <h4 class="mb-0">{{ $paidOrders ?? '0' }}</h4>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="status-card cancelled-bg">
                                <div class="small mb-1">Cancelled</div>
                                <h4 class="mb-0">{{ $cancelledOrders ?? '0' }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Items -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm p-3 h-100">
                    <h6 class="section-title">Pending Items</h6>
                    <div class="row g-2">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center p-2">
                                <span class="text-muted">Products</span>
                                <span class="fw-semibold">{{ $pendingProducts }}</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center p-2">
                                <span class="text-muted">Sellers</span>
                                <span class="fw-semibold">{{ $pendingSellers }}</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center p-2">
                                <span class="text-muted">Complaints</span>
                                <span class="fw-semibold">{{ $openComplaints }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Products -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="section-title mb-0">Recent Products</h6>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-dark">
                            View All →
                        </a>
                    </div>
                    <div class="scroll-container">
                        <div class="row g-2">
                            @foreach($recentProducts as $p)
                                <div class="col-12">
                                    <a href="{{ route('admin.products.show', $p->id) }}" class="text-decoration-none text-dark">
                                        <div class="product-card d-flex align-items-center">
                                            <img src="{{ $p->images->first() ? asset('images/' . $p->images->first()->image_path) : asset('images/default.jpg') }}"
                                                class="rounded me-3" style="width:60px; height:60px; object-fit:cover;">
                                            <div class="flex-grow-1">
                                                <div class="fw-medium mb-1">{{ Str::limit($p->product_name, 25) }}</div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted">
                                                        {{ $p->seller->business_name ?? 'No Seller' }}
                                                    </small>
                                                    <span class="text-success fw-semibold">
                                                        RM {{ number_format($p->price, 2) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Management Modules -->
        <div class="row mb-5">
            <div class="col-12">
                <h6 class="section-title">Management Modules</h6>
            </div>

            @php
                $modules = [
                    ['route' => 'admin.users.index', 'icon' => 'bi-people-fill', 'title' => 'User Management', 'desc' => 'Manage all users and accounts'],
                    ['route' => 'admin.products.index', 'icon' => 'bi-box-seam-fill', 'title' => 'Product Management', 'desc' => 'View and approve products'],
                    ['route' => 'admin.categories.index', 'icon' => 'bi-tags-fill', 'title' => 'Categories & Tags', 'desc' => 'Manage product categories'],
                    ['route' => 'admin.orders.index', 'icon' => 'bi-cart-check-fill', 'title' => 'Order Management', 'desc' => 'Process and track orders'],
                    ['route' => 'admin.payments.index', 'icon' => 'bi-credit-card-fill', 'title' => 'Payments', 'desc' => 'Monitor transactions'],
                    ['route' => 'admin.sellers.index', 'icon' => 'bi-shop-window', 'title' => 'Seller Management', 'desc' => 'Approve and manage sellers'],
                    ['route' => 'admin.analytics.index', 'icon' => 'bi-graph-up-arrow', 'title' => 'Analytics', 'desc' => 'View reports & insights'],
                    ['route' => 'admin.logs.index', 'icon' => 'bi-clipboard-data-fill', 'title' => 'System Logs', 'desc' => 'Activity and audit logs'],
                ];
            @endphp

            @foreach($modules as $module)
                    @php
                        $routeExists = Route::has($module['route']);
                    @endphp

                    <div class="col-md-6 col-lg-4 col-xl-3 mb-3">
                        @if($routeExists)
                            <a href="{{ route($module['route']) }}" class="text-decoration-none text-dark">
                        @else
                                <div class="text-decoration-none text-muted" style="cursor: not-allowed;">
                            @endif
                                <div class="module-card h-100 @if(!$routeExists) opacity-50 @endif">
                                    <div class="module-icon">
                                        <i class="bi {{ $module['icon'] }}"></i>
                                    </div>
                                    <h6 class="fw-semibold mb-2">{{ $module['title'] }}</h6>
                                    <p class="text-muted small mb-0">{{ $module['desc'] }}</p>
                                </div>
                                @if($routeExists)
                                    </a>
                                @else
                            </div>
                        @endif
                </div>
            @endforeach
    </div>

    </div>
@endsection

@section('scripts')
    <!-- Optional: Chart.js if needed -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Simple fade-in animation for cards
        document.addEventListener('DOMContentLoaded', function () {
            const cards = document.querySelectorAll('.stat-card, .module-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 50);
            });
        });
    </script>
@endsection