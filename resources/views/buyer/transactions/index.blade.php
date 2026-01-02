@extends('layouts.main')

@section('title', 'My Transactions')

@section('content')
    <div class="container py-5">
        {{-- Page Header --}}
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 class="fw-bold text-gradient-success">My Orders</h1>
                <p class="text-muted">Track and manage all your purchases</p>
            </div>
            <div class="text-end">
                <span class="badge bg-light text-dark border px-3 py-2">
                    {{ count($orders) }} {{ Str::plural('order', count($orders)) }}
                </span>
            </div>
        </div>

        {{-- Order Statistics --}}
        @if($orders->isNotEmpty())
            <div class="row mb-5">
                <div class="col-md-3 col-6 mb-3">
                    <div class="card border-0 bg-light-warning bg-opacity-10 shadow-sm rounded-4">
                        <div class="card-body text-center py-3">
                            <h3 class="fw-bold text-warning">{{ $orders->where('status', 'Pending')->count() }}</h3>
                            <p class="text-muted mb-0">Pending</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="card border-0 bg-light-primary bg-opacity-10 shadow-sm rounded-4">
                        <div class="card-body text-center py-3">
                            <h3 class="fw-bold text-primary">{{ $orders->where('status', 'Paid')->count() }}</h3>
                            <p class="text-muted mb-0">Paid</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="card border-0 bg-light-success bg-opacity-10 shadow-sm rounded-4">
                        <div class="card-body text-center py-3">
                            <h3 class="fw-bold text-success">{{ $orders->where('status', 'Shipped')->count() }}</h3>
                            <p class="text-muted mb-0">Shipped</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-3">
                    <div class="card border-0 bg-light-info bg-opacity-10 shadow-sm rounded-4">
                        <div class="card-body text-center py-3">
                            <h3 class="fw-bold text-info">{{ $orders->count() }}</h3>
                            <p class="text-muted mb-0">Total</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Orders List --}}
        <div class="row">
            @forelse($orders as $order)
                <div class="col-12 mb-4">
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                        <div class="card-header bg-light py-3 border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0">
                                        <i class="bi bi-bag-check me-2 text-success"></i>
                                        Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
                                    </h5>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        {{ $order->created_at->format('F d, Y \a\t h:i A') }}
                                    </small>
                                </div>
                                <span class="badge rounded-pill px-3 py-2 
                                    @if($order->status === 'Pending') bg-warning text-dark
                                    @elseif($order->status === 'Paid') bg-primary
                                    @elseif($order->status === 'Shipped') bg-success
                                    @endif">
                                    <i class="bi 
                                        @if($order->status === 'Pending') bi-clock
                                        @elseif($order->status === 'Paid') bi-check-circle
                                        @elseif($order->status === 'Shipped') bi-truck
                                        @endif me-1">
                                    </i>
                                    {{ $order->status }}
                                </span>
                            </div>
                        </div>

                        <div class="card-body">
                            {{-- Order Items Preview --}}
                            @if($order->items->count() > 0)
                                <div class="mb-4">
                                    <h6 class="text-muted mb-3">Items</h6>
                                    <div class="row g-3">
                                        @foreach($order->items->take(2) as $item)
                                            @if($item->product)
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $item->product->images->first() ? asset('images/' . $item->product->images->first()->image_path) : asset('images/default.jpg') }}"
                                                            class="rounded-3 me-3" style="width: 60px; height: 60px; object-fit: cover;"
                                                            alt="{{ $item->product->product_name }}">
                                                        <div class="flex-grow-1">
                                                            <p class="mb-1 fw-semibold">{{ Str::limit($item->product->product_name, 30) }}
                                                            </p>
                                                            <small class="text-muted">Qty: {{ $item->quantity }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                        @if($order->items->count() > 2)
                                            <div class="col-12">
                                                <small class="text-muted">
                                                    +{{ $order->items->count() - 2 }} more items
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            {{-- Order Summary --}}
                            <div class="bg-light p-3 rounded-3 mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Total Amount</h6>
                                        <small class="text-muted">Inclusive of all charges</small>
                                    </div>
                                    <h4 class="fw-bold text-success mb-0">RM {{ number_format($order->total_amount, 2) }}</h4>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    @if($order->status === 'Shipped')
                                        <a href="#" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                            <i class="bi bi-truck me-1"></i> Track Order
                                        </a>
                                    @endif
                                </div>
                                <div>
                                    <a href="{{ route('buyer.transactions.show', $order->id) }}"
                                        class="btn btn-success btn-sm rounded-pill px-4">
                                        <i class="bi bi-eye me-1"></i> View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                {{-- Empty State --}}
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body text-center py-5">
                            <div class="display-1 text-muted mb-4">
                                <i class="bi bi-bag-x"></i>
                            </div>
                            <h4 class="text-muted mb-3">No Orders Yet</h4>
                            <p class="text-muted mb-4">You haven't placed any orders. Start shopping now!</p>
                            <a href="{{ route('products.index') }}" class="btn btn-success rounded-pill px-4">
                                <i class="bi bi-shop me-1"></i> Browse Products
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($orders->hasPages())
            <div class="d-flex justify-content-center mt-5">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

    <style>
        .text-gradient-success {
            background: linear-gradient(45deg, #28a745, #20c997);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .bg-light-warning {
            background-color: #fff3cd !important;
        }

        .bg-light-primary {
            background-color: #cfe2ff !important;
        }

        .bg-light-success {
            background-color: #d1e7dd !important;
        }

        .bg-light-info {
            background-color: #d1ecf1 !important;
        }
    </style>
@endsection