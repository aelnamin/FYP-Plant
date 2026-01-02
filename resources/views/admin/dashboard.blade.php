@extends('layouts.admin-main')

@section('title', 'Dashboard')

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #5C7F51;
            --secondary-color: #4a6c7d;
            --success-color: #2a9d8f;
            --warning-color: #e9c46a;
            --danger-color: #e76f51;
            --hover-lift: translateY(-2px);
            --transition-speed: 0.2s;
            --border-radius: 12px;
        }

        .hover-lift {
            transition: all var(--transition-speed) ease;
            border: 1px solid transparent;
        }

        .hover-lift:hover {
            transform: var(--hover-lift);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08) !important;
            border-color: rgba(92, 127, 81, 0.2);
        }

        .card {
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: var(--border-radius);
            overflow: hidden;
        }

        .bg-primary-opacity {
            background-color: rgba(92, 127, 81, 0.1);
        }

        .bg-success-opacity {
            background-color: rgba(42, 157, 143, 0.1);
        }

        .bg-warning-opacity {
            background-color: rgba(233, 196, 106, 0.1);
        }

        .bg-info-opacity {
            background-color: rgba(66, 153, 225, 0.1);
        }

        .badge {
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        .table th {
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6c757d;
            border-bottom: 2px solid #dee2e6 !important;
        }

        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: rgba(92, 127, 81, 0.05);
        }

        .btn {
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-outline-success {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .btn-outline-success:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .btn-success {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-success:hover {
            background-color: #4a6c42;
            border-color: #4a6c42;
        }

        .dropdown-menu {
            border: 1px solid rgba(0, 0, 0, 0.1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .dropdown-item {
            border-radius: 6px;
            margin: 2px;
        }

        .dropdown-item:hover {
            background-color: rgba(92, 127, 81, 0.1);
        }

        .display-1 {
            opacity: 0.3;
        }

        .stat-icon-container {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .module-card {
            border-radius: 10px;
            border: 1px solid #eef2f7;
            transition: all 0.2s ease;
            padding: 1.25rem;
            height: 100%;
        }

        .module-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(92, 127, 81, 0.1);
        }

        .module-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .scroll-container {
            max-height: 320px;
            overflow-y: auto;
            padding-right: 0.5rem;
        }

        .scroll-container::-webkit-scrollbar {
            width: 5px;
        }

        .scroll-container::-webkit-scrollbar-track {
            background: #f8f9fa;
            border-radius: 10px;
        }

        .scroll-container::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 10px;
        }

        @media (max-width: 768px) {
            .h2 {
                font-size: 1.5rem;
            }

            .h5 {
                font-size: 1.1rem;
            }

            .table-responsive {
                font-size: 0.9rem;
            }

            .card-body {
                padding: 1rem !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid px-lg-4">

        {{-- PAGE HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1" style="color: var(--primary-color);">Admin Dashboard</h2>
                <p class="text-muted mb-0">Welcome back, Administrator! Platform overview and insights.</p>
            </div>
            <div class="d-flex align-items-center">
            <span class="badge bg-light text-dark">
    <i class="fas fa-calendar-alt me-1"></i>
    {{ \Carbon\Carbon::now('Asia/Kuala_Lumpur')->format('d M Y') }}
</span>


                <span class="badge bg-light text-dark">
                <i class="fas fa-clock me-1"></i>
                {{ \Carbon\Carbon::now('Asia/Kuala_Lumpur')->format('g:i A') }}
                </span>

            </div>
        </div>

        {{-- 1. KEY METRICS --}}
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 hover-lift">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-muted mb-1">Total Users</div>
                                <div class="h2 fw-bold mb-2">{{ $totalUsers }}</div>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-primary-opacity text-primary px-2 py-1 me-2">
                                        <i class="fas fa-user-check me-1"></i>
                                        {{ $activeUsers ?? 'N/A' }} Active
                                    </span>
                                    <span class="badge bg-warning-opacity text-warning px-2 py-1">
                                        <i class="fas fa-user-clock me-1"></i>
                                        {{ $newUsersToday ?? '0' }} Today
                                    </span>
                                </div>
                            </div>
                            <div class="stat-icon-container bg-primary-opacity">
                                <i class="fas fa-users text-primary" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <a href="{{ route('admin.users.index') }}" class="stretched-link"></a>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 hover-lift">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                            <div class="text-muted mb-1">Total Sellers</div>
                            <div class="h2 fw-bold mb-2">{{ $totalSellers ?? 0 }}</div>
                            <div class="text-success small">
                            <i class="fas fa-user-check me-1"></i>
                            {{ $activeSellers ?? 0 }} active
                           </div>
                            </div>
                            <div class="stat-icon-container bg-success-opacity">
                                <i class="fas fa-store text-success" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <a href="{{ route('admin.orders.index', ['status' => 'paid']) }}" class="stretched-link"></a>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 hover-lift">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-muted mb-1">Total Products</div>
                                <div class="h2 fw-bold mb-2">{{ $totalProducts }}</div>
                                @if(($pendingProducts ?? 0) > 0)
                                    <div class="text-warning small">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $pendingProducts }} pending approval
                                    </div>
                                @else
                                    <div class="text-success small">
                                        <i class="fas fa-check-circle me-1"></i>
                                        All products approved
                                    </div>
                                @endif
                            </div>
                            <div class="stat-icon-container bg-info-opacity">
                                <i class="fas fa-boxes text-info" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <a href="{{ route('admin.products.index') }}" class="stretched-link"></a>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 hover-lift">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-muted mb-1">Pending Actions</div>
                                <div class="h2 fw-bold mb-2 text-warning">{{ $pendingSellers ?? 0 }}</div>
                                <div class="text-warning small">
                                    <i class="fas fa-store-alt me-1"></i>
                                    {{ $pendingSellers }} sellers to approve
                                </div>
                            </div>
                            <div class="stat-icon-container bg-warning-opacity">
                                <i class="fas fa-bell text-warning" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                        <a href="{{ route('admin.sellers.index') }}" class="stretched-link"></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            {{-- LEFT COLUMN --}}
            <div class="col-lg-8">
                {{-- 2. RECENT ORDERS --}}
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0 p-4 pb-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">Recent Orders</h5>
                                <p class="text-muted small mb-0">Latest platform transactions</p>
                            </div>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-list me-1"></i> View All
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-4 pt-0">
                        @if(!empty($recentOrders) && $recentOrders->count())
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr class="bg-light">
                                            <th class="border-0" style="width: 15%">Order ID</th>
                                            <th class="border-0" style="width: 20%">Customer</th>
                                            <th class="border-0" style="width: 20%">Seller</th>
                                            <th class="border-0" style="width: 15%">Status</th>
                                            <th class="border-0" style="width: 15%">Amount</th>
                                            <th class="border-0 text-end" style="width: 15%">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentOrders as $order)
                                            <tr class="border-bottom">
                                                <td>
                                                    <div class="fw-semibold">#{{ $order->id }}</div>
                                                    <small class="text-muted">{{ $order->created_at->format('M j') }}</small>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-light rounded-circle p-2 me-2">
                                                            <i class="fas fa-user text-muted"></i>
                                                        </div>
                                                        <div>
                                                            <div class="fw-medium">{{ $order->user->name ?? 'Customer' }}</div>
                                                            <small
                                                                class="text-muted">{{ Str::limit($order->user->email ?? '', 15) }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="fw-medium">{{ $order->seller->business_name ?? 'N/A' }}</div>
                                                </td>
                                                <td>
                                                    @php
                                                        $statusColors = [
                                                            'pending' => 'warning',
                                                            'paid' => 'primary',
                                                            'shipped' => 'info',
                                                            'delivered' => 'success',
                                                            'cancelled' => 'danger'
                                                        ];
                                                        $statusColor = $statusColors[$order->status] ?? 'secondary';
                                                    @endphp
                                                    <span
                                                        class="badge bg-{{ $statusColor }}-opacity text-{{ $statusColor }} px-3 py-1">
                                                        {{ ucfirst($order->status) }}
                                                    </span>
                                                </td>
                                                <td class="fw-bold">RM {{ number_format($order->total_amount ?? 0, 2) }}</td>
                                                <td class="text-end">
                                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                                        class="btn btn-light btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="display-1 text-muted mb-3">
                                    <i class="fas fa-clipboard-list"></i>
                                </div>
                                <h5 class="text-muted mb-3">No Recent Orders</h5>
                                <p class="text-muted">Orders will appear here when they are placed.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN --}}
            <div class="col-lg-4">
                {{-- 3. ORDER STATUS SUMMARY --}}
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent border-0 p-4 pb-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">Order Status</h5>
                                <p class="text-muted small mb-0">Platform-wide overview</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4 pt-0">
                        <div class="row g-3">
                            <div class="col-12">
                                <div
                                    class="status-badge bg-warning-opacity text-warning d-flex justify-content-between align-items-center p-3">
                                    <div>
                                        <i class="fas fa-clock me-2"></i>
                                        <span>Pending</span>
                                    </div>
                                    <span class="fw-bold h5 mb-0">{{ $pendingOrders ?? '0' }}</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div
                                    class="status-badge bg-success-opacity text-success d-flex justify-content-between align-items-center p-3">
                                    <div>
                                        <i class="fas fa-check-circle me-2"></i>
                                        <span>Paid</span>
                                    </div>
                                    <span class="fw-bold h5 mb-0">{{ $paidOrders ?? '0' }}</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div
                                    class="status-badge bg-danger-opacity text-danger d-flex justify-content-between align-items-center p-3">
                                    <div>
                                        <i class="fas fa-times-circle me-2"></i>
                                        <span>Cancelled</span>
                                    </div>
                                    <span class="fw-bold h5 mb-0">{{ $cancelledOrders ?? '0' }}</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div
                                    class="status-badge bg-info-opacity text-info d-flex justify-content-between align-items-center p-3">
                                    <div>
                                        <i class="fas fa-shipping-fast me-2"></i>
                                        <span>Shipped</span>
                                    </div>
                                    <span class="fw-bold h5 mb-0">{{ $shippedOrders ?? '0' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. RECENT PRODUCTS --}}
        <div class="row g-4 mt-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0 p-4 pb-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">Recent Products</h5>
                                <p class="text-muted small mb-0">Newly added products</p>
                            </div>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-list me-1"></i> View All
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-4 pt-0">
                        <div class="scroll-container">
                            <div class="row g-3">
                                @foreach($recentProducts as $p)
                                    <div class="col-12">
                                        <a href="{{ route('admin.products.show', $p->id) }}"
                                            class="text-decoration-none text-dark">
                                            <div class="d-flex align-items-center p-3 border rounded-3 hover-lift">
                                                <img src="{{ $p->images->first() ? asset('images/' . $p->images->first()->image_path) : asset('images/default.jpg') }}"
                                                    class="rounded-3 me-3"
                                                    style="width: 60px; height: 60px; object-fit: cover;">
                                                <div class="flex-grow-1">
                                                    <div class="fw-medium mb-1">{{ Str::limit($p->product_name, 35) }}</div>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <small class="text-muted">
                                                            {{ $p->seller->business_name ?? 'No Seller' }}
                                                        </small>
                                                        <span class="text-success fw-semibold">
                                                            RM {{ number_format($p->price, 2) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="ms-3">
                                                    @if($p->status == 'pending')
                                                        <span class="badge bg-warning">Pending</span>
                                                    @elseif($p->status == 'approved')
                                                        <span class="badge bg-success">Approved</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ ucfirst($p->status) }}</span>
                                                    @endif
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

            {{-- RIGHT COLUMN - PENDING ACTIONS --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-60">
                    <div class="card-header bg-transparent border-0 p-4 pb-2">
                        <h5 class="mb-1">Pending Actions</h5>
                        <p class="text-muted small mb-0">Requires attention</p>
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
                                <span class="badge bg-primary rounded-pill">{{ $pendingOrders ?? '0' }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

    @section('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Hover effects for cards
                document.querySelectorAll('.hover-lift').forEach(card => {
                    card.addEventListener('mouseenter', function () {
                        this.style.zIndex = '10';
                    });
                    card.addEventListener('mouseleave', function () {
                        this.style.zIndex = '1';
                    });
                });

                // Add loading animation to buttons
                document.querySelectorAll('a.btn').forEach(button => {
                    button.addEventListener('click', function (e) {
                        if (!this.classList.contains('dropdown-toggle')) {
                            const originalHTML = this.innerHTML;
                            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Loading...';
                            this.disabled = true;

                            // Reset after 2 seconds (in case of error)
                            setTimeout(() => {
                                this.innerHTML = originalHTML;
                                this.disabled = false;
                            }, 2000);
                        }
                    });
                });

                // Fade in animation
                const cards = document.querySelectorAll('.card');
                cards.forEach((card, index) => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, index * 100);
                });
            });
        </script>
    @endsection