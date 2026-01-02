@extends('layouts.admin-main')

@section('title', 'Reports')

@section('content')
    <div class="container py-4">
        <head> <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        </head>

    <style>
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
    </style>

     <!-- Page Header -->
     <div class="page-header">
        <h1 class="page-title">Reports</h1>
            <p class="page-subtitle">Monitor users orders and payments</p>
        </div>

        {{-- FILTER --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label>Status</label>
                        <select name="status" class="form-select">
                            <option value="">All</option>
                            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Paid" {{ request('status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                            <option value="Shipped" {{ request('status') == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label>From</label>
                        <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                    </div>

                    <div class="col-md-3">
                        <label>To</label>
                        <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                    </div>

                    <div class="col-md-3 d-flex align-items-end">
                        <button class="btn btn-primary w-100">Filter</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- PAYMENT SUMMARY --}}
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted">Total Revenue</h6>
                        <h3 class="fw-bold text-success">
                        <i class="bi bi-cash-coin me-2"></i>
                            RM {{ number_format($totalRevenue, 2) }}
                        </h3>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted">Total Paid Orders</h6>
                        <h3 class="fw-bold">
                        <i class="bi bi-wallet2 me-2"></i>
                        {{ $totalPaidOrders }}</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- ORDER REPORT --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header fw-bold">Order Report</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Order ID</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $order->status }}</span>
                                </td>
                                <td>RM {{ number_format($order->total_amount, 2) }}</td>
                                <td>{{ $order->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-3">
                                    No orders found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- SELLER PERFORMANCE --}}
        <div class="card shadow-sm">
            <div class="card-header fw-bold">Seller Performance</div>
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Seller</th>
                            <th>Total Products</th>
                            <th>Total Sales (RM)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sellerPerformance as $seller)
                            <tr>
                                <td>{{ $seller->business_name ?? 'Seller #' . $seller->id }}</td>
                                <td>{{ $seller->products_count }}</td>
                                <td>
                                 RM {{ number_format($seller->total_sales ?? 0, 2) }}
                               </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection