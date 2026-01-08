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
            $status = strtoupper($order->status);
            $statusInfo = match ($status) {
                'PENDING' => ['bg' => 'bg-warning', 'textColor' => 'text-dark', 'text' => 'Pending', 'icon' => 'clock'],
                'PAID' => ['bg' => 'bg-info', 'textColor' => 'text-white', 'text' => 'Paid', 'icon' => 'check-circle'],
                'SHIPPED' => ['bg' => 'bg-light', 'textColor' => 'text-dark', 'text' => 'Your order has been shipped by seller', 'icon' => 'truck'],
                'DELIVERED', 'COMPLETED' => ['bg' => 'bg-light', 'textColor' => 'text-white', 'text' => 'Your product has arrived', 'icon' => 'truck'],
                'CANCELLED' => ['bg' => 'bg-danger', 'textColor' => 'text-white', 'text' => 'Cancelled', 'icon' => 'x-circle'],
                default => ['bg' => 'bg-secondary', 'textColor' => 'text-white', 'text' => 'Unknown', 'icon' => 'question-circle'],
            };

            $subtotal = $order->items->sum(fn($i) => $i->price * $i->quantity);
            $deliveryFee = 10.60;
            $total = $subtotal + $deliveryFee;
        @endphp

        <div class="row g-4">
            <!-- Left Column -->
            <div class="col-lg-7">
                <!-- Order Status -->
                <div class="card border-light shadow-sm rounded-3 mb-4">
                    <div class="card-body p-4 text-center">
                        <div class="{{ $statusInfo['bg'] }} {{ $statusInfo['text'] }} rounded-circle d-inline-flex align-items-center justify-content-center p-3 mb-3"
                            style="width: 60px; height: 60px;">
                            <i class="fas fa-{{ $statusInfo['icon'] }} fs-4"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-2">
                            {{ $statusInfo['text'] === 'text-dark' ? 'Pending' : $statusInfo['text'] }}
                        </h4>
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
                                        <i class="fas fa-truck" style="color: #8a9c6a;"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-0">Shipping Information</h6>
                                </div>
                                <div class="ps-4">
                                    <p class="fw-semibold text-dark mb-1">
                                        {{ $order->shipping_method ?? 'Standard Shipping' }}
                                    </p>
                                    @if($order->tracking_number)
                                        <p class="text-secondary small mb-1">
                                            <i class="fas fa-barcode me-1"></i>{{ $order->tracking_number }}
                                        </p>
                                    @endif
                                    @if($order->delivered_at)
                                        <p class="text-success small mb-0">
                                            <i class="fas fa-check-circle me-1"></i>
                                            Delivered {{ $order->delivered_at->format('M d') }}
                                        </p>
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

                        @foreach($order->items->groupBy(fn($i) => $i->product->seller_id) as $items)
                            @php $seller = $items->first()->product->seller; @endphp
                            <div class="mb-4 pb-4 border-bottom" style="border-color: #e9ecef !important;">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-2 p-1 me-2" style="background-color: #f8f9fa;">
                                        <i class="fas fa-store" style="color: #6c757d;"></i>
                                    </div>
                                    <span class="fw-bold text-dark">{{ $seller->business_name ?? 'Seller' }}</span>
                                </div>

                                @foreach($items as $item)
                                    @php $product = $item->product; @endphp
                                    <div class="d-flex align-items-start mb-3">
                                        <img src="{{ $product->images->first() ? asset('images/' . $product->images->first()->image_path) : asset('images/default.png') }}"
                                            alt="{{ $product->product_name }}" class="rounded-3 me-3"
                                            style="width: 60px; height: 60px; object-fit: cover; border: 1px solid #e9ecef;">
                                        <div class="flex-grow-1">
                                            <h6 class="fw-semibold text-dark mb-1">{{ $product->product_name }}</h6>
                                            <!-- Variant -->
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
                            <span class="text-secondary">Delivery (inc. 6% SST)</span>
                            <span class="fw-semibold text-dark">RM {{ number_format($deliveryFee, 2) }}</span>
                        </div>
                        <hr class="my-3" style="border-color: #e9ecef !important;">
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
                @if(in_array($status, ['SHIPPED', 'DELIVERED', 'PAID']))
                    <div class="card border-light shadow-sm rounded-3">
                        <div class="card-body p-4">

                            @if($status === 'SHIPPED')
                                <!-- Mark as Received Button -->
                                <div class="d-grid gap-2 mb-4">
                                    <form action="{{ route('buyer.orders.received', $order->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-outline-success rounded-pill py-2">
                                            Order Received
                                        </button>
                                    </form>
                                </div>
                            @endif

                            @if(in_array($status, ['SHIPPED', 'DELIVERED']))
                                <div class="d-grid gap-2 mb-4">
                                    <a href="#" class="btn btn-outline-dark rounded-pill py-2">
                                        <i class="fas fa-undo me-2"></i> Request Return/Refund
                                    </a>

                                    @foreach($order->items as $item)
                                        @php $product = $item->product; @endphp
                                        <a href="{{ route('buyer.reviews.create', ['order' => $order->id, 'product' => $product->id]) }}"
                                            class="btn rounded-pill py-2 border-0" style="background-color: #8a9c6a; color: white;">
                                            <i class="bi bi-star me-2"></i> Rate {{ $product->product_name }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Support Center -->
                            <div>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-2 p-2 me-2" style="background-color: #e8f0e8;">
                                        <i class="fas fa-headset" style="color: #8a9c6a;"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-0">Support Center</h6>
                                </div>
                                <div class="d-grid gap-2">
                                    @php
                                        $sellers = $order->items->map(fn($i) => $i->product->seller)->unique('id');
                                    @endphp
                                    @foreach($sellers as $seller)
                                        <form action="{{ route('buyer.chats.start', $seller->user_id) }}" method="GET">
                                            <button type="submit" class="btn btn-outline-secondary rounded-pill w-100">
                                                <i class="fas fa-comment me-2"></i> Contact {{ $seller->business_name }}
                                            </button>
                                        </form>
                                    @endforeach

                                    <a href="{{ route('buyer.help-center') }}" class="btn btn-outline-secondary rounded-pill">
                                        <i class="fas fa-question-circle me-2"></i> Help Center
                                    </a>

                                    <a href="{{ route('buyer.transactions.show', $order->id) }}"
                                        class="btn btn-outline-secondary rounded-pill">
                                        <i class="fas fa-receipt me-2"></i> View Transaction
                                    </a>
                                </div>
                            </div>
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

                    .sticky-top {
                        position: sticky;
                        z-index: 1;
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

                    .text-dark {
                        color: #212529 !important;
                    }

                    .text-secondary {
                        color: #6c757d !important;
                    }
                </style>
@endsection