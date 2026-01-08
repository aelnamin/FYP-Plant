@extends('layouts.admin-main')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid px-4 py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 class="h2 fw-bold text-gray-900 mb-1">Dashboard</h1>
                <p class="text-gray-600 mb-0">Welcome back, Aether & Leaf.Co! Platform overview and insights.</p>
            </div>
            <div class="d-flex gap-2">
                <span class="badge bg-light text-gray-700 px-3 py-2">
                    <i class="fas fa-calendar-alt me-1"></i>
                    {{ \Carbon\Carbon::now('Asia/Kuala_Lumpur')->format('d M Y') }}
                </span>
                <span class="badge bg-light text-gray-700 px-3 py-2">
                    <i class="fas fa-clock me-1"></i>
                    {{ \Carbon\Carbon::now('Asia/Kuala_Lumpur')->format('g:i A') }}
                </span>
            </div>
        </div>

        <!-- Stats -->
        <div class="row g-4 mb-5">
            <!-- Total Users -->
            <div class="col-xl-3 col-lg-6">
                <div class="card border-0 shadow-sm hover-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-gray-600 mb-1">Total Users</p>
                                <h2 class="fw-bold mb-2">{{ $totalUsers }}</h2>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-primary-100 text-primary-700 px-2 py-1 me-2">
                                        <i class="fas fa-user-check me-1"></i>
                                        {{ $activeUsers ?? '0' }} Active
                                    </span>
                                    <span class="badge bg-warning-100 text-warning-700 px-2 py-1">
                                        <i class="fas fa-user-clock me-1"></i>
                                        {{ $newUsersToday ?? '0' }} Today
                                    </span>
                                </div>
                            </div>
                            <div class="bg-primary-50 rounded-circle p-3">
                                <i class="fas fa-users text-primary-600" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Sellers -->
            <div class="col-xl-3 col-lg-6">
                <div class="card border-0 shadow-sm hover-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-gray-600 mb-1">Total Sellers</p>
                                <h2 class="fw-bold mb-2">{{ $totalSellers ?? '0' }}</h2>
                                <div class="text-success">
                                    <i class="fas fa-user-check me-1"></i>
                                    {{ $activeSellers ?? '0' }} active
                                </div>
                            </div>
                            <div class="bg-success-50 rounded-circle p-3">
                                <i class="fas fa-store text-success" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Products -->
            <div class="col-xl-3 col-lg-6">
                <div class="card border-0 shadow-sm hover-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
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
                            <div class="bg-info-50 rounded-circle p-3">
                                <i class="fas fa-boxes text-info" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Actions -->
            <div class="col-xl-3 col-lg-6">
                <div class="card border-0 shadow-sm hover-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-gray-600 mb-1">Pending Actions</p>
                                <h2 class="fw-bold text-warning mb-2">{{ $pendingSellers ?? '0' }}</h2>
                                <div class="text-warning">
                                    <i class="fas fa-store-alt me-1"></i>
                                    {{ $pendingSellers }} sellers to approve
                                </div>
                            </div>
                            <div class="bg-warning-50 rounded-circle p-3">
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
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0 p-4">
                        <h5 class="fw-semibold mb-1">Order Status</h5>
                        <p class="text-gray-600 small mb-0">Platform-wide overview</p>
                    </div>
                    <div class="card-body p-4 pt-0">
                        <div class="row g-3">
                            <div class="col-md-3 col-6">
                                <div class="text-center p-4 bg-warning-soft rounded-3">
                                    <i class="fas fa-clock text-warning fs-3 mb-2"></i>
                                    <div class="h4 fw-bold text-warning mb-1">{{ $pendingOrders ?? '0' }}</div>
                                    <div class="text-gray-700">Pending</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="text-center p-4 bg-success-soft rounded-3">
                                    <i class="fas fa-check-circle text-success fs-3 mb-2"></i>
                                    <div class="h4 fw-bold text-success mb-1">{{ $paidOrders ?? '0' }}</div>
                                    <div class="text-gray-700">Paid</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="text-center p-4 bg-info-soft rounded-3">
                                    <i class="fas fa-shipping-fast text-info fs-3 mb-2"></i>
                                    <div class="h4 fw-bold text-info mb-1">{{ $shippedOrders ?? '0' }}</div>
                                    <div class="text-gray-700">Shipped</div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="text-center p-4 bg-danger-soft rounded-3">
                                    <i class="fas fa-times-circle text-danger fs-3 mb-2"></i>
                                    <div class="h4 fw-bold text-danger mb-1">{{ $cancelledOrders ?? '0' }}</div>
                                    <div class="text-gray-700">Cancelled</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Actions -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0 p-4">
                        <h5 class="fw-semibold mb-1">Pending Actions</h5>
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
                            <a href="{{ route('admin.complaints.index', ['status' => 'open']) }}"
                                class="list-group-item list-group-item-action border-0 px-0 py-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                                    <span>Open Complaints</span>
                                </div>
                                <span class="badge bg-danger rounded-pill">{{ $openComplaints }}</span>
                            </a>
                            <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}"
                                class="list-group-item list-group-item-action border-0 px-0 py-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-shopping-cart text-primary me-2"></i>
                                    <span>Pending Orders</span>
                                </div>
                                <span class="badge bg-primary-600 rounded-pill">{{ $pendingOrders ?? '0' }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Products -->
        <div class="row g-4 mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0 p-4">
                        <div class="d-flex justify-content-between align-items-center">
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
                                <div class="col-md-6 col-lg-4 col-xl-3">
                                    <a href="{{ route('admin.products.show', $p->id) }}" class="text-decoration-none text-dark">
                                        <div class="card border hover-card h-100">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-start gap-3">
                                                    <img src="{{ $p->images->first() ? asset('images/' . $p->images->first()->image_path) : asset('images/default.jpg') }}"
                                                        class="rounded-3" style="width: 60px; height: 60px; object-fit: cover;">
                                                    <div class="flex-grow-1">
                                                        <div class="fw-semibold mb-1">{{ Str::limit($p->product_name, 25) }}
                                                        </div>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <small class="text-gray-600">
                                                                {{ $p->seller->business_name ?? 'No Seller' }}
                                                            </small>
                                                            <span class="text-success fw-semibold">
                                                                RM {{ number_format($p->price, 2) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        @if($p->status == 'pending')
                                                            <span class="badge bg-warning">Pending</span>
                                                        @elseif($p->status == 'approved')
                                                            <span class="badge bg-success">Approved</span>
                                                        @else
                                                            <span class="badge bg-secondary">{{ ucfirst($p->status) }}</span>
                                                        @endif
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
        .card {
            border-radius: 12px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
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
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Simple hover effects
            const cards = document.querySelectorAll('.hover-card');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function () {
                    this.style.zIndex = '10';
                });
                card.addEventListener('mouseleave', function () {
                    this.style.zIndex = '1';
                });
            });
        });
    </script>
@endsection