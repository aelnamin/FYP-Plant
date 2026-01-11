@extends('layouts.sellers-main')

@section('title', 'Orders')

@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-12">

                <head>
                    <link rel="stylesheet"
                        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
                </head>

                {{-- Validation Errors --}}
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Please fix the following errors:</h6>
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Success Message --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-0">Success!</h6>
                                <p class="mb-0">{{ session('success') }}</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Error Message --}}
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-x-circle-fill fs-4 me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-0">Error!</h6>
                                <p class="mb-0">{{ session('error') }}</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(isset($sellerId) && $orders->count() > 0)

                    <!-- Orders List -->
                    @foreach($orders as $order)
                        @php
                            $deliveryPerSeller = 10.60;

                            // Filter only items belonging to this seller
                            $sellerItems = $order->items->filter(
                                fn($item) => $item->product && $item->product->seller_id == $sellerId
                            );

                            // Skip if no items for this seller
                            if ($sellerItems->count() <= 0)
                                continue;

                            // Product subtotal
                            $productSubtotal = $sellerItems->sum(fn($item) => $item->price * $item->quantity);

                            // Final seller total (subtotal + delivery)
                            $sellerTotal = $productSubtotal + $deliveryPerSeller;

                            // Get the first seller item status to display
                            $sellerStatus = $sellerItems->first()->seller_status;

                            $sellerStatusNormalized = ucfirst(strtolower(trim($sellerStatus)));

$statusColors = [
    'Pending' => ['bg' => 'bg-warning', 'text' => 'text-dark', 'icon' => 'clock'],
    'Paid' => ['bg' => 'bg-info', 'text' => 'text-white', 'icon' => 'credit-card'],
    'Shipped' => ['bg' => 'bg-primary', 'text' => 'text-white', 'icon' => 'truck'],
    'Delivered' => ['bg' => 'bg-success', 'text' => 'text-white', 'icon' => 'check-circle'],
    'Completed' => ['bg' => 'bg-success', 'text' => 'text-white', 'icon' => 'check-circle'],
    'Cancelled' => ['bg' => 'bg-danger', 'text' => 'text-white', 'icon' => 'x-circle']
];

$statusInfo = $statusColors[$sellerStatusNormalized] ?? ['bg' => 'bg-secondary', 'text' => 'text-white', 'icon' => 'question-circle'];

                        @endphp

                        <div class="card mb-4 border-0 shadow-sm rounded-3 overflow-hidden">
                            <!-- Order Header -->
                            <div class="card-header border-0 py-3" style="background-color: #f8f9fa;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3 {{ $statusInfo['bg'] }} {{ $statusInfo['text'] }}"
                                                style="width: 40px; height: 40px;">
                                                <i class="bi bi-{{ $statusInfo['icon'] }}"></i>
                                            </div>
                                            <div>
                                                <h5 class="fw-bold mb-1 text-dark">
                                                    Order #{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}
                                                </h5>
                                                <small class="text-muted">
    <i class="bi bi-calendar me-1"></i>
    {{ $order->created_at->setTimezone('Asia/Kuala_Lumpur')->format('d M Y, h:i A') }}
