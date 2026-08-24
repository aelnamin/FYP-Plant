@extends('layouts.admin-main')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid admin-dashboard px-0 py-2 py-md-3">
        <!-- Header -->
        <div class="dashboard-heading d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h1 class="h2 fw-bold text-gray-900 mb-1">Dashboard</h1>
                <p class="text-gray-600 mb-0">Welcome back, Aether & Leaf.Co! Platform overview and insights.</p>
            </div>
            <div class="dashboard-datetime d-flex flex-wrap gap-2">
                <span class="badge bg-white text-gray-700 px-3 py-2 shadow-sm">
                    <i class="fas fa-calendar-alt me-1"></i>
                    {{ \Carbon\Carbon::now('Asia/Kuala_Lumpur')->format('d M Y') }}
                </span>
                <span class="badge bg-white text-gray-700 px-3 py-2 shadow-sm">
                    <i class="fas fa-clock me-1"></i>
                    {{ \Carbon\Carbon::now('Asia/Kuala_Lumpur')->format('g:i A') }}
                </span>
            </div>
        </div>

        <!-- Stats -->
        <div class="row g-3 g-xl-4 mb-4 dashboard-stats">
            <!-- Total Users -->
            <div class="col-sm-6 col-xxl-3 d-flex">
                <div class="card border-0 shadow-sm hover-card stat-card w-100">
                    <div class="card-body p-3 p-xl-4">
                        <div class="d-flex justify-content-between align-items-start gap-3 h-100">
                            <div class="stat-card__content">
                                <p class="text-gray-600 mb-1">Total Users</p>
                                <h2 class="fw-bold mb-2">{{ $totalUsers }}</h2>
                                <div class="d-flex align-items-center">
                                    <span class="badge stat-card__detail bg-primary-100 text-primary-700 px-2 py-1">
                                        <i class="fas fa-user-check me-1"></i>
                                        {{ $totalBuyers }} buyers · {{ $totalSellerAccounts }} seller accounts
                                    </span>
                                </div>
                            </div>
                            <div class="stat-card__icon bg-primary-50 rounded-circle">
                                <i class="fas fa-users text-primary-600" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Sellers -->
            <div class="col-sm-6 col-xxl-3 d-flex">
                <div class="card border-0 shadow-sm hover-card stat-card w-100">
                    <div class="card-body p-3 p-xl-4">
                        <div class="d-flex justify-content-between align-items-start gap-3 h-100">
                            <div class="stat-card__content">
                                <p class="text-gray-600 mb-1">Total Sellers</p>
                                <h2 class="fw-bold mb-2">{{ $totalSellers ?? '0' }}</h2>
                                <div class="text-success">
                                    <i class="fas fa-user-check me-1"></i>
                                    Approved seller
                                </div>
                            </div>
                            <div class="stat-card__icon bg-success-50 rounded-circle">
                                <i class="fas fa-store text-success" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Products -->
            <div class="col-sm-6 col-xxl-3 d-flex">
                <div class="card border-0 shadow-sm hover-card stat-card w-100">
                    <div class="card-body p-3 p-xl-4">
                        <div class="d-flex justify-content-between align-items-start gap-3 h-100">
                            <div class="stat-card__content">
                                <p class="text-gray-600 mb-1">Total Products</p>
                                <h2 class="fw-bold mb-2">{{ $totalProducts }}</h2>
                                @if(($pendingProducts ?? 0) > 0)
                                    <div class="text-danger">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $pendingProducts }} pending approval
                                    </div>
                                @else
                                    <div class="text-success">
                                        <i class="fas fa-check-circle me-1"></i>
                                        All products approved
                                    </div>
                                @endif
                            </div>
                            <div class="stat-card__icon bg-info-50 rounded-circle">
                                <i class="fas fa-boxes text-info" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Sellers -->
            <div class="col-sm-6 col-xxl-3 d-flex">
                <div class="card border-0 shadow-sm hover-card stat-card w-100">
                    <div class="card-body p-3 p-xl-4">
                        <div class="d-flex justify-content-between align-items-start gap-3 h-100">
                            <div class="stat-card__content">
                                <p class="text-gray-600 mb-1">Pending Sellers</p>
                                <h2 class="fw-bold text-warning mb-2">{{ $pendingSellers ?? '0' }}</h2>
                                <div class="text-warning">
                                    <i class="fas fa-store-alt me-1"></i>
                                    {{ $pendingSellers }} sellers to approve
                                </div>
                            </div>
                            <div class="stat-card__icon bg-warning-50 rounded-circle">
                                <i class="fas fa-bell text-warning" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row g-4">
            <!-- Order Status -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0 p-4">
                        <h3 class="h5 fw-semibold mb-1">Order Fulfillment Status</h3>
                        <p class="text-gray-600 small mb-0">Each order is counted once at its current seller-fulfillment stage</p>
                    </div>
                    <div class="card-body p-4 pt-0">
                        <div class="row g-3">
                            <div class="col-md-3 col-6">
                                <div class="fulfillment-tile text-center p-3 p-xl-4 bg-warning-soft rounded-3 h-100">
                                    <i class="fas fa-clock text-warning fs-3 mb-2"></i>
                                    <div class="h4 fw-bold text-warning mb-1">{{ $pendingOrders ?? '0' }}</div>
                                    <div class="text-gray-700">Pending</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="fulfillment-tile text-center p-3 p-xl-4 bg-success-soft rounded-3 h-100">
                                    <i class="fas fa-check-circle text-success fs-3 mb-2"></i>
                                    <div class="h4 fw-bold text-success mb-1">{{ $paidOrders ?? '0' }}</div>
                                    <div class="text-gray-700">Paid</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="fulfillment-tile text-center p-3 p-xl-4 bg-info-soft rounded-3 h-100">
                                    <i class="fas fa-shipping-fast text-info fs-3 mb-2"></i>
                                    <div class="h4 fw-bold text-info mb-1">{{ $shippedOrders ?? '0' }}</div>
                                    <div class="text-gray-700">Shipped</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="fulfillment-tile text-center p-3 p-xl-4 bg-delivered-soft rounded-3 h-100">
                                    <i class="fas fa-box-open text-delivered-600 fs-3 mb-2"></i>
                                    <div class="h4 fw-bold text-delivered-600 mb-1">{{ $deliveredOrders ?? '0' }}</div>
                                    <div class="text-gray-700">Delivered / Completed</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Actions -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0 p-4">
                        <h4 class="h5 fw-semibold mb-1">Actions</h4>
                    </div>
                    <div class="card-body p-4 pt-0">
                        <div class="list-group list-group-flush">
                            <a href="{{ route('admin.products.index', ['status' => 'pending']) }}"
                                class="list-group-item list-group-item-action border-0 px-0 py-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-box text-warning me-2"></i>
                                    <span>Products Pending</span>
                                </div>
                                <span class="badge bg-warning rounded-pill">{{ $pendingProducts }}</span>
                            </a>
                            <a href="{{ route('admin.sellers.index', ['status' => 'pending']) }}"
                                class="list-group-item list-group-item-action border-0 px-0 py-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-store text-warning me-2"></i>
                                    <span>Sellers Pending</span>
                                </div>
                                <span class="badge bg-warning rounded-pill">{{ $pendingSellers }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Products -->
        <div class="row g-4 mt-1">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0 p-4">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div>
                                <h5 class="fw-semibold mb-1">Recent Products</h5>
                                <p class="text-gray-600 small mb-0">Newly added products</p>
                            </div>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-list me-1"></i> View All
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-4 pt-0">
                        <div class="row g-3">
                            @foreach($recentProducts as $p)
                                <div class="col-sm-6 col-xl-4 col-xxl-3 d-flex">
                                    <a href="{{ route('admin.products.show', $p->id) }}" class="text-decoration-none text-dark w-100">
                                        <div class="card recent-product-card border hover-card h-100">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-start gap-3 h-100">
                                                    <img src="{{ $p->images->first() ? asset('images/' . $p->images->first()->image_path) : asset('images/default.jpg') }}"
                                                        class="recent-product-card__image rounded-3" alt="{{ $p->product_name }}" width="72" height="72" loading="lazy">
                                                    <div class="recent-product-card__content d-flex flex-column flex-grow-1">
                                                        <div class="fw-semibold mb-1">{{ Str::limit($p->product_name, 25) }}
                                                        </div>
                                                        <small class="text-gray-600 d-block text-truncate mb-2">
                                                            {{ $p->seller->business_name ?? 'No Seller' }}
                                                        </small>
                                                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-auto">
                                                            <span class="text-success fw-semibold text-nowrap">
                                                                RM {{ number_format($p->price, 2) }}
                                                            </span>
                                                            @if(strtolower($p->approval_status) === 'approved')
                                                                <span class="badge bg-success">Approved</span>
                                                            @else
                                                                <span class="badge bg-secondary">{{ $p->approval_status }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
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
    </div>

    <style>
        :root {
            --color-primary-50: #f5f7f0;
            --color-primary-100: #e9edd9;
            --color-primary-600: #6e8055;
            --color-primary-700: #566546;

            --color-success-50: #f0f7f3;
            --color-success-100: #dcefe4;
            --color-success-soft: #e8f5eb;
            --color-success-700: #2d6b47;

            --color-warning-50: #fef9f0;
            --color-warning-100: #fef0d7;
            --color-warning-soft: #fff4e0;
            --color-warning-700: #9c6c1a;


            --color-delivered-50: #f5f0ff;
            --color-delivered-soft: rgb(219, 228, 246);
            --color-delivered-600: rgb(51, 44, 108);
            --color-delivered-700: rgb(34, 50, 120);



            --color-info-50: #f0f7fc;
            --color-info-100: #dcedf9;
            --color-info-soft: #e3f2fd;
            --color-info-700: #1a6094;

            --color-danger-50: #fdf2f2;
            --color-danger-100: #fde8e8;
            --color-danger-soft: #fdeaea;
            --color-danger-700: #9b1c1c;

            --color-gray-600: #6b7280;
            --color-gray-700: #374151;
            --color-gray-900: #111827;
        }

        /* Color Utilities */
        .text-primary-600 {
            color: var(--color-primary-600) !important;
        }

        .text-primary-700 {
            color: var(--color-primary-700) !important;
        }

        .bg-primary-50 {
            background-color: var(--color-primary-50) !important;
        }

        .bg-primary-100 {
            background-color: var(--color-primary-100) !important;
        }

        .bg-primary-600 {
            background-color: var(--color-primary-600) !important;
        }

        .bg-success-50 {
            background-color: var(--color-success-50) !important;
        }

        .bg-success-100 {
            background-color: var(--color-success-100) !important;
        }

        .bg-success-soft {
            background-color: var(--color-success-soft) !important;
        }

        .text-success-700 {
            color: var(--color-success-700) !important;
        }

        .bg-warning-50 {
            background-color: var(--color-warning-50) !important;
        }

        .bg-warning-100 {
            background-color: var(--color-warning-100) !important;
        }

        .bg-warning-soft {
            background-color: var(--color-warning-soft) !important;
        }

        .text-warning-700 {
            color: var(--color-warning-700) !important;
        }

        .bg-delivered-50 {
            background-color: var(--color-delivered-50) !important;
        }

        .bg-delivered-soft {
            background-color: var(--color-delivered-soft) !important;
        }

        .text-delivered-600 {
            color: var(--color-delivered-600) !important;
        }

        .text-delivered-700 {
            color: var(--color-delivered-700) !important;
        }

        .bg-info-50 {
            background-color: var(--color-info-50) !important;
        }

        .bg-info-100 {
            background-color: var(--color-info-100) !important;
        }

        .bg-info-soft {
            background-color: var(--color-info-soft) !important;
        }

        .text-info-700 {
            color: var(--color-info-700) !important;
        }

        .bg-danger-50 {
            background-color: var(--color-danger-50) !important;
        }

        .bg-danger-100 {
            background-color: var(--color-danger-100) !important;
        }

        .bg-danger-soft {
            background-color: var(--color-danger-soft) !important;
        }

        .text-danger-700 {
            color: var(--color-danger-700) !important;
        }

        .text-gray-600 {
            color: var(--color-gray-600) !important;
        }

        .text-gray-700 {
            color: var(--color-gray-700) !important;
        }

        .text-gray-900 {
            color: var(--color-gray-900) !important;
        }

        /* Components */
        .admin-dashboard {
            max-width: 1600px;
            margin-inline: auto;
        }

        .dashboard-heading h1 {
            letter-spacing: -0.025em;
        }

        .dashboard-datetime .badge {
            border: 1px solid rgba(107, 114, 128, 0.12);
            font-size: 0.78rem;
            font-weight: 600;
        }

        .card {
            border-radius: 12px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card {
            min-height: 166px;
        }

        .stat-card__content {
            min-width: 0;
        }

        .stat-card__detail {
            max-width: 100%;
            white-space: normal;
            line-height: 1.35;
            text-align: left;
        }

        .stat-card__icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 52px;
            height: 52px;
            flex: 0 0 52px;
        }

        .fulfillment-tile {
            min-height: 132px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .recent-product-card {
            min-width: 0;
        }

        .recent-product-card__image {
            width: 72px;
            height: 72px;
            flex: 0 0 72px;
            object-fit: cover;
        }

        .recent-product-card__content {
            min-width: 0;
        }

        .hover-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(110, 128, 85, 0.1) !important;
        }

        .rounded-3 {
            border-radius: 12px !important;
        }

        .btn-outline-primary {
            color: var(--color-primary-600);
            border-color: var(--color-primary-600);
        }

        .btn-outline-primary:hover {
            background-color: var(--color-primary-600);
            border-color: var(--color-primary-600);
            color: white;
        }

        .list-group-item:hover {
            background-color: var(--color-primary-50) !important;
        }

        .fs-3 {
            font-size: 1.5rem !important;
        }

        @media (max-width: 767.98px) {
            .dashboard-heading h1 {
                font-size: 1.65rem;
            }

            .dashboard-datetime {
                width: 100%;
            }

            .dashboard-datetime .badge {
                flex: 1 1 auto;
                text-align: center;
            }

            .stat-card {
                min-height: 150px;
            }

            .fulfillment-tile {
                min-height: 118px;
            }
        }

        @media (max-width: 374.98px) {
            .recent-product-card__image {
                width: 60px;
                height: 60px;
                flex-basis: 60px;
            }
        }
    </style>
@endsection
