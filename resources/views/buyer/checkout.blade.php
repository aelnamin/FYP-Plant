@extends('layouts.main')

@section('title', 'Checkout')

@section('content')

@php($total = $total ?? 0)

<style>
    :root {
        --primary: #4BAE7F;
        --primary-light: #e8f5ef;
        --gradient: linear-gradient(135deg, #4BAE7F 0%, #2d8a60 100%);
    }

    body {
        background: #f8f9fa;
        font-family: 'Inter', -apple-system, sans-serif;
    }

    .checkout-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem;
    }

    .checkout-header {
        text-align: center;
        margin-bottom: 3rem;
    }

    .checkout-header h1 {
        font-size: 2.4rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.2);
        margin-bottom: 1.5rem;
        transition: transform 0.3s ease;
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

    .section-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #2d3436;
        margin: 0;
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
        background: linear-gradient(to right, var(--primary) 0%, var(--primary) 56%, #e9ecef 0%);
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

    .product-card {
        background: white;
        border-radius: 16px;
        padding: 1.2rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 1.2rem;
        transition: transform 0.3s ease;
        box-shadow: 0 25px 50px rgba(75, 174, 127, 0.15);
    }

    .product-card:hover {
        transform: translateX(5px);
    }

    .product-img {
        width: 90px;
        height: 90px;
        border-radius: 12px;
        object-fit: cover;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .product-price {
        font-weight: 800;
        font-size: 0.9rem;
    }

    .payment-card {
        background: white;
        border: 2px solid #e9ecef;
        border-radius: 14px;
        padding: 1.2rem;
        margin-bottom: 0.8rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .payment-card:hover,
    .payment-card.active {
        border-color: var(--primary);
        background: var(--primary-light);
        transform: translateY(-3px);
    }

    .payment-icon {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary);
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

    .summary-item {
        display: flex;
        justify-content: space-between;
        padding: 0.8rem 0;
        border-bottom: 1px dashed #e9ecef;
    }

    .summary-item.total {
        border-bottom: none;
        border-top: 2px solid #e9ecef;
        font-size: 1.3rem;
        font-weight: 800;
        color: var(--primary);
        padding-top: 1.2rem;
        margin-top: 0.5rem;
    }

    .order-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(75, 174, 127, 0.3);
    }

    @media (max-width: 768px) {
        .checkout-container {
            padding: 1rem;
        }

        .checkout-header h1 {
            font-size: 2.2rem;
        }

        .glass-card,
        .summary-card {
            padding: 1.5rem;
        }

        .progress-tracker {
            gap: 2rem;
        }

        .progress-tracker::before {
            left: 10%;
            right: 10%;
        }
    }
</style>

<div class="checkout-container mt-4 mb-5">

    <!-- Progress Tracker -->
    <div class="progress-tracker">
        <div class="progress-step">
            <div class="step-bubble active">1</div>
            <span class="small">Cart</span>
        </div>
        <div class="progress-step">
            <div class="step-bubble active">2</div>
            <span class="small">Checkout</span>
        </div>
        <div class="progress-step">
            <div class="step-bubble">3</div>
            <span class="small">Complete</span>
        </div>
    </div>

    <!-- Header -->
    <div class="checkout-header text-success">
        <h1>Complete Your Order</h1>
        <p class="text-muted">Review items and confirm details</p>
    </div>

    <form action="{{ route('checkout.placeOrder') }}" method="POST" id="checkoutForm">
        @csrf

        <div class="row g-4">
            <!-- LEFT COLUMN -->
            <div class="col-lg-7">
                <!-- Shipping Information -->
                <div class="glass-card">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="bi bi-truck"></i>
                        </div>
                        <h3 class="section-title">Shipping Information</h3>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold mb-2">Full Name</label>
                            <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold mb-2">Phone Number</label>
                            <input type="text" class="form-control" value="{{ Auth::user()->phone }}" readonly>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold mb-2">Delivery Address</label>
                            <textarea class="form-control" rows="2" readonly>{{ Auth::user()->address }}</textarea>
                        </div>
                        <div class="form-text mt-2 d-flex align-items-center gap-2">
                            <i class="bi bi-info-circle"></i>
                            Need to update? Go to your profile settings.
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="glass-card">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="bi bi-wallet-fill"></i>
                        </div>
                        <h3 class="section-title">Payment Method</h3>
                    </div>

                    <div class="payment-card active" onclick="selectPayment('cod')">
                        <div class="payment-icon">
                            <i class="bi bi-cash-coin"></i>
                        </div>
                        <div>
                            <div class="fw-semibold">Cash on Delivery</div>
                            <small class="text-muted">Pay when order arrives</small>
                        </div>
                        <input type="radio" name="payment_method" value="cod" checked hidden id="cod">
                    </div>

                    <div class="payment-card" onclick="selectPayment('online')">
                        <div class="payment-icon">
                            <i class="bi bi-credit-card"></i>
                        </div>
                        <div>
                            <div class="fw-semibold">Online Payment</div>
                            <small class="text-muted">Pay with card or e-wallet</small>
                        </div>
                        <input type="radio" name="payment_method" value="online" hidden id="online">
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN -->
            <div class="col-lg-5">
                <!-- Order Summary -->
                <div class="summary-card">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="bi bi-clipboard"></i>
                        </div>
                        <h3 class="section-title">Order Summary</h3>
                    </div>

                    <!-- Cart Items -->
                    <div class="mb-4">
                        @foreach ($cartItems as $item)
                            @if($item->product)
                                <div class="product-card">
                                    <img src="{{ $item->product->images->first() ? asset('images/' . $item->product->images->first()->image_path) : asset('images/default.jpg') }}"
                                        class="product-img" alt="{{ $item->product->product_name }}">
                                    <div class="flex-grow-1">
                                        <div class="fw-semibold">{{ $item->product->product_name }}</div>
                                        <small class="text-muted">Qty: {{ $item->quantity }}</small>
                                    </div>
                                    <div class="product-price">
                                        RM {{ number_format($item->product->price * $item->quantity, 2) }}
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <!-- Pricing -->
                    <div>
                        <div class="summary-item">
                            <span>Subtotal</span>
                            <span class="fw-semibold">RM {{ number_format($total, 2) }}</span>
                        </div>
                        <div class="summary-item">
                            <span>Delivery (inc. 6% SST)</span>
                            <span class="fw-semibold">RM 10.60</span>
                        </div>
                        <div class="summary-item total">
                            <span>Total</span>
                            <span class="fw">RM {{ number_format($total + 10.60, 2) }}</span>
                        </div>
                    </div>

                    <!-- Button -->
                    <button type="submit" class="btn btn-outline-success w-100 py-3 product-price rounded-pill"
                        id="placeOrderBtn">
                        Place Order
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function selectPayment(method) {
        // Update radio
        document.getElementById(method).checked = true;

        // Update UI
        document.querySelectorAll('.payment-card').forEach(card => {
            card.classList.remove('active');
        });
        event.currentTarget.classList.add('active');
    }

    // Form submit
    document.getElementById('checkoutForm').addEventListener('submit', function (e) {
        const btn = document.getElementById('placeOrderBtn');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        btn.disabled = true;
    });
</script>

@endsection