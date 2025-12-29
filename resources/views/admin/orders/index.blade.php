@extends('layouts.admin-main')

@section('title', 'Orders')

@section('content')
    <style>
        :root {
            --primary-color: #5C7F51;
            --primary-light: rgba(92, 127, 81, 0.08);
            --border-radius: 10px;
            --transition: all 0.2s ease;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.25rem;
        }

        .page-subtitle {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .filter-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            border: 1px solid #e9ecef;
        }

        .filter-label {
            font-size: 0.85rem;
            color: #495057;
            font-weight: 500;
            margin-bottom: 0.5rem;
            display: block;
        }

        .order-card {
            background: white;
            border-radius: var(--border-radius);
            border: 1px solid #e9ecef;
            margin-bottom: 1rem;
            transition: var(--transition);
            overflow: hidden;
        }

        .order-card:hover {
            border-color: var(--primary-color);
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.05);
        }

        .order-header {
            padding: 1.25rem;
            border-bottom: 1px solid #f1f3f4;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
        }

        .order-info {
            flex: 1;
        }

        .order-id {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .order-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 0.5rem;
        }

        .meta-item {
            font-size: 0.85rem;
            color: #6c757d;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .status-badge {
            padding: 0.375rem 0.875rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 500;
            border: 1px solid transparent;
            white-space: nowrap;
        }

        .status-pending {
            background: rgba(255, 193, 7, 0.1);
            color: #856404;
            border-color: rgba(255, 193, 7, 0.2);
        }

        .status-paid {
            background: rgba(40, 167, 69, 0.1);
            color: #155724;
            border-color: rgba(40, 167, 69, 0.2);
        }

        .status-shipped {
            background: rgba(0, 123, 255, 0.1);
            color: #004085;
            border-color: rgba(0, 123, 255, 0.2);
        }

        .order-actions {
            padding: 1rem 1.25rem;
            border-top: 1px solid #f1f3f4;
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
        }

        .btn-view {
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
            padding: 0.5rem 1.25rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-view:hover {
            background: var(--primary-color);
            color: white;
        }

        .btn-mark-paid {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.5rem 1.25rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-mark-paid:hover {
            background: #4a6b40;
            transform: translateY(-1px);
        }

        .empty-state {
            padding: 3rem 1rem;
            text-align: center;
            background: white;
            border-radius: var(--border-radius);
            border: 1px solid #e9ecef;
        }

        .empty-icon {
            font-size: 3rem;
            color: #dee2e6;
            margin-bottom: 1rem;
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.1);
            border: 1px solid rgba(40, 167, 69, 0.2);
            color: #155724;
            border-radius: var(--border-radius);
            padding: 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .pagination-wrapper {
            margin-top: 2rem;
            display: flex;
            justify-content: center;
        }

        .stats-summary {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            overflow-x: auto;
            padding-bottom: 0.5rem;
        }

        .stat-item {
            background: white;
            border-radius: var(--border-radius);
            padding: 0.75rem 1rem;
            border: 1px solid #e9ecef;
            min-width: 120px;
            flex-shrink: 0;
        }

        .stat-label {
            font-size: 0.75rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.25rem;
        }

        .stat-value {
            font-size: 1.25rem;
            font-weight: 600;
            color: #2c3e50;
        }

        @media (max-width: 768px) {
            .order-header {
                flex-direction: column;
                gap: 0.75rem;
            }

            .order-actions {
                flex-wrap: wrap;
            }

            .stats-summary {
                gap: 0.5rem;
            }
        }
    </style>

    <div class="container py-4">
        <!-- Page Header -->
        <div class="page-header">
        <h1 class="page-title">Orders</h1>
            <p class="page-subtitle">Manage customer orders and payments</p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert-success">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Filters -->
        <div class="filter-card">
            <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="filter-label">Status</label>
                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All Status</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Paid" {{ request('status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                        <option value="Shipped" {{ request('status') == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="filter-label">Sort By</label>
                    <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                        <option value="amount_high" {{ request('sort') == 'amount_high' ? 'selected' : '' }}>Amount (High to Low)</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="filter-label">Search</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-transparent">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control" placeholder="Order ID or buyer..." value="{{ request('search') }}">
                    </div>
                </div>
            </form>
        </div>

        <!-- Orders List -->
        @forelse($orders as $order)
            <div class="order-card" data-status="{{ strtolower($order->status) }}">
                <!-- Order Header -->
                <div class="order-header">
                    <div class="order-info">
                        <div class="order-id">
                            <i class="bi bi-receipt"></i>
                            #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
                        </div>
                        
                        <div class="order-meta">
                            <span class="meta-item">
                                <i class="bi bi-person"></i>
                                Buyer: {{ $order->buyer->name ?? 'User #' . $order->buyer_id }}
                            </span>
                            <span class="meta-item">
                                <i class="bi bi-calendar"></i>
                                {{ $order->created_at->format('M d, Y') }}
                            </span>
                            <span class="meta-item">
                                <i class="bi bi-currency-dollar"></i>
                                RM {{ number_format($order->total_amount, 2) }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <span class="status-badge status-{{ strtolower($order->status) }}">
                            @switch($order->status)
                                @case('Pending')
                                    <i class="bi bi-clock me-1"></i>
                                    @break
                                @case('Paid')
                                    <i class="bi bi-credit-card me-1"></i>
                                    @break
                                @case('Shipped')
                                    <i class="bi bi-truck me-1"></i>
                                    @break
                            @endswitch
                            {{ $order->status }}
                        </span>
                    </div>
                </div>

                <!-- Order Actions -->
                <div class="order-actions">
                    <a href="{{ route('admin.orders.show', $order) }}" class="btn-view">
                        <i class="bi bi-eye"></i>
                        View Details
                    </a>
                </div>
            </div>
        @empty
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="bi bi-receipt"></i>
                </div>
                <h5 class="text-muted mb-2">No orders found</h5>
                <p class="text-muted mb-3">There are no orders matching your criteria.</p>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary btn-sm">
                    Clear filters
                </a>
            </div>
        @endforelse

        <!-- Pagination -->
@if($orders instanceof \Illuminate\Pagination\LengthAwarePaginator && $orders->hasPages())
    <div class="pagination-wrapper">
        {{ $orders->links() }}
    </div>
@endif

    </div>

    <script>
        function filterOrders(status) {
            const cards = document.querySelectorAll('.order-card');
            cards.forEach(card => {
                if (!status || card.getAttribute('data-status') === status.toLowerCase()) {
                    card.style.display = 'block';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 10);
                } else {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(-10px)';
                    setTimeout(() => {
                        card.style.display = 'none';
                    }, 200);
                }
            });
        }

        // Initialize cards with animation
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.order-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 50);
            });
        });
    </script>
@endsection