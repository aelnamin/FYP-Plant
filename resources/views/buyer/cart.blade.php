@extends('layouts.main')

@section('title', 'My Cart')

@section('content')

<style>
    :root {
        --primary: #4BAE7F;
        --primary-light: #e8f5ef;
        --gradient: linear-gradient(135deg, #4BAE7F 0%, #2d8a60 100%);
    }

    .cart-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem;
    }

    .total-price {
        font-weight: 750;
        font-size: 1.1rem;
    }

    .summary-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 25px 50px rgba(75, 174, 127, 0.15);
        position: sticky;
        top: 2rem;
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .section-icon {
        width: 50px;
        height: 50px;
        background: var(--primary-light);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
        font-size: 1.2rem;
    }

    .progress-tracker {
        display: flex;
        justify-content: center;
        gap: 3rem;
        margin-bottom: 3rem;
        position: relative;
    }

    .progress-tracker::before {
        content: '';
        position: absolute;
        top: 35%;
        left: 15%;
        right: 15%;
        height: 3px;
        background: linear-gradient(to right, var(--primary) 0%, var(--primary) 45%, #e9ecef 0%);
        transform: translateY(-50%);
        z-index: 1;
    }

    .progress-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
        position: relative;
        z-index: 2;
    }

    .step-bubble {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: white;
        border: 3px solid #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.1rem;
        color: #6c757d;
        transition: all 0.3s ease;
    }

    .step-bubble.active {
        background: var(--gradient);
        border-color: var(--primary);
        color: white;
        transform: scale(1.1);
    }

    .section-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #2d3436;
        margin: 0;
    }

    .cart-header {
        text-align: center;
        margin-bottom: 3rem;
    }

    .cart-header h1 {
        font-size: 2.4rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
    }

    @media (max-width: 768px) {

        .cart-header h1 {
            font-size: 2.2rem;
        }

        .progress-tracker {
            gap: 2rem;
        }

        .progress-tracker::before {
            left: 10%;
            right: 10%;
        }
    }
    .summary-item {
        display: flex;
        justify-content: space-between;
        padding: 0.8rem 0;
        border-bottom: 1px dashed #e9ecef;
    }
</style>
<br>
<div class="container py-4">

<!-- Progress Tracker -->
<div class="progress-tracker">
        <div class="progress-step">
            <div class="step-bubble active">1</div>
            <span class="small">Cart</span>
        </div>
        <div class="progress-step">
            <div class="step-bubble">2</div>
            <span class="small">Checkout</span>
        </div>
        <div class="progress-step">
            <div class="step-bubble">3</div>
            <span class="small">Complete</span>
        </div>
    </div>

     <!-- Header -->
     <div class="cart-header text-success">
        <h1>Your Shopping Cart</h1>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($cartItems->isNotEmpty())
    <div class="row g-4">

        {{-- CART ITEMS --}}
        <div class="col-lg-8">
            <div class="summary-card">
                @php $total = 0; @endphp
                
                @foreach($cartItems as $item)
                @php
                    $price = $item->product->price;
                    $subtotal = $price * $item->quantity;
                    $total += $subtotal;
                @endphp

                <div class="p-3 border-bottom">
                    {{-- Seller --}}
                    <small class="text-muted d-block mb-2">
                        <i class="bi bi-shop me-1"></i> {{ $item->product->seller->business_name ?? 'Unknown Seller' }}
                    </small>

                    <div class="row align-items-center">
                        {{-- Image --}}
                        <div class="col-3 col-md-2">
                            <img src="{{ $item->image_url }}" 
                                 class="img-fluid rounded shadow-sm"
                                 style="width:80px;height:80px;object-fit:cover;">
                        </div>

                        {{-- Details --}}
                        <div class="col-9 col-md-4">
                            <h6 class="fw-bold mb-1">{{ $item->product->product_name }}</h6>
                            <p class="text-muted small mb-1">RM {{ number_format($price, 2) }}</p>
                            <p class="fw-semibold text-success">RM {{ number_format($subtotal, 2) }}</p>
                        </div>

                        <div class="product-variant">
    Variant: 
    @if($item->variant && $item->variant !== '')
        <strong>{{ $item->variant }}</strong>
        @if(!empty($variants) && count($variants) > 1)
            <br><small class="text-muted">
                
            </small>
        @endif
    @else
        <span class="text-muted">Standard</span>
    @endif
</div>
                        

                        {{-- Quantity --}}
                        <div class="col-6 col-md-3 mt-2">
                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex align-items-center">
                                @csrf @method('PUT')
                                <button class="btn btn-sm btn-outline-secondary rounded-circle p-0" 
                                        name="quantity" value="{{ $item->quantity - 1 }}"
                                        style="width:28px;height:28px;"
                                        {{ $item->quantity <= 1 ? 'disabled' : '' }}>−</button>
                                <span class="mx-2 fw-bold">{{ $item->quantity }}</span>
                                <button class="btn btn-sm btn-outline-secondary rounded-circle p-0" 
                                        name="quantity" value="{{ $item->quantity + 1 }}"
                                        style="width:28px;height:28px;">+</button>
                            </form>
                        </div>

                        {{-- Remove --}}
                        <div class="col-6 col-md-3 mt-2 text-end">
                            <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-dark btn-sm px-3">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach

                {{-- Continue Shopping --}}
                <div class="p-3">
                    <a href="{{ route('products.browse') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left"></i> Continue Shopping
                    </a>
                </div>
            </div>
        </div>

              <div class="col-lg-4">
                  <!-- Order Summary -->
                  <div class="summary-card">
                    <div class="section-header">
                        <h3 class="section-title">Order Summary</h3>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Products ({{ $cartItems->sum('quantity') }})</span>
                            <span class="fw-bold">
                                RM {{ number_format($total, 2) }}
                            </span>
                        </div>
                
                    
                    @php $delivery = 10.00; @endphp

                    <div class="d-flex justify-content-between mb-2">
                        <span>Delivery price</span>
                        <span class="fw-bold">
                            RM {{ number_format($delivery, 2) }}</span>
                    </div>
                    
                    <hr>
                    
                    <div class="summary-item">
                            <span>Subtotal (incl. delivery)</span>
                            <span class="fw-bold">RM {{ number_format($total + $delivery, 2) }}</span>
                        </div>

                    <a href="{{ route('buyer.checkout') }}" class="btn btn-outline-success w-100 py-3 total-price rounded-pill">
                        Checkout
                    </a>
                    
                    {{-- Payment Icons --}}
                    <div class="d-flex justify-content-center align-items-center mt-4 gap-2">
                        <img src="{{ asset('images/tng-logo.jpg') }}" width="35">
                        <img src="{{ asset('images/visa-logo.jpg') }}" width="35">
                        <img src="{{ asset('images/Master-logo.jpg') }}" width="35">
                        <img src="{{ asset('images/apple-logo.jpg') }}" width="35">
                        <img src="{{ asset('images/duitnow-logo.png') }}" width="35">
                    </div>
                </div>
            </div>
        </div>

    </div>
    @else
    {{-- Empty Cart --}}
    <div class="text-center py-5">
        <i class="bi bi-cart-x display-1 text-muted mb-3"></i>
        <h4 class="fw-bold mb-3">Your cart is empty</h4>
        <a href="{{ route('products.browse') }}" class="btn btn-primary btn-lg">
            Start Shopping
        </a>
    </div>
    @endif

</div>

@endsection