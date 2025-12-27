@extends('layouts.admin-main')

@section('title', 'Admin Dashboard')

@section('content')
    <style>
        .module-card {
            background: white;
            border: 1px solid rgba(92, 127, 81, 0.1);
            border-radius: 12px;
            padding: 1.5rem;
            height: 100%;
            transition: all 0.3s ease;
            border-left: 4px solid #5C7F51;
        }

        .module-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(92, 127, 81, 0.1);
            border-left-color: #4A6B40;
        }

        .module-icon {
            width: 50px;
            height: 50px;
            background: rgba(92, 127, 81, 0.1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #5C7F51;
            margin-bottom: 1rem;
        }

        .dashboard-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #2c3e50;
        }
    </style>

    <div class="container mt-4">
        <h5 class="fw-bold text-success mb-4">Dashboard</h5>

        <!-- Admin Overview (Dashboard Home) -->
        <div class="row g-4 mb-4">
            <!-- Total Users -->
            <div class="col-md-3">
                <a href="{{ route('admin.users.index') }}" class="text-decoration-none text-dark">
                    <div class="card shadow-sm border-start p-3 h-100 card-hover">
                        <div class="text-muted small">Total Users</div>
                        <div class="h3 fw-bold">{{ $totalUsers }}</div>
                    </div>
                </a>
            </div>

            <!-- Total Products -->
            <div class="col-md-3">
                <a href="{{ route('admin.products.index') }}" class="text-decoration-none text-dark">
                    <div class="card shadow-sm border-start p-3 h-100 card-hover">
                        <div class="text-muted small">Total Products</div>
                        <div class="h3 fw-bold">{{ $totalProducts }}</div>
                    </div>
                </a>
            </div>


            <!-- Total Orders -->
            <div class="col-md-3">
                <a href="{{ route('admin.orders.index') }}" class="text-decoration-none text-dark">
                    <div class="card shadow-sm border-start p-3 h-100 card-hover">
                        <div class="text-muted small">Total Orders</div>
                        <div class="h3 fw-bold">{{ $totalOrders ?? '0' }}</div>
                    </div>
                </a>
            </div>

            <!-- Total Revenue -->
            <div class="col-md-3">
                <a href="{{ route('admin.orders.index', ['status' => 'paid']) }}" class="text-decoration-none text-dark">
                    <div class="card shadow-sm border-start p-3 h-100 card-hover">
                        <div class="text-muted small">Total Revenue</div>
                        <div class="h3 fw-bold">
                            RM {{ number_format($totalRevenue ?? 0, 2) }}
                        </div>
                    </div>
                </a>

            </div>
        </div>

        <!-- Order Status Summary -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm p-3 text-center" style="background: #fff3cd; border: none;">
                    <h6 class="text-warning">Pending Orders</h6>
                    <h4 class="mb-0">{{ $pendingOrders ?? '0' }}</h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm p-3 text-center" style="background: #d4edda; border: none;">
                    <h6 class="text-success">Paid Orders</h6>
                    <h4 class="mb-0">{{ $paidOrders ?? '0' }}</h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm p-3 text-center" style="background: #f8d7da; border: none;">
                    <h6 class="text-danger">Cancelled Orders</h6>
                    <h4 class="mb-0">{{ $cancelledOrders ?? '0' }}</h4>
                </div>
            </div>
        </div>

        <!-- Pending Items & Quick Actions -->
        <div class="row g-4 mb-4">
            <!-- Pending Items -->
            <div class="col-md-6">
                <div class="card shadow-sm rounded p-4">
                    <h6 class="mb-3 fw-bold">Pending Items</h6>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <div class="p-3 bg-light rounded">
                                <div class="text-muted small">Pending Products</div>
                                <h5 class="fw-bold mb-0">{{ $pendingProducts }}</h5>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="p-3 bg-light rounded">
                                <div class="text-muted small">Pending Sellers</div>
                                <h5 class="fw-bold mb-0">{{ $pendingSellers }}</h5>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded">
                                <div class="text-muted small">Open Complaints</div>
                                <h5 class="fw-bold mb-0">{{ $openComplaints }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Products -->
            <div class="col-md-6">
                <div class="card shadow-sm rounded p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0">Recent Products</h6>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-success">View All</a>
                    </div>
                    <div class="row g-3" style="max-height: 239px; overflow-y:auto;">
                        @foreach($recentProducts as $p)
                            <div class="col-12">
                                <a href="{{ route('admin.products.show', $p->id) }}" class="text-decoration-none text-dark">
                                    <div class="d-flex align-items-center bg-light rounded p-2">
                                        <img src="{{ $p->images->first() ? asset('images/' . $p->images->first()->image_path) : asset('images/default.jpg') }}"
                                            class="rounded" style="width:64px; height:74px; object-fit:cover;">
                                        <div class="ms-2">
                                            <div class="small text-muted">{{ $p->seller->business_name ?? 'Seller' }}</div>
                                            <div class="fw-medium">{{ Str::limit($p->product_name, 20) }}</div>
                                            <div class="text-success small">RM {{ number_format($p->price, 2) }}</div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- All Admin Modules (2-9) -->
        <div class="row mb-4">
            <div class="col-12">
                <h5 class="fw-bold text-success mb-3">Management Modules</h5>
            </div>

            <!-- 2️⃣ User Management -->
            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                <a href="{{ route('admin.users.index') }}" class="text-decoration-none text-dark">
                    <div class="module-card">
                        <div class="module-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <h6 class="fw-bold mb-2">User Management</h6>
                        <p class="text-muted small mb-0">
                            Manage all users, activate/deactivate accounts, approve/reject sellers
                        </p>
                    </div>
                </a>
            </div>

            <!-- 3️⃣ Product Management -->
            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                <a href="{{ route('admin.products.index') }}" class="text-decoration-none text-dark">
                    <div class="module-card">
                        <div class="module-icon">
                            <i class="bi bi-box-seam-fill"></i>
                        </div>
                        <h6 class="fw-bold mb-2">Product Management</h6>
                        <p class="text-muted small mb-0">
                            View all products, approve/reject products, manage product status
                        </p>
                    </div>
                </a>
            </div>

            <!-- 4️⃣ Category & Tag Management -->
            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                <a href="{{ route('admin.users.index') }}" class="text-decoration-none text-dark">
                    <div class="module-card">
                        <div class="module-icon">
                            <i class="bi bi-tags-fill"></i>
                        </div>
                        <h6 class="fw-bold mb-2">Category & Tags</h6>
                        <p class="text-muted small mb-0">
                            Create/edit/delete categories and tags, assign categories to products
                        </p>
                    </div>
                </a>
            </div>

            <!-- 5️⃣ Order Management -->
            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                <a href="{{ route('admin.orders.index') }}" class="text-decoration-none text-dark">
                    <div class="module-card">
                        <div class="module-icon">
                            <i class="bi bi-cart-check-fill"></i>
                        </div>
                        <h6 class="fw-bold mb-2">Order Management</h6>
                        <p class="text-muted small mb-0">
                            View all orders, update order status, confirm payments
                        </p>
                    </div>
                </a>
            </div>

            <!-- 6️⃣ Payment & Transaction Monitoring -->
            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                <a href="{{ route('admin.users.index') }}" class="text-decoration-none text-dark">
                    <div class="module-card">
                        <div class="module-icon">
                            <i class="bi bi-credit-card-fill"></i>
                        </div>
                        <h6 class="fw-bold mb-2">Payment Monitoring</h6>
                        <p class="text-muted small mb-0">
                            View transaction history, verify payment status, match payments to orders
                        </p>
                    </div>
                </a>
            </div>

            <!-- 7️⃣ Seller Management -->
            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                <a href="{{ route('admin.users.index') }}" class="text-decoration-none text-dark">
                    <div class="module-card">
                        <div class="module-icon">
                            <i class="bi bi-shop-window"></i>
                        </div>
                        <h6 class="fw-bold mb-2">Seller Management</h6>
                        <p class="text-muted small mb-0">
                            View all sellers, approve/suspend sellers, view seller performance
                        </p>
                    </div>
                </a>
            </div>

            <!-- 8️⃣ Reports & Analytics -->
            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                <a href="{{ route('admin.users.index') }}" class="text-decoration-none text-dark">
                    <div class="module-card">
                        <div class="module-icon">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <h6 class="fw-bold mb-2">Reports & Analytics</h6>
                        <p class="text-muted small mb-0">
                            Sales reports, order statistics, product performance, charts & graphs
                        </p>
                    </div>
                </a>
            </div>

            <!-- 9️⃣ System Monitoring & Logs -->
            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                <a href="{{ route('admin.users.index') }}" class="text-decoration-none text-dark">
                    <div class="module-card">
                        <div class="module-icon">
                            <i class="bi bi-clipboard-data-fill"></i>
                        </div>
                        <h6 class="fw-bold mb-2">System Logs</h6>
                        <p class="text-muted small mb-0">
                            Activity logs, error logs, audit logs, system monitoring
                        </p>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Chart initialization (if you want to keep it)
        const ctx = document.getElementById('activityChart');
        if (ctx) {
            new Chart(ctx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
                    datasets: [
                        {
                            label: "New Users",
                            data: [5, 8, 4, 6, 10, 3, 7],
                            borderColor: "#6A8F4E",
                            backgroundColor: "rgba(106,143,78,0.3)",
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: "New Products",
                            data: [2, 4, 3, 8, 5, 9, 6],
                            borderColor: "#A5B682",
                            backgroundColor: "rgba(165,182,130,0.3)",
                            tension: 0.4,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'top' }
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }
    </script>
@endsection