</small>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="badge {{ $statusInfo['bg'] }} {{ $statusInfo['text'] }} px-3 py-2 fs-6">
                                            {{ $sellerStatus }}
                                        </div>
                                        <div class="mt-2">
                                            <small class="text-muted">Order Total:</small>
                                            <div class="fw-bold fs-5" style="color: #5C7F51;">
                                                RM {{ number_format($sellerTotal, 2) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <!-- Buyer Information -->
                                <div class="row mb-4">
                                    <div class="col-md-8">
                                        <div class="buyer-info p-3 rounded" style="background-color: #f8f9fa;">
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="rounded-circle me-3 overflow-hidden"
                                                    style="width: 40px; height: 40px; border: 2px solid #5C7F51;">
                                                    <img src="{{ $order->buyer->profile_picture
                        ? asset($order->buyer->profile_picture)
                        : asset('images/default.png') }}" alt="{{ $order->buyer->name ?? 'Buyer' }}"
                                                        style="width: 100%; height: 100%; object-fit: cover;">
                                                </div>

                                                <div>
                                                    <h6 class="fw-bold mb-0 text-dark">{{ $order->buyer->name ?? 'Unknown Buyer' }}
                                                    </h6>
                                                    <small class="text-muted">Customer</small>
                                                </div>
                                            </div>
                                            <div class="ps-5">
                                                <p class="mb-1">
                                                    <i class="bi bi-geo-alt me-2"></i>
                                                    <span
                                                        class="text-dark">{{ $order->buyer->address ?? 'No address provided' }}</span>
                                                </p>
                                                @if($order->buyer->phone)
                                                    <p class="mb-0">
                                                        <i class="bi bi-telephone me-2"></i>
                                                        <span class="text-dark">{{ $order->buyer->phone }}</span>
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="order-summary p-3 rounded"
                                            style="background-color: #f0f7f0; border: 1px solid #e0e0e0;">
                                            <h6 class="fw-bold mb-3 text-dark">Order Summary</h6>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-muted">Items ({{ $sellerItems->count() }})</span>
                                                <span class="fw-semibold">RM {{ number_format($productSubtotal, 2) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-muted">Delivery</span>
                                                <span class="fw-semibold">RM {{ number_format($deliveryPerSeller, 2) }}</span>
                                            </div>
                                            <hr class="my-2">
                                            <div class="d-flex justify-content-between">
                                                <span class="fw-bold text-dark">Total</span>
                                                <span class="fw-bold fs-5" style="color: #5C7F51;">
                                                    RM {{ number_format($sellerTotal, 2) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Products -->
                                <h6 class="fw-bold mb-3 text-dark">
                                    <i class="bi bi-box me-2"></i>Ordered Products ({{ $sellerItems->count() }})
                                </h6>

                                <div class="products-list">
                                    @foreach($sellerItems as $item)
                                                <div class="product-item d-flex align-items-center p-3 mb-2 rounded"
                                                    style="background-color: #f8f9fa;">
                                                    <div class="product-image me-3">
                                                        <img src="{{ $item->product->images->first()
                                        ? asset('images/' . $item->product->images->first()->image_path)
                                        : asset('images/default.png') }}" class="rounded"
                                                            style="width: 80px; height: 80px; object-fit: cover; border: 2px solid #e0e0e0;">
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="fw-semibold mb-1 text-dark">{{ $item->product->product_name }}</h6>
                                                        <div class="d-flex flex-wrap align-items-center gap-3">
                                                            <span class="badge bg-light text-dark">
                                                                <i class="bi bi-tag me-1"></i>
                                                                {{ $item->variant && $item->variant !== '' ? $item->variant : 'Standard' }}
                                                            </span>
                                                            <span class="text-muted">
                                                                <i class="bi bi-x-square me-1"></i>
                                                                Qty: {{ $item->quantity }}
                                                            </span>
                                                            <span class="text-muted">
                                                                <i class="bi bi-currency-dollar me-1"></i>
                                                                RM {{ number_format($item->price, 2) }} each
                                                            </span>
                                                        </div>

                                                        <!-- Seller Status -->
                                                        <p class="text-success mt-1">Status: {{ $item->seller_status }}</p>

                                                    </div>
                                                    <div class="text-end">
                                                        <div class="fw-bold fs-5 mb-1" style="color: #5C7F51;">
                                                            RM {{ number_format($item->price * $item->quantity, 2) }}
                                                        </div>
                                                        <small class="text-muted">
                                                            {{ $item->quantity }} × RM{{ number_format($item->price, 2) }}
                                                        </small>
                                                    </div>
                                                </div>
                                    @endforeach
                                </div>

                                @php
    // Get this seller's delivery for this order
    $delivery = $order->deliveries->where('seller_id', $sellerId)->first();
@endphp

<!-- Actions -->
<div class="mt-4 pt-3 border-top">

{{-- PENDING --}}
@if($sellerStatusNormalized === 'Pending')
    <form action="{{ route('sellers.orders.paid', $order->id) }}" method="POST">
        @csrf
        <button class="btn btn-primary rounded-pill px-4 py-2 shadow-sm">
            <i class="bi bi-check-circle me-2"></i>
            Mark as Paid
        </button>
    </form>

{{-- PAID → SHOW TRACKING FORM --}}
@elseif($sellerStatusNormalized === 'Paid' && !$delivery)
    <div class="shipping-form">
        <h6 class="fw-bold mb-3 text-dark">
            <i class="bi bi-truck me-2"></i>Ship Order
        </h6>

        <form action="{{ route('sellers.deliveries.store', $order->id) }}"
              method="POST" class="row g-3 align-items-end">
            @csrf
            <input type="hidden" name="courier_name" value="J&T Express">

            <div class="col-md-6">
                <label class="form-label fw-semibold">Tracking Number</label>
                <input type="text" name="tracking_number"
                       class="form-control form-control-lg rounded-pill"
                       placeholder="Enter tracking number" required>
            </div>

            <div class="col-md-6">
                <button class="btn btn-success rounded-pill px-4 py-2 w-100">
                    <i class="bi bi-truck me-2"></i>
                    Confirm Shipment
                </button>
            </div>
        </form>
    </div>

{{-- SHIPPED --}}
@elseif($sellerStatusNormalized === 'Shipped' && $delivery)
    <div class="delivery-info p-3 rounded mb-3" style="background-color: #e8f5e9;">
        <h6 class="fw-bold mb-1 text-dark">Order Shipped</h6>

        <p class="mb-1 text-muted">
            {{ $delivery->courier_name }} •
            <strong>{{ $delivery->tracking_number }}</strong>
        </p>

        @if($delivery->shipped_at)
            <small class="text-muted">
                Shipped on {{ $delivery->shipped_at->format('d M Y') }}
            </small>
        @endif
    </div>

    @php
    // Get this seller's delivery
    $delivery = $order->deliveries->where('seller_id', $sellerId)->first();
@endphp

@if($delivery && !$delivery->delivered_at)
    <form action="{{ route('sellers.deliveries.delivered', $delivery->id) }}" method="POST">
        @csrf
        @method('PATCH')
        <button class="btn btn-success rounded-pill px-4 py-2 shadow-sm">
            <i class="bi bi-check-circle me-2"></i>
            Mark as Delivered
        </button>
    </form>
@endif


{{-- DELIVERED --}}
@elseif($sellerStatusNormalized === 'Delivered' && $delivery)
    <div class="delivery-info p-3 rounded" style="background-color: #e8f5e9;">
        <h6 class="fw-bold mb-1 text-dark">Order Delivered</h6>

        <p class="mb-0 text-muted">
            {{ $delivery->courier_name }} •
            <strong>{{ $delivery->tracking_number }}</strong>
        </p>

        <small class="text-success">
            Delivered on {{ $delivery->delivered_at->format('d M Y') }}
        </small>
    </div>
@endif

</div>


                            </div>
                        </div>
                    @endforeach

               

                @else
                            <!-- Empty State -->
                            <div class="text-center py-5">
                                <div class="empty-state-icon mb-4">
                                    <i class="bi bi-cart-x display-1" style="color: #dee2e6;"></i>
                                </div>
                                <h4 class="text-muted mb-3">No Orders Yet</h4>
                                <p class="text-muted mb-4">You haven't received any orders. When customers purchase your
                                    products, they'll appear here.</p>
                                <a href="{{ route('sellers.dashboard') }}" class="btn btn-primary rounded-pill px-4 py-2">
                                    <i class="bi bi-arrow-left me-2"></i>
                                    Back to Dashboard
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <style>
                .card {
                    transition: transform 0.3s ease, box-shadow 0.3s ease;
                }

                .card:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
                }

                .rounded-3 {
                    border-radius: 0.75rem !important;
                }

                .badge {
                    font-weight: 500;
                    letter-spacing: 0.5px;
                }

                .btn-primary {
                    background: linear-gradient(135deg, #4a90e2, #63a4ff);
                    border: none;
                    transition: all 0.3s ease;
                }

                .btn-primary:hover {
                    background: linear-gradient(135deg, #3a80d2, #5394ff);
                    transform: translateY(-2px);
                    box-shadow: 0 4px 15px rgba(74, 144, 226, 0.3);
                }

                .btn-success {
                    background: linear-gradient(135deg, #5C7F51, #6d9c5e);
                    border: none;
                    transition: all 0.3s ease;
                }

                .btn-success:hover {
                    background: linear-gradient(135deg, #4C6F41, #5d8c4e);
                    transform: translateY(-2px);
                    box-shadow: 0 4px 15px rgba(92, 127, 81, 0.3);
                }

                .product-item {
                    transition: all 0.3s ease;
                }

                .product-item:hover {
                    background-color: #f0f7f0 !important;
                    transform: translateX(5px);
                }

                .form-control:focus {
                    border-color: #5C7F51 !important;
                    box-shadow: 0 0 0 0.25rem rgba(92, 127, 81, 0.25) !important;
                }

                .empty-state-icon {
                    animation: float 3s ease-in-out infinite;
                }

                @keyframes float {

                    0%,
                    100% {
                        transform: translateY(0px);
                    }

                    50% {
                        transform: translateY(-10px);
                    }
                }

                .status-icon {
                    width: 40px;
                    height: 40px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border-radius: 50%;
                }

                @media (max-width: 768px) {
                    .card-body {
                        padding: 1.25rem !important;
                    }

                    .row {
                        margin-left: -0.75rem;
                        margin-right: -0.75rem;
                    }

                    .btn {
                        width: 100% !important;
                        margin-bottom: 0.5rem;
                    }
                }
            </style>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    // Auto-dismiss alerts after 5 seconds
                    setTimeout(() => {
                        document.querySelectorAll('.alert').forEach(alert => {
                            const bsAlert = new bootstrap.Alert(alert);
                            bsAlert.close();
                        });
                    }, 5000);

                    // Add animation to order cards on load
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