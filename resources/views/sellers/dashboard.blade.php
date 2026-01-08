@extends('layouts.sellers-main')

@section('title', 'Seller Dashboard')

@section('styles')


    <style>
        :root {
            --primary-color: #2a9d8f;
            --success-color: #2a9d8f;
            --hover-lift: translateY(-2px);
            --transition-speed: 0.2s;
        }

        .hover-lift {
            transition: all var(--transition-speed) ease;
            border: 1px solid transparent;
        }

        .hover-lift:hover {
            transform: var(--hover-lift);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
            border-color: rgba(42, 157, 143, 0.2);
        }

        .card {
            border: 1px solid rgba(0, 0, 0, 0.05);
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
            background-color: rgba(42, 157, 143, 0.05);
        }

        .btn {
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-outline-success {
            border-color: var(--success-color);
            color: var(--success-color);
        }

        .btn-outline-success:hover {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }

        .list-group-item {
            transition: background-color 0.2s ease;
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
            background-color: rgba(42, 157, 143, 0.1);
        }

        .display-1 {
            opacity: 0.3;
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
                <h2 class="fw-bold mb-1" style="color: #2a9d8f;">Dashboard Overview</h2>
                <p class="text-muted mb-0">Welcome back, {{ $seller->business_name ?? 'Seller' }}! Here's your business
                    summary.</p>
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
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden h-100 hover-lift">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-muted mb-1">Total Orders</div>
                                <div class="h2 fw-bold mb-2">{{ $total_orders ?? 0 }}</div>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1 me-2">
                                        <i class="fas fa-check-circle me-1"></i>
                                        {{ $paid_orders ?? 0 }} Paid
                                    </span>
                                    <span class="badge bg-warning bg-opacity-10 text-warning px-2 py-1">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $pending_orders ?? 0 }} Pending
                                    </span>
                                </div>
                            </div>
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-shopping-bag text-primary" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden h-100 hover-lift">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-muted mb-1">Total Revenue</div>
                                <div class="h2 fw-bold mb-2" style="color: #2a9d8f;">
                                    RM {{ number_format($total_revenue ?? 0, 2) }}
                                </div>
                                <div class="text-success small">
                                    <i class="fas fa-arrow-up me-1"></i>
                                    RM {{ number_format($month_revenue ?? 0, 2) }} this month
                                </div>
                            </div>
                            <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-chart-line text-success" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden h-100 hover-lift">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-muted mb-1">Total Products</div>
                                <div class="h2 fw-bold mb-2">{{ $total_products ?? 0 }}</div>
                                @if(($low_stock_count ?? 0) > 0)
                                    <div class="text-danger small">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        {{ $low_stock_count }} low stock items
                                    </div>
                                @else
                                    <div class="text-success small">
                                        <i class="fas fa-check-circle me-1"></i>
                                        Stock levels good
                                    </div>
                                @endif
                            </div>
                            <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-boxes text-info" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm rounded-3 overflow-hidden h-100 hover-lift">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-muted mb-1">Pending Actions</div>
                                <div class="h2 fw-bold mb-2 text-warning">{{ $pending_orders ?? 0 }}</div>
                                <div class="text-warning small">
                                    <i class="fas fa-truck me-1"></i>
                                    {{ $pending_orders ?? 0 }} orders to ship
                                </div>
                            </div>
                            <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                                <i class="fas fa-bell text-warning" style="font-size: 1.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            {{-- LEFT COLUMN --}}
            <div class="col-lg-8">
                {{-- 2. RECENT ORDERS --}}
                <div class="card border-0 shadow-sm rounded-3 h-100">
                    <div class="card-header bg-transparent border-0 p-4 pb-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">Recent Orders</h5>
                                <p class="text-muted small mb-0">Latest customer purchases</p>
                            </div>
                            <a href="{{ route('sellers.orders.index') }}" class="btn btn-outline-success btn-sm">
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
                                            <th class="border-0" style="width: 25%">Customer</th>
                                            <th class="border-0" style="width: 20%">Date</th>
                                            <th class="border-0" style="width: 15%">Status</th>
                                            <th class="border-0" style="width: 15%">Amount</th>
                                            <th class="border-0 text-end" style="width: 10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentOrders as $order)
                                            <tr class="border-bottom">
                                                <td>
                                                <h6 class="mb-1">
                                        #{{ str_pad($order->id, 10, '0', STR_PAD_LEFT) }}
                                    </h6>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-light rounded-circle p-2 me-2">
                                                            <i class="fas fa-user text-muted"></i>
                                                        </div>
                                                        <div>
                                                            <div class="fw-medium">{{ $order->user->name ?? 'Customer' }}</div>
                                                            <small class="text-muted">{{ $order->user->email ?? '' }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>{{ $order->created_at->format('M j') }}</div>
                                                    <small class="text-muted">{{ $order->created_at->format('g:i A') }}</small>
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
                                                        class="badge bg-{{ $statusColor }} bg-opacity-10 text-{{ $statusColor }} border border-{{ $statusColor }} border-opacity-25 px-3 py-1">
                                                        {{ ucfirst($order->status) }}
                                                    </span>
                                                </td>
                                                <td class="fw-bold">RM {{ number_format($order->seller_total ?? 0, 2) }}</td>
                                                <td class="text-end">
                                                    <div class="dropdown">
                                                        <button class="btn btn-light btn-sm" type="button"
                                                            data-bs-toggle="dropdown">
                                                            <i class="fas fa-ellipsis-h"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li>
                                                                <a class="dropdown-item"
                                                                    href="{{ route('sellers.orders.show', $order->id) }}">
                                                                    <i class="fas fa-eye me-2"></i> View Details
                                                                </a>
                                                            </li>
                                                            @if($order->status == 'paid')
                                                                <li>
                                                                    <form action="{{ route('sellers.orders.update', $order->id) }}"
                                                                        method="POST">
                                                                        @csrf @method('PUT')
                                                                        <input type="hidden" name="status" value="shipped">
                                                                        <button type="submit" class="dropdown-item">
                                                                            <i class="fas fa-shipping-fast me-2"></i> Mark as Shipped
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </div>
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
                                <p class="text-muted">When you receive new orders, they'll appear here.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN --}}
            <div class="col-lg-4" style="height: 600px;">
                {{-- 3. PRODUCT INVENTORY --}}
                <div class="card border-0 shadow-sm rounded-3 mb-4 h-100">
                    <div class="card-header bg-transparent border-0 p-4 pb-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">Product Inventory</h5>
                                <p class="text-muted small mb-0">Stock overview</p>
                            </div>
                            <div>
                                <a href="{{ route('sellers.inventory.create') }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus me-1"></i> Add New
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4 pt-0">
                        @if(!empty($inventoryProducts) && $inventoryProducts->count())
                            <div class="list-group list-group-flush">
                                @foreach($inventoryProducts as $product)
                                                @php
                                                    $firstImage = $product->images->first();
                                                @endphp
                                                <div class="list-group-item border-0 px-0 py-3">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="d-flex align-items-center">
                                                            <div class="position-relative me-3">
                                                                <img src="{{ $firstImage
                                    ? asset('images/' . $firstImage->image_path)
                                    : asset('images/default-product.jpg') }}" class="rounded-2"
                                                                    style="width: 50px; height: 50px; object-fit: cover;">

                                                                @if(($product->stock_quantity ?? 0) <= 10)
                                                                    <span
                                                                        class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger border border-white"
                                                                        style="width: 12px; height: 12px;"></span>
                                                                @endif
                                                            </div>
                                                            <div>
                                                                <div class="fw-medium mb-1">{{ Str::limit($product->product_name, 22) }}</div>
                                                                <div class="d-flex align-items-center">
                                                                    <span class="badge bg-light text-dark me-2">
                                                                        <i class="fas fa-cube me-1"></i> {{ $product->stock_quantity ?? 0 }}
                                                                    </span>

                                                                    <span class="text-success fw-medium">
                                                                        RM {{ number_format($product->price, 2) }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <a href="{{ route('sellers.inventory.edit', $product->id) }}"
                                                            class="btn btn-outline-secondary btn-sm">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                @endforeach
                            </div>
                            <div class="mt-3 text-center">
                                <a href="{{ route('sellers.inventory.index') }}"
                                    class="text-decoration-none text-success small">
                                    <i class="fas fa-arrow-right me-1"></i> View all products
                                </a>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <div class="display-1 text-muted mb-3">
                                    <i class="fas fa-box-open"></i>
                                </div>
                                <h5 class="text-muted mb-3">No Products Yet</h5>
                                <p class="text-muted mb-3">Start by adding your first product</p>
                                <a href="{{ route('sellers.inventory.create') }}" class="btn btn-success">
                                    <i class="fas fa-plus me-1"></i> Add First Product
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-4">

{{-- QUICK ACTIONS (Left) --}}
<div class="col-lg-4">
    <div class="card border-0 shadow-sm rounded-3 h-100">
        <div class="card-header bg-transparent border-0 p-4 pb-2">
            <h5 class="mb-1">Quick Actions</h5>
            <p class="text-muted small mb-0">Frequently used tasks</p>
        </div>
        <div class="card-body p-4 pt-0">
            <div class="d-grid gap-2">
                <a href="{{ route('sellers.inventory.create') }}" class="btn btn-success">
                    <i class="fas fa-plus-circle me-2"></i> Add New Product
                </a>
                <a href="{{ route('sellers.orders.index') }}" class="btn btn-outline-success">
                    <i class="fas fa-list-alt me-2"></i> View All Orders
                </a>
                <a href="{{ route('sellers.chats.index') }}" class="btn btn-outline-info">
                    <i class="fas fa-comments me-2"></i> Customer Messages
                </a>
                <a href="{{ route('sellers.orders.index') }}" class="btn btn-outline-warning">
                    <i class="fas fa-exchange-alt me-2"></i> Returns & Refunds
                </a>
            </div>
        </div>
    </div>
</div>

{{-- ANALYTICS (Right) --}}
<div class="col-lg-8">
    <div class="card border-0 shadow-sm rounded-3 h-100">
        <div class="card-header bg-transparent border-0 p-4 pb-2">
            <h5 class="mb-1">Performance Analytics</h5>
            <p class="text-muted small mb-0">Sales trends and insights</p>
        </div>
        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-8">
                    <canvas id="salesChart" style="height: 300px;"></canvas>
                </div>
                <div class="col-md-4">
                    <div class="h-100">
                        <h6 class="mb-3">Quick Stats</h6>
                        <div class="list-group list-group-flush">
                            <div class="list-group-item border-0 px-0 d-flex justify-content-between">
                                <span>Avg. Order Value</span>
                                <span class="fw-bold">RM {{ number_format($avg_order_value ?? 0, 2) }}</span>
                            </div>
                            <div class="list-group-item border-0 px-0 d-flex justify-content-between">
                                <span>Conversion Rate</span>
                                <span class="fw-bold">{{ number_format($conversion_rate ?? 0, 1) }}%</span>
                            </div>
                            <div class="list-group-item border-0 px-0 d-flex justify-content-between">
                                <span>Best Selling</span>
                                <span class="fw-bold">{{ $best_selling_product ?? 'N/A' }}</span>
                            </div>
                            <div class="list-group-item border-0 px-0 d-flex justify-content-between">
                                <span>Customer Rating</span>
                                <span class="fw-bold">
                                    {{ number_format($avg_rating ?? 0, 1) }}
                                    <i class="fas fa-star text-warning"></i>
                                </span>
                            </div>
                        </div>

</div>

            
@endsection

    @section('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Initialize tooltips
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });

                // Add loading animation to buttons
                document.querySelectorAll('.btn').forEach(button => {
                    button.addEventListener('click', function (e) {
                        if (!this.classList.contains('dropdown-toggle')) {
                            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Loading...';
                            this.disabled = true;
                        }
                    });
                });

            });
        </script>
    @endsection