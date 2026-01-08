@extends('layouts.main')

@section('title', 'My Cart')

@section('content')
    <div class="container py-5">
        <!-- Progress Tracker -->
        <div class="d-flex justify-content-center mb-5">
            <div class="d-flex align-items-center position-relative" style="max-width: 500px; width: 100%;">
                <!-- Progress Line -->
                <div class="progress position-absolute w-100"
                    style="height: 3px; top: 15px; z-index: 1; background-color: #e9ecef;">
                    <div class="progress-bar" style="width: 33%; background-color: #8a9c6a;"></div>
                </div>

                <!-- Steps -->
                <div class="d-flex justify-content-between w-100 position-relative" style="z-index: 2;">
                    <div class="text-center">
                        <div class="rounded-circle text-white d-flex align-items-center justify-content-center mx-auto mb-2"
                            style="width: 40px; height: 40px; background-color: #8a9c6a;">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <span class="small fw-semibold text-dark">Cart</span>
                    </div>
                    <div class="text-center">
                        <div class="rounded-circle bg-white border border-secondary text-secondary d-flex align-items-center justify-content-center mx-auto mb-2"
                            style="width: 40px; height: 40px;">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <span class="small text-muted">Checkout</span>
                    </div>
                    <div class="text-center">
                        <div class="rounded-circle bg-white border border-secondary text-secondary d-flex align-items-center justify-content-center mx-auto mb-2"
                            style="width: 40px; height: 40px;">
                            <i class="fas fa-truck"></i>
                        </div>
                        <span class="small text-muted">Complete</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Header -->
        <div class="text-center mb-5">
            <h1 class="fw-bold text-dark mb-3">Your Shopping Cart</h1>
            <p class="text-secondary">Review your items before checkout.</p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-light border-0 shadow-sm mb-4" role="alert" style="background-color: #f8f9fa;">
                <i class="fas fa-check-circle me-2" style="color: #8a9c6a;"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($groupedCart->isNotEmpty())
            <div class="row g-4">
                <!-- Cart Items -->
                <div class="col-lg-8">
                    <div class="card border-light shadow-sm rounded-3">
                        <div class="card-body p-4">
                            @php $total = 0; @endphp

                            @foreach($groupedCart as $sellerId => $items)
                                @php
                                    $seller = $items->first()->product->seller;
                                    $sellerSubtotal = 0;
                                @endphp

                                <!-- Seller Section -->
                                <div class="border-bottom pb-4 mb-4" style="border-color: #e9ecef !important;">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="rounded-2 p-2 me-2" style="background-color: #e8f0e8;">
                                            <i class="fas fa-store" style="color: #8a9c6a;"></i>
                                        </div>
                                        <small class="fw-medium text-dark">{{ $seller->business_name ?? 'Unknown Seller' }}</small>
                                    </div>

                                    <!-- Products for this seller -->
                                    @foreach($items as $item)
                                        @php
                                            $price = $item->product->price;
                                            $subtotal = $price * $item->quantity;
                                            $total += $subtotal;
                                            $sellerSubtotal += $subtotal;
                                        @endphp
                                        <div class="row align-items-center mb-3">
                                            <!-- Image -->
                                            <div class="col-3 col-md-2">
                                                <img src="{{ $item->image_url }}" class="rounded-3 img-fluid border"
                                                    style="width: 80px; height: 80px; object-fit: cover; border-color: #e9ecef !important;">
                                            </div>
                                            <!-- Details -->
                                            <div class="col-9 col-md-4">
                                                <h6 class="fw-bold text-dark mb-1">{{ $item->product->product_name }}</h6>
                                                <p class="fw-semibold mb-1" style="color: #8a9c6a;">RM {{ number_format($subtotal, 2) }}
                                                </p>
                                                <p class="text-muted small mb-2">RM {{ number_format($price, 2) }} each</p>
                                                <div class="text-secondary small">
                                                    <i class="fas fa-tag me-1"></i>
                                                    {{ $item->variant && $item->variant !== '' ? $item->variant : 'Standard' }}
                                                </div>
                                            </div>
                                            <!-- Quantity -->
                                            <div class="col-6 col-md-3 mt-3 mt-md-0">
                                                <div class="d-flex align-items-center rounded-pill p-1"
                                                    style="max-width: 120px; background-color: #f8f9fa;">
                                                    <form action="{{ route('cart.update', $item->id) }}" method="POST"
                                                        class="w-100 d-flex">
                                                        @csrf @method('PUT')
                                                        <button type="submit" name="quantity" value="{{ $item->quantity - 1 }}"
                                                            class="btn btn-sm border-0 rounded-circle p-0 {{ $item->quantity <= 1 ? 'opacity-50' : '' }}"
                                                            style="width: 30px; height: 30px; background-color: #e8f0e8; color: #8a9c6a;"
                                                            {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                                            <i class="fas fa-minus small"></i>
                                                        </button>
                                                        <span class="flex-grow-1 text-center fw-bold">{{ $item->quantity }}</span>
                                                        <button type="submit" name="quantity" value="{{ $item->quantity + 1 }}"
                                                            class="btn btn-sm border-0 rounded-circle p-0"
                                                            style="width: 30px; height: 30px; background-color: #e8f0e8; color: #8a9c6a;">
                                                            <i class="fas fa-plus small"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                            <!-- Remove -->
                                            <div class="col-6 col-md-3 mt-3 mt-md-0 text-end">
                                                <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-secondary btn-sm px-3 rounded-pill">
                                                        <i class="fas fa-trash-alt me-1"></i> Remove
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach

                                    <!-- Shipping Option -->
                                    @php
                                        $delivery = 10.00; // Example per seller
                                    @endphp
                                    <div class="d-flex justify-content-between mt-3">
                                        <span class="text-secondary">Shipping for {{ $seller->business_name ?? 'Seller' }}</span>
                                        <span class="fw-bold text-dark">RM {{ number_format($delivery, 2) }}</span>
                                    </div>
                                </div>
                            @endforeach

                            <!-- Continue Shopping -->
                            <div class="text-center pt-3">
                                <a href="{{ route('products.browse') }}" class="btn btn-outline-dark rounded-pill px-4">
                                    <i class="fas fa-arrow-left me-2"></i> Continue Shopping
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-lg-4">
                    <div class="card border-light shadow-sm rounded-3 sticky-top" style="top: 20px;">
                        <div class="card-header bg-light border-0 py-3">
                            <div class="d-flex align-items-center">
                                <div class="rounded-2 p-2 me-3" style="background-color: #e8f0e8;">
                                    <i class="fas fa-receipt" style="color: #8a9c6a;"></i>
                                </div>
                                <h5 class="fw-bold text-dark mb-0">Order Summary</h5>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            @php
                                $totalDelivery = $groupedCart->count() * 10; // Example: 10 per seller
                            @endphp

                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-secondary">Products
                                    ({{ $groupedCart->sum(fn($items) => $items->sum('quantity')) }})</span>
                                <span class="fw-bold text-dark">RM {{ number_format($total, 2) }}</span>
                            </div>

                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-secondary">Delivery Price</span>
                                <span class="fw-bold text-dark">RM {{ number_format($totalDelivery, 2) }}</span>
                            </div>

                            <hr class="my-4" style="border-color: #e9ecef !important;">

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div>
                                    <h6 class="fw-bold text-dark mb-1">Total</h6>
                                    <small class="text-secondary">Including delivery</small>
                                </div>
                                <h4 class="fw-bold mb-0" style="color: #8a9c6a;">
                                    RM {{ number_format($total + $totalDelivery, 2) }}
                                </h4>
                            </div>

                            <a href="{{ route('buyer.checkout') }}" class="btn w-100 py-3 rounded-3 fw-bold mb-4 border-0"
                                style="background-color: #8a9c6a; color: white;">
                                <i class="fas fa-shopping-bag me-2"></i> Proceed to Checkout
                            </a>

                            <!-- Payment Methods -->
                            <div class="text-center">
                                <p class="text-secondary small mb-3">Secure Payment Methods</p>
                                <div class="d-flex justify-content-center align-items-center mt-4 gap-2">
                                    <img src="{{ asset('images/tng-logo.jpg') }}" width="35" class="img-fluid">
                                    <img src="{{ asset('images/visa-logo.jpg') }}" width="35" class="img-fluid">
                                    <img src="{{ asset('images/Master-logo.jpg') }}" width="35" class="img-fluid">
                                    <img src="{{ asset('images/apple-logo.jpg') }}" width="35" class="img-fluid">
                                    <img src="{{ asset('images/grab-pay-logo.png') }}" width="35" class="img-fluid">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty Cart -->
            <div class="card border-0 shadow-lg rounded-3">
                <div class="card-body p-5 text-center" style="background-color: #f8f9fa;">
                    <div class="mb-4">
                        <i class="fas fa-shopping-cart" style="font-size: 4rem; color: #8a9c6a;"></i>
                    </div>
                    <h3 class="text-dark mb-3">Your cart is empty</h3>
                    <p class="text-secondary mb-4">Add some products to get started</p>
                    <a href="{{ route('products.browse') }}" class="btn px-4 rounded-3 border-0"
                        style="background-color: #8a9c6a; color: white;">
                        <i class="fas fa-store me-2"></i> Start Shopping
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection