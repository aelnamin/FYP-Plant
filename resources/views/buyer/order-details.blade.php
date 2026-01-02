@extends('layouts.main')

@section('title', 'Order Details')

@section('content')
    <div class="container py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('buyer.profile') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i> Back
            </a>
            <div class="text-end">
                <span class="text-muted">Order ID</span>
                <h5 class="fw-bold mb-0">{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}</h5>
            </div>
        </div>


        <!-- Order Status -->
        @php
            $status = strtoupper($order->status);
            $statusInfo = match ($status) {
                'PENDING' => ['color' => 'warning', 'text' => 'Pending', 'icon' => 'clock'],
                'PAID' => ['color' => 'info', 'text' => 'Paid & Confirmed', 'icon' => 'check-circle'],
                'SHIPPED' => ['color' => 'primary', 'text' => ' You order has been Shipped by seller', 'icon' => 'truck'],
                'COMPLETED', 'DELIVERED' => ['color' => 'success', 'text' => 'Delivered', 'icon' => 'box-seam'],
                'CANCELLED' => ['color' => 'danger', 'text' => 'Cancelled', 'icon' => 'x-circle'],
                default => ['color' => 'secondary', 'text' => $status, 'icon' => 'question-circle'],
            };

            // Calculate totals
            $subtotal = $order->items->sum(fn($i) => $i->price * $i->quantity);
            $deliveryFee = 10.60; // Fixed delivery fee including 6% SST
            $total = $subtotal + $deliveryFee;
        @endphp

        <div class="card shadow-sm mb-4 border-0">
            <div class="card-body text-center py-4">
                <div class="mb-3">
                    <i class="bi bi-{{ $statusInfo['icon'] }} text-{{ $statusInfo['color'] }} fs-1"></i>
                </div>
                <h4 class="fw-bold text-{{ $statusInfo['color'] }} mb-2">{{ $statusInfo['text'] }}</h4>
                <p class="text-muted mb-0">Order placed on {{ $order->created_at->format('M d, Y') }}</p>
            </div>
        </div>

        <!-- Info Cards -->
        <div class="row g-3 mb-4">
            <!-- Delivery Info -->
            <div class="col-md-6">
                <div class="card h-100 shadow-sm border">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3"><i class="bi bi-geo-alt me-2"></i>Delivery Information</h6>
                        <p class="mb-1"><strong>{{ $order->buyer->name }}</strong></p>
                        <p class="mb-1 text-muted small">{{ $order->buyer->phone }}</p>
                        <p class="mb-0 small">{{ $order->buyer->address }}</p>
                    </div>
                </div>
            </div>

            <!-- Shipping Info -->
            <div class="col-md-6">
                <div class="card h-100 shadow-sm border">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3"><i class="bi bi-truck me-2"></i>Shipping Information</h6>
                        <p class="mb-1"><strong>{{ $order->shipping_method ?? 'Standard Shipping' }}</strong></p>
                        @if($order->tracking_number)
                            <p class="mb-1 text-muted small">Tracking: {{ $order->tracking_number }}</p>
                        @endif
                        @if($order->delivered_at)
                            <p class="mb-0 text-success small"><i class="bi bi-check-circle me-1"></i>Delivered
                                {{ $order->delivered_at->format('M d') }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="card shadow-sm mb-4 border">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Order Items</h6>

                <hr>

                @foreach($order->items->groupBy(fn($i) => $i->product->seller_id) as $items)
                    @php $seller = $items->first()->product->seller; @endphp

                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex align-items-center mb-2">
                            <span class="fw-bold">{{ $seller->business_name ?? 'Seller' }}</span>
                        </div>


                        @foreach($items as $item)
                            @php $product = $item->product; @endphp
                            <div class="d-flex align-items-center mb-2">
                                <img src="{{ $product->images->first() ? asset('images/' . $product->images->first()->image_path) : asset('images/default.png') }}"
                                    alt="{{ $product->product_name }}" class="rounded me-3" width="60" height="60">
                                <div class="flex-grow-1">
                                    <p class="mb-1">{{ $product->product_name }}</p>
                                    <small class="text-muted">{{ $item->quantity }} × RM
                                        {{ number_format($item->price, 2) }}</small>
                                </div>
                                <div class="text-end">
                                    <strong class="text-success">RM
                                        {{ number_format($item->price * $item->quantity, 2) }}</strong>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Order Summary -->
        <div class="card shadow-sm mb-4 border">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Order Summary</h6>
                <div class="summary-item mb-2">
                    <span>Subtotal</span>
                    <span class="fw-semibold">RM {{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="summary-item mb-2">
                    <span>Delivery (inc. 6% SST)</span>
                    <span class="fw-semibold">RM {{ number_format($deliveryFee, 2) }}</span>
                </div>
                <hr class="my-3">
                <div class="summary-item total">
                    <span class="fw-bold">Total</span>
                    <span class="fw-bold fs-5">RM {{ number_format($total, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Actions -->
        @if(in_array($status, ['SHIPPED', 'DELIVERED']))
            <div class="d-grid gap-2 mb-4">
                <a href="#" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-clockwise me-2"></i> Request Return/Refund
                </a>

                @foreach($order->items as $item)
                    @php $product = $item->product; @endphp
                    <a href="{{ route('buyer.reviews.create', ['order' => $order->id, 'product' => $product->id]) }}"
                        class="btn btn-primary">
                        <i class="bi bi-star me-2"></i> Rate & Review {{ $product->product_name }}
                    </a>

                @endforeach
            </div>
        @endif

        <!-- Support -->
        @if(in_array($status, ['SHIPPED', 'PAID']))
            <div class="card shadow-sm border">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Need Help?</h6>
                    <div class="d-grid gap-2">

                        @php
                            // Get unique sellers from this order
                            $sellers = $order->items->map(fn($item) => $item->product->seller)
                                ->unique('id');
                        @endphp

                        @foreach($sellers as $seller)
                            <form action="{{ route('buyer.chats.start', $seller->user_id) }}" method="GET">
                                <button type="submit" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-chat-dots me-1"></i> Contact {{ $seller->business_name }}
                                </button>
                            </form>
                        @endforeach

                        <button class="btn btn-outline-secondary text-start">
                            <i class="bi bi-question-circle me-2"></i> Help Center
                        </button>
                    </div>
                </div>
            </div>
        @endif

    </div> <!-- End of grey bordered container -->
    </div>

    <style>
        .card {
            border-radius: 10px;
        }

        .card-body {
            padding: 1.25rem;
        }

        .btn-outline-secondary {
            border-color: #dee2e6;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .summary-item.total {
            margin-top: 1rem;
        }
    </style>
@endsection