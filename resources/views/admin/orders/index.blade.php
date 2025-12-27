{{-- index.blade.php --}}
@extends('layouts.admin-main')

@section('title', 'Admin Orders')

@section('content')
    <style>
        .admin-orders-page {
            min-height: 100vh;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9f5e9 100%);
            padding: 40px 0;
        }

        .page-header {
            background: white;
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 30px;
            border: 1px solid rgba(92, 127, 81, 0.1);
            box-shadow: 0 5px 20px rgba(92, 127, 81, 0.08);
        }

        .page-title {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .page-subtitle {
            color: #6c757d;
            font-size: 14px;
        }

        .order-card {
            background: white;
            border-radius: 16px;
            border: 1px solid rgba(92, 127, 81, 0.1);
            box-shadow: 0 5px 20px rgba(92, 127, 81, 0.08);
            transition: all 0.3s ease;
            margin-bottom: 20px;
            overflow: hidden;
        }

        .order-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(92, 127, 81, 0.12);
        }

        .order-header {
            background: linear-gradient(135deg, rgba(92, 127, 81, 0.05) 0%, rgba(165, 182, 130, 0.05) 100%);
            padding: 20px;
            border-bottom: 1px solid rgba(92, 127, 81, 0.1);
        }

        .order-id {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .order-meta {
            color: #6c757d;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .status-badge {
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .status-pending {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .status-paid {
            background: linear-gradient(135deg, #cce5ff 0%, #b8daff 100%);
            color: #004085;
            border: 1px solid #b8daff;
        }

        .status-shipped {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .order-actions {
            padding: 20px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .btn-view {
            background: transparent;
            border: 2px solid #5C7F51;
            color: #5C7F51;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-view:hover {
            background: #5C7F51;
            color: white;
            transform: translateX(3px);
        }

        .btn-mark-paid {
            background: linear-gradient(135deg, #5C7F51 0%, #A5B682 100%);
            color: white;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-mark-paid:hover {
            background: linear-gradient(135deg, #4a6b42 0%, #5C7F51 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(92, 127, 81, 0.2);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 16px;
            border: 1px solid rgba(92, 127, 81, 0.1);
        }

        .empty-icon {
            font-size: 64px;
            color: #dee2e6;
            margin-bottom: 20px;
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border: 1px solid #c3e6cb;
            color: #155724;
            border-radius: 12px;
            padding: 15px 20px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-section {
            background: white;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 25px;
            border: 1px solid rgba(92, 127, 81, 0.1);
        }

        .filter-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
            font-size: 14px;
        }
    </style>

    <div class="admin-orders-page">
        <div class="container">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">
                    <i class="bi bi-receipt me-2"></i>Order Management
                </h1>
                <p class="page-subtitle">View and manage all customer orders</p>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="alert-success">
                    <i class="bi bi-check-circle-fill"></i>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Filter Section -->
            <div class="filter-section">
                <div class="row">
                    <div class="col-md-4">
                        <label class="filter-label">Status Filter</label>
                        <select class="form-select" onchange="filterOrders(this.value)">
                            <option value="">All Orders</option>
                            <option value="Pending">Pending</option>
                            <option value="Paid">Paid</option>
                            <option value="Shipped">Shipped</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="filter-label">Sort By</label>
                        <select class="form-select">
                            <option>Newest First</option>
                            <option>Oldest First</option>
                            <option>Total Amount (High to Low)</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="filter-label">Search</label>
                        <input type="text" class="form-control" placeholder="Search order ID or buyer...">
                    </div>
                </div>
            </div>

            <!-- Orders List -->
            @forelse($orders as $order)
                <div class="order-card" data-status="{{ strtolower($order->status) }}">
                    <!-- Order Header -->
                    <div class="order-header d-flex justify-content-between align-items-center">
                        <div>
                            <div class="order-id">Order ID #{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}</div>
                            <div class="order-meta">
                                <span class="meta-item">
                                    <i class="bi bi-person"></i>
                                    Buyer ID: {{ $order->buyer_id }}
                                </span>
                                <span class="meta-item">
                                    <i class="bi bi-calendar"></i>
                                    {{ $order->created_at->format('d M Y, H:i') }}
                                </span>
                            </div>
                        </div>

                        <span class="status-badge 
                                                        @if($order->status === 'Pending') status-pending
                                                        @elseif($order->status === 'Paid') status-paid
                                                        @elseif($order->status === 'Shipped') status-shipped
                                                        @endif">
                            <i class="bi 
                                                            @if($order->status === 'Pending') bi-clock
                                                            @elseif($order->status === 'Paid') bi-credit-card
                                                            @elseif($order->status === 'Shipped') bi-truck
                                                            @endif"></i>
                            {{ $order->status }}
                        </span>
                    </div>

                    <!-- Order Actions -->
                    <div class="order-actions">
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn-view">
                            <i class="bi bi-eye"></i>
                            View Details
                        </a>

                        @if($order->status === 'Pending')
                            <form action="{{ route('admin.orders.markPaid', $order) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn-mark-paid">
                                    <i class="bi bi-check-circle"></i>
                                    Mark as Paid
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <!-- Empty State -->
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <h5 class="text-muted mb-2">No orders found</h5>
                    <p class="text-muted">There are no orders to display at the moment.</p>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        function filterOrders(status) {
            const cards = document.querySelectorAll('.order-card');
            cards.forEach(card => {
                if (!status || card.getAttribute('data-status') === status.toLowerCase()) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>
@endsection