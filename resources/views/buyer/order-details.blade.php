@extends('layouts.main')

@section('title', 'Order Details')

@section('content')
    <div class="container py-5">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-5">
            <a href="{{ route('buyer.profile') }}" class="btn btn-outline-dark rounded-pill px-4">
                <i class="fas fa-arrow-left me-2"></i> Back
            </a>
            <div class="text-end">
                <span class="text-secondary small d-block">Order ID</span>
                <h5 class="fw-bold text-dark mb-0">#{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}</h5>
            </div>
        </div>

        @php
            $status = $sellerId
                ? $order->items->firstWhere('product.seller_id', $sellerId)->seller_status ?? 'Pending'
                : $order->status ?? 'Pending';

            $statusInfo = match (strtoupper($status)) {
                'PENDING' => ['bg' => 'bg-warning', 'textColor' => 'text-dark', 'text' => 'Pending', 'icon' => 'clock'],
                'PAID' => ['bg' => 'bg-info', 'textColor' => 'text-white', 'text' => 'Paid', 'icon' => 'check-circle'],
                'SHIPPED' => ['bg' => 'bg-light', 'textColor' => 'text-dark', 'text' => 'Your order has been shipped by seller', 'icon' => 'truck'],
                'DELIVERED', 'COMPLETED' => ['bg' => 'bg-light', 'textColor' => 'text-white', 'text' => 'Your product has arrived', 'icon' => 'truck'],
                'CANCELLED' => ['bg' => 'bg-danger', 'textColor' => 'text-white', 'text' => 'Cancelled', 'icon' => 'x-circle'],
                default => ['bg' => 'bg-secondary', 'textColor' => 'text-white', 'text' => 'Unknown', 'icon' => 'question-circle'],
            };
        @endphp


        <div class="row g-4">
            <!-- Left Column -->
            <div class="col-lg-7">
                <!-- Order Status -->
                <div class="card border-light shadow-sm rounded-3 mb-4">
                    <div class="card-body p-4 text-center">
                        <div class="{{ $statusInfo['bg'] }} rounded-circle d-inline-flex align-items-center justify-content-center p-3 mb-3"
                            style="width: 60px; height: 60px;">
                            <i class="fas fa-{{ $statusInfo['icon'] }} fs-4"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-2">{{ $statusInfo['text'] }}</h4>
                        <p class="text-secondary mb-0">
                            <i class="fas fa-calendar me-1"></i>
                            Ordered on {{ $order->created_at->format('M d, Y') }}
                        </p>
                    </div>
                </div>

                <!-- Delivery & Shipping Info -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="card border-light shadow-sm rounded-3 h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-2 p-2 me-2" style="background-color: #e8f0e8;">
                                        <i class="fas fa-user" style="color: #8a9c6a;"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-0">Delivery Information</h6>
                                </div>
                                <div class="ps-4">
                                    <p class="fw-semibold text-dark mb-1">{{ $order->buyer->name }}</p>
                                    <p class="text-secondary small mb-1">
                                        <i class="fas fa-phone me-1"></i>{{ $order->buyer->phone }}
                                    </p>
                                    <p class="text-secondary small mb-0">
                                        <i class="fas fa-map-marker-alt me-1"></i>{{ $order->buyer->address }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-light shadow-sm rounded-3 h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-2 p-2 me-2" style="background-color: #e8f0e8;">
                                        <i class="bi bi-card-text" style="color: #8a9c6a;"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-0">Shipping Information</h6>
                                </div>
                                <div class="ps-4">
                                    <!-- Shipping Method -->
                                    <p class="fw-semibold text-dark mb-1">
                                        {{ $order->shipping_method ?? 'Standard Shipping' }}
                                    </p>

                                    @php
                                        // Collect possible tracking sources (priority order)
                                        $delivery = collect([
                                            $order->delivery,
                                            ...$order->items->pluck('delivery')
                                        ])->first(fn($d) => optional($d)->tracking_number);

                                        $trackingNumber = $delivery?->tracking_number;
                                        $courierName = $delivery?->courier_name ?? 'Courier';

                                        $shippedAt = $order->shipped_at ?? $delivery?->shipped_at;
                                        $deliveredAt = $order->delivered_at ?? $delivery?->delivered_at;
                                    @endphp


                                    @if($delivery)
                                        <p class="text-secondary small mb-1">
                                            <i class="fas fa-truck me-1"></i>
                                            {{ $delivery->courier_name ?? 'Courier not specified' }} •
                                            <strong>#{{ $delivery->tracking_number ?? 'N/A' }}</strong>
                                        </p>

                                        @if($delivery->shipped_at)
                                            <p class="text-secondary small mb-0">
                                                <i class="fas fa-calendar-alt me-1"></i>
                                                Shipped on {{ $delivery->shipped_at->format('M d, Y') }}
                                            </p>
                                        @endif

                                        @if($delivery->delivered_at)
                                            <p class="text-success small mb-0 mt-1">
                                                <i class="fas fa-check-circle me-1"></i>
                                                Delivered on {{ $delivery->delivered_at->format('M d, Y') }}
                                            </p>
                                        @endif
                                    @else
                                        <p class="text-muted small mb-0">Your order has not been shipped yet.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="card border-light shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="rounded-2 p-2 me-2" style="background-color: #e8f0e8;">
                                <i class="fas fa-box" style="color: #8a9c6a;"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-0">Order Items</h6>
                        </div>
                        <hr>
                        <!-- Group by seller if not filtered -->
                        @if(!$sellerId)
                            @foreach($items->groupBy(fn($item) => $item->product->seller_id) as $sellerId => $sellerItems)
                                @php $seller = $sellerItems->first()->product->seller; @endphp
                                <div class="mb-4 pb-4 border-bottom" style="border-color: #e9ecef !important;">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="rounded-2 p-1 me-2" style="background-color: #f8f9fa;">
                                            <i class="fas fa-store" style="color: #6c757d;"></i>
                                        </div>
                                        <span class="fw-bold text-dark">{{ $seller->business_name ?? 'Seller' }}</span>
                                        <a href="{{ route('buyer.orders.show', ['order' => $order->id, 'seller' => $sellerId]) }}"
                                            class="btn btn-sm btn-outline-secondary ms-auto">
                                            View Seller Items Only
                                        </a>
                                    </div>

                                    @foreach($sellerItems as $item)
                                        @php $product = $item->product; @endphp
                                        <div class="d-flex align-items-start mb-3">
                                            <img src="{{ $product->images->first() ? asset('images/' . $product->images->first()->image_path) : asset('images/default.png') }}"
                                                alt="{{ $product->product_name }}" class="rounded-3 me-3"
                                                style="width: 60px; height: 60px; object-fit: cover; border: 1px solid #e9ecef;">
                                            <div class="flex-grow-1">
                                                <h6 class="fw-semibold text-dark mb-1">{{ $product->product_name }}</h6>
                                                <div class="text-secondary small">
                                                    <i class="fas fa-tag me-1"></i>
                                                    {{ $item->variant && $item->variant !== '' ? $item->variant : 'Standard' }}
                                                </div>
                                                <p class="text-secondary small mb-1">Qty: {{ $item->quantity }}</p>
                                                <p class="text-secondary small mb-0">RM {{ number_format($item->price, 2) }} each</p>
                                            </div>
                                            <div class="text-end">
                                                <p class="fw-bold mb-0" style="color: #8a9c6a;">
                                                    RM {{ number_format($item->price * $item->quantity, 2) }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        @else
                            <!-- Show filtered items for specific seller -->
                            @if($selectedSeller)
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-2 p-1 me-2" style="background-color: #f8f9fa;">
                                        <i class="fas fa-store" style="color: #6c757d;"></i>
                                    </div>
                                    <span class="fw-bold text-dark">{{ $selectedSeller->business_name ?? 'Seller' }}</span>
                                </div>
                            @endif

                            @foreach($items as $item)
                                @php $product = $item->product; @endphp
                                <div class="d-flex align-items-start mb-3 pb-3 border-bottom"
                                    style="border-color: #e9ecef !important;">
                                    <img src="{{ $product->images->first() ? asset('images/' . $product->images->first()->image_path) : asset('images/default.png') }}"
                                        alt="{{ $product->product_name }}" class="rounded-3 me-3"
                                        style="width: 60px; height: 60px; object-fit: cover; border: 1px solid #e9ecef;">
                                    <div class="flex-grow-1">
                                        <h6 class="fw-semibold text-dark mb-1">{{ $product->product_name }}</h6>
                                        <div class="text-secondary small">
                                            <i class="fas fa-tag me-1"></i>
                                            {{ $item->variant && $item->variant !== '' ? $item->variant : 'Standard' }}
                                        </div>
                                        <p class="text-secondary small mb-1">Qty: {{ $item->quantity }}</p>
                                        <p class="text-secondary small mb-0">RM {{ number_format($item->price, 2) }} each</p>
                                    </div>
                                    <div class="text-end">
                                        <p class="fw-bold mb-0" style="color: #8a9c6a;">
                                            RM {{ number_format($item->price * $item->quantity, 2) }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-5">
                <!-- Order Summary -->
                <div class="card border-light shadow-sm rounded-3 mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="rounded-2 p-2 me-2" style="background-color: #e8f0e8;">
                                <i class="fas fa-receipt" style="color: #8a9c6a;"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-0">Order Summary</h6>
                        </div>

                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-secondary">Subtotal</span>
                            <span class="fw-semibold text-dark">RM {{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-secondary">Delivery (incl. 6% SST)</span>
                            <span class="fw-semibold text-dark">RM {{ number_format($deliveryFee, 2) }}</span>
                        </div>
                        <hr class="my-3" style="border-color: #e9ecef;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold text-dark mb-1">Total</h6>
                                <small class="text-secondary">Including delivery</small>
                            </div>
                            <h4 class="fw-bold mb-0" style="color: #8a9c6a;">RM {{ number_format($total, 2) }}</h4>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                @php
                    // Normalize status for comparison
                    $normalizedStatus = strtoupper(trim($status));
                    $showActions = in_array($normalizedStatus, ['SHIPPED', 'DELIVERED', 'PAID', 'COMPLETED']);
                @endphp

                @if($showActions)
                    <div class="card border-light shadow-sm rounded-3">
                        <div class="card-body p-4">
                        @if($normalizedStatus === 'SHIPPED' || $normalizedStatus === 'DELIVERED')
    <!-- Mark as Received Button -->
    <div class="d-grid gap-2 mb-4">
        <form action="{{ route('buyer.orders.received', $order->id) }}{{ $sellerId ? '?seller=' . $sellerId : '' }}" method="POST">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-outline-success rounded-pill py-2">
                Order Received
            </button>
        </form>
    </div>
@endif


                            @if(in_array($normalizedStatus, ['SHIPPED', 'DELIVERED', 'COMPLETED']))
                                <div class="d-grid gap-2 mb-4">
                                    @if($sellerId)
                                        <!-- Return/Refund for specific seller -->
                                        <a href="#" class="btn btn-outline-dark rounded-pill py-2">
                                            <i class="fas fa-undo me-2"></i> Return/Refund for
                                            {{ $selectedSeller->business_name ?? 'Seller' }}
                                        </a>
                                    @else
                                        <a href="#" class="btn btn-outline-dark rounded-pill py-2">
                                            <i class="fas fa-undo me-2"></i> Request Return/Refund
                                        </a>
                                    @endif

                                    @foreach($items as $item)
                                        @php $product = $item->product; @endphp
                                        <a href="{{ route('buyer.reviews.create', ['order' => $order->id, 'product' => $product->id]) }}{{ $sellerId ? '?seller=' . $sellerId : '' }}"
                                            class="btn rounded-pill py-2 border-0" style="background-color: #8a9c6a; color: white;">
                                            <i class="bi bi-star me-2"></i> Rate {{ $product->product_name }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Support Center - ALWAYS SHOW FOR DELIVERED/COMPLETED ORDERS -->
                            @if(in_array($normalizedStatus, ['DELIVERED', 'COMPLETED', 'SHIPPED', 'PAID']))
                                <div>
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="rounded-2 p-2 me-2" style="background-color: #e8f0e8;">
                                            <i class="fas fa-headset" style="color: #8a9c6a;"></i>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-0">Support Center</h6>
                                    </div>
                                    <div class="d-grid gap-2">
                                        @if($sellerId && $selectedSeller)
                                            <form action="{{ route('buyer.chats.start', $selectedSeller->user_id) }}" method="GET">
                                                <button type="submit" class="btn btn-outline-secondary rounded-pill w-100">
                                                    <i class="fas fa-comment me-2"></i> Contact {{ $selectedSeller->business_name }}
                                                </button>
                                            </form>
                                        @else
                                            @foreach($order->items->map(fn($item) => $item->product->seller)->unique('id') as $seller)
                                                <form action="{{ route('buyer.chats.start', $seller->user_id) }}" method="GET">
                                                    <button type="submit" class="btn btn-outline-secondary rounded-pill w-100">
                                                        <i class="fas fa-comment me-2"></i> Contact {{ $seller->business_name }}
                                                    </button>
                                                </form>
                                            @endforeach
                                        @endif

                                        <a href="{{ route('buyer.help-center') }}" class="btn btn-outline-secondary rounded-pill">
                                            <i class="fas fa-question-circle me-2"></i> Help Center
                                        </a>

                                        <a href="{{ route('buyer.transactions.show', $order->id) }}{{ $sellerId ? '?seller=' . $sellerId : '' }}"
                                            class="btn btn-outline-secondary rounded-pill">
                                            <i class="fas fa-receipt me-2"></i> View Transaction
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <style>
                    .card {
                        transition: all 0.3s ease;
                        border: 1px solid #e9ecef !important;
                    }

                    .card:hover {
                        transform: translateY(-2px);
                        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
                    }

                    .rounded-3 {
                        border-radius: 0.75rem !important;
                    }

                    .btn:hover {
                        transform: translateY(-2px);
                        transition: all 0.3s ease;
                    }

                    .btn[style*="background-color: #8a9c6a"]:hover {
                        background-color: #7a8b5a !important;
                        box-shadow: 0 4px 12px rgba(138, 156, 106, 0.2);
                    }

                    .btn-outline-dark:hover {
                        background-color: #212529;
                        color: white;
                    }

                    .btn-outline-secondary:hover {
                        background-color: #6c757d;
                        color: white;
                    }
                </style>
@endsection