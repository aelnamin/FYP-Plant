@extends('layouts.admin-main')

@section('title', 'Reports Dashboard')

@section('content')
    <div class="container-fluid py-4">


        {{-- SUMMARY CARDS --}}
        <div class="row g-4 mb-5">
            <div class="col-xl-3 col-md-6">
                <div class="card border rounded-4 overflow-hidden h-100 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="text-muted small fw-semibold">TOTAL REVENUE</span>
                                <h2 class="fw-bold mt-2 mb-0 text-primary">
                                    RM {{ number_format($totalRevenue, 2) }}
                                </h2>
                                <span class="text-success small fw-semibold">
                                    <i class="fas fa-trend-up me-1"></i> All time
                                </span>
                            </div>
                            <div class="bg-primary bg-opacity-10 p-3 rounded-3">
                                <i class="fas fa-chart-line text-primary fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border rounded-4 overflow-hidden h-100 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="text-muted small fw-semibold">TOTAL ORDERS</span>
                                <h2 class="fw-bold mt-2 mb-0 text-dark">{{ $totalOrders }}</h2>
                                <span class="text-muted small">Completed Orders</span>
                            </div>
                            <div class="bg-info bg-opacity-10 p-3 rounded-3">
                                <i class="fas fa-shopping-cart text-info fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border rounded-4 overflow-hidden h-100 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="text-muted small fw-semibold">TOTAL SELLERS</span>
                                <h2 class="fw-bold mt-2 mb-0 text-dark">
                                    {{ $totalRegisteredSellers }}
                                    <span class="d-block fs-6 text-success">Active: {{ $totalActiveSellers }}</span>
                                </h2>

                                <span class="text-muted small">Registered / Making sales</span>
                            </div>
                            <div class="bg-warning bg-opacity-10 p-3 rounded-3">
                                <i class="fas fa-store text-warning fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-xl-3 col-md-6">
                <div class="card border rounded-4 overflow-hidden h-100 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span class="text-muted small fw-semibold">PRODUCTS SOLD</span>
                                <h2 class="fw-bold mt-2 mb-0 text-dark">{{ $totalProductsSold }}</h2>
                                <span class="text-muted small">Units delivered</span>
                            </div>
                            <div class="bg-success bg-opacity-10 p-3 rounded-3">
                                <i class="fas fa-box text-success fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            {{-- ORDER STATUS BREAKDOWN --}}
            <div class="col-lg-6">
                <div class="card border rounded-4 h-100 shadow-sm">
                    <div class="card-header border-0 bg-white pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0 text-dark">
                                <i class="fas fa-tasks text-primary me-2"></i>
                                Order Status Breakdown
                            </h5>
                            <span class="badge bg-light text-dark">
                                {{ $statusBreakdown->count() }} statuses
                            </span>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        @if($statusBreakdown->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr class="text-muted border-top">
                                            <th class="border-0 py-3 fw-semibold">STATUS</th>
                                            <th class="border-0 py-3 fw-semibold text-end">COUNT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($statusBreakdown as $status)
                                            <tr class="border-bottom">
                                                <td class="py-3">
                                                    <span class="d-flex align-items-center">
                                                        <span
                                                            class="status-indicator status-{{ Str::slug($status->seller_status) }} me-3"></span>
                                                        {{ $status->seller_status }}
                                                    </span>
                                                </td>
                                                <td class="py-3 text-end">
                                                    <span class="badge bg-light text-dark fs-6 px-3 py-2">
                                                        {{ $status->total }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <i class="fas fa-chart-pie fa-3x text-muted opacity-25"></i>
                                </div>
                                <p class="text-muted mb-0">No order status data available</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- TOP SELLERS --}}
            <div class="col-lg-6">
                <div class="card border rounded-4 h-100 shadow-sm">
                    <div class="card-header border-0 bg-white pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0 text-dark">
                                <i class="fas fa-crown text-warning me-2"></i>
                                Top Performing Sellers
                            </h5>
                            <span class="badge bg-warning bg-opacity-10 text-warning">
                                Top {{ min(5, $topSellers->count()) }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        @if($topSellers->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr class="text-muted border-top">
                                            <th class="border-0 py-3 fw-semibold">SELLER</th>
                                            <th class="border-0 py-3 fw-semibold text-end">SALES</th>
                                            <th class="border-0 py-3 fw-semibold text-end">ORDERS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topSellers as $index => $seller)
                                            <tr class="border-bottom">
                                                <td class="py-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="rank-icon rank-{{ $index + 1 }} me-3">
                                                            @if($index + 1 == 1)
                                                                <i class="fas fa-trophy text-warning"></i>
                                                            @elseif($index + 1 == 2)
                                                                <i class="fas fa-medal text-secondary"></i>
                                                            @elseif($index + 1 == 3)
                                                                <i class="fas fa-award text-bronze"></i>
                                                            @else
                                                                <span class="rank-number">{{ $index + 1 }}</span>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <div class="fw-semibold">{{ $seller->business_name }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="py-3 text-end fw-bold text-primary">
                                                    RM {{ number_format($seller->total_sales, 2) }}
                                                </td>
                                                <td class="py-3 text-end">
                                                    <span class="badge bg-info bg-opacity-10 text-info">
                                                        {{ $seller->completed_orders }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <i class="fas fa-store fa-3x text-muted opacity-25"></i>
                                </div>
                                <p class="text-muted mb-0">No seller data available</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- TOP PRODUCTS (Full Width) --}}
            <div class="col-12">
                <div class="card border rounded-4 mt-4 shadow-sm">
                    <div class="card-header border-0 bg-white pt-4 pb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0 text-dark">
                                <i class="fas fa-star text-success me-2"></i>
                                Best Selling Products
                            </h5>
                            <span class="badge bg-success bg-opacity-10 text-success">
                                {{ $topProducts->count() }} products
                            </span>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        @if($topProducts->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr class="text-muted border-top">
                                            <th class="border-0 py-3 fw-semibold">PRODUCT</th>
                                            <th class="border-0 py-3 fw-semibold text-center">QUANTITY SOLD</th>
                                            <th class="border-0 py-3 fw-semibold text-end">REVENUE GENERATED</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topProducts as $product)
                                            <tr class="border-bottom">
                                                <td class="py-3">
                                                    <div class="fw-semibold">{{ $product->product_name }}</div>
                                                </td>
                                                <td class="py-3 text-center">
                                                    <div class="d-flex align-items-center justify-content-center">
                                                        <span class="badge bg-light text-dark fs-6 px-3 py-2">
                                                            {{ $product->total_quantity }} units
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="py-3 text-end fw-bold text-success">
                                                    RM {{ number_format($product->revenue, 2) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <i class="fas fa-box-open fa-3x text-muted opacity-25"></i>
                                </div>
                                <p class="text-muted mb-0">No product data available</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            transition: transform 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        .status-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
        }

        .status-pending {
            background-color: #ffc107;
        }

        .status-processing {
            background-color: #17a2b8;
        }

        .status-completed {
            background-color: #28a745;
        }

        .status-cancelled {
            background-color: #dc3545;
        }

        .status-shipped {
            background-color: #007bff;
        }

        .status-delivered {
            background-color: #20c997;
        }

        .rank-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            background: #f8f9fa;
        }

        .rank-icon i {
            font-size: 1.1rem;
        }

        .rank-number {
            color: #6c757d;
            font-weight: bold;
        }

        .text-bronze {
            color: #cd7f32;
        }

        .table tbody tr:last-child {
            border-bottom: none !important;
        }

        .rounded-4 {
            border-radius: 1rem !important;
        }
    </style>
@endsection