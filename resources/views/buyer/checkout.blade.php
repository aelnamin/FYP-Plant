@extends('layouts.main')

@section('title', 'Checkout')

@section('content')
    <div class="container py-5">
        <!-- Progress Tracker -->
        <div class="d-flex justify-content-center mb-5">
            <div class="d-flex align-items-center position-relative" style="max-width: 500px; width: 100%;">
                <!-- Progress Line -->
                <div class="progress position-absolute w-100"
                    style="height: 3px; top: 15px; z-index: 1; background-color: #e9ecef;">
                    <div class="progress-bar" style="width: 66%; background-color: #8a9c6a;"></div>
                </div>

                <!-- Steps -->
                <div class="d-flex justify-content-between w-100 position-relative" style="z-index: 2;">
                    <div class="text-center">
                        <div class="rounded-circle text-white d-flex align-items-center justify-content-center mx-auto mb-2"
                            style="width: 40px; height: 40px; background-color: #8a9c6a;">
                            <i class="fas fa-check"></i>
                        </div>
                        <span class="small text-muted">Cart</span>
                    </div>
                    <div class="text-center">
                        <div class="rounded-circle text-white d-flex align-items-center justify-content-center mx-auto mb-2"
                            style="width: 40px; height: 40px; background-color: #8a9c6a;">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <span class="small fw-semibold text-dark">Checkout</span>
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
            <h1 class="fw-bold text-dark mb-3">Checkout</h1>
            <p class="text-secondary">Review items and confirm details</p>
        </div>

        <form action="{{ route('checkout.placeOrder') }}" method="POST" id="checkoutForm">
            @csrf

            <div class="row g-4">
                <!-- Left Column -->
                <div class="col-lg-7">
                    <!-- Shipping Information -->
                    <div class="card border-light shadow-sm rounded-3 mb-4">
                        <div class="card-header bg-light border-0 py-3">
                            <div class="d-flex align-items-center">
                                <div class="rounded-2 p-2 me-3" style="background-color: #e8f0e8;">
                                    <i class="fas fa-truck" style="color: #8a9c6a;"></i>
                                </div>
                                <h5 class="fw-bold text-dark mb-0">Shipping Information</h5>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark mb-2">Full Name</label>
                                    <input type="text" class="form-control border-light" value="{{ Auth::user()->name }}"
                                        readonly style="background-color: #f8f9fa;">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark mb-2">Phone Number</label>
                                    <input type="text" class="form-control border-light" value="{{ Auth::user()->phone }}"
                                        readonly style="background-color: #f8f9fa;">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold text-dark mb-2">Delivery Address</label>
                                    <textarea class="form-control border-light" rows="2" readonly
                                        style="background-color: #f8f9fa;">{{ Auth::user()->address }}</textarea>
                                </div>
                            </div>
                            <div class="alert alert-light border rounded-3 mt-3 p-3" style="background-color: #f8f9fa;">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-info-circle me-2" style="color: #6c757d;"></i>
                                    <small class="text-secondary">Need to update? Go to your profile settings.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="card border-light shadow-sm rounded-3">
                        <div class="card-header bg-light border-0 py-3">
                            <div class="d-flex align-items-center">
                                <div class="rounded-2 p-2 me-3" style="background-color: #e8f0e8;">
                                    <i class="fas fa-wallet" style="color: #8a9c6a;"></i>
                                </div>
                                <h5 class="fw-bold text-dark mb-0">Payment Method</h5>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <!-- COD Option -->
                            <div class="payment-option active mb-3" onclick="selectPayment('cod', event)">

                                <div class="d-flex align-items-center">
                                    <div class="rounded-3 p-2 me-3" style="background-color: #e8f0e8;">
                                        <i class="fas fa-money-bill-wave" style="color: #8a9c6a;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold text-dark mb-1">Cash on Delivery</h6>
                                        <p class="text-secondary small mb-0">Pay when order arrives</p>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method" value="cod"
                                            id="cod" checked style="background-color: #8a9c6a; border-color: #8a9c6a;">
                                    </div>
                                </div>
                            </div>

                            <!-- Online Payment Option -->
                            <div class="payment-option" onclick="selectPayment('online', event)">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-3 p-2 me-3" style="background-color: #e8f0e8;">
                                        <img src="{{ asset('images/visa-logo.jpg') }}" width="35" class="img-fluid">
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold text-dark mb-1">Online Payment</h6>
                                        <p class="text-secondary small mb-0">Pay with visa, or mastercard</p>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method" value="online"
                                            id="online">
                                    </div>
                                </div>
                            </div>

                            <!-- Touch n Go Payment Option -->
                            <div class="payment-option" onclick="selectPayment('touch_n_go', event)">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-3 p-2 me-3" style="background-color: #e8f0e8;">
                                        <img src="{{ asset('images/tng-logo.jpg') }}" width="35" class="img-fluid">
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold text-dark mb-1">Touch n Go</h6>
                                        <p class="text-secondary small mb-0">Pay with e-wallet</p>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method"
                                            value="touch_n_go" id="touch_n_go">
                                    </div>
                                </div>
                            </div>

                            <!-- Apple Pay Payment Option -->
                            <div class="payment-option" onclick="selectPayment('apple_pay', event)">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-3 p-2 me-3" style="background-color: #e8f0e8;">
                                        <img src="{{ asset('images/apple-logo.jpg') }}" width="35" class="img-fluid">
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold text-dark mb-1">Apple Pay</h6>
                                        <p class="text-secondary small mb-0">Pay with apple pay</p>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method" value="apple_pay"
                                            id="apple_pay">
                                    </div>
                                </div>
                            </div>

                            <!-- Grab Pay Payment Option -->
                            <div class="payment-option" onclick="selectPayment('grab_pay', event)">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-3 p-2 me-3" style="background-color: #e8f0e8;">
                                        <img src="{{ asset('images/grab-pay-logo.png') }}" width="35" class="img-fluid">
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold text-dark mb-1">Grab Pay</h6>
                                        <p class="text-secondary small mb-0">Pay with grab pay</p>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method" value="grab_pay"
                                            id="grab_pay">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-lg-5">
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
                            <!-- Cart Items Grouped by Seller -->
                            <div class="mb-4">
                                @php
                                    // Group cart items by seller
                                    $groupedItems = $cartItems->groupBy(function ($item) {
                                        return $item->product ? $item->product->seller_id : null;
                                    });
                                @endphp

                                @foreach($groupedItems as $sellerId => $items)
                                    @if($items->first() && $items->first()->product && $items->first()->product->seller)
                                        <!-- Seller Header -->
                                        <div class="seller-section mb-3">
                                            <div class="seller-header d-flex align-items-center mb-2 p-2 rounded"
                                                style="background-color: #f8f9fa; border: 1px solid #e9ecef;">
                                                <i class="fas fa-store me-2" style="color: #8a9c6a;"></i>
                                                <h6 class="fw-semibold mb-0" style="font-size: 0.9rem;">
                                                    {{ $items->first()->product->seller->business_name ?? $items->first()->product->seller->name }}
                                                </h6>
                                            </div>

                                            <!-- Items from this seller -->
                                            @foreach($items as $item)
                                                @if($item->product)
                                                    <div class="d-flex align-items-start mb-3 pb-3 border-bottom"
                                                        style="border-color: #e9ecef !important;">
                                                        <img src="{{ $item->product->images->first() ? asset('images/' . $item->product->images->first()->image_path) : asset('images/default.jpg') }}"
                                                            class="rounded-3 me-3"
                                                            style="width: 60px; height: 60px; object-fit: cover; border: 1px solid #e9ecef;">
                                                        <div class="flex-grow-1">
                                                            <h6 class="fw-semibold text-dark mb-1" style="font-size: 0.95rem;">
                                                                {{ $item->product->product_name }}
                                                            </h6>
                                                            <p class="text-secondary small mb-1">Qty: {{ $item->quantity }}</p>
                                                            <!-- Variant -->
                                                            <div class="text-secondary small mb-1">
                                                                <i class="fas fa-tag me-1"></i>
                                                                {{ $item->variant && $item->variant !== '' ? $item->variant : 'Standard' }}
                                                            </div>
                                                            <p class="fw-bold mb-0" style="color: #8a9c6a; font-size: 0.95rem;">
                                                                RM {{ number_format($item->product->price * $item->quantity, 2) }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <!-- Pricing Summary -->
                            <div class="border-top pt-3" style="border-color: #e9ecef !important;">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-secondary">Subtotal</span>
                                    <span class="fw-semibold text-dark">RM {{ number_format($total, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-secondary">Delivery (inc. 6% SST)</span>
                                    <span class="fw-semibold text-dark">RM {{ number_format($delivery, 2) }}</span>
                                </div>
                                <hr class="my-3" style="border-color: #e9ecef;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fw-bold text-dark mb-1">Total</h6>
                                        <small class="text-secondary">Including delivery</small>
                                    </div>
                                    <h4 class="fw-bold mb-0" style="color: #8a9c6a;">
                                        RM {{ number_format($total + $delivery, 2) }}
                                    </h4>
                                </div>

                            </div>

                            <!-- Place Order Button -->
                            <button type="submit" id="placeOrderBtn" class="btn w-100 py-3 rounded-3 fw-bold mt-4 border-0"
                                style="background-color: #8a9c6a; color: white;">
                                <i class="fas fa-shopping-bag me-2"></i> Place Order
                            </button>

                            <!-- Security Note -->
                            <div class="text-center mt-3">
                                <p class="text-secondary small">
                                    <i class="fas fa-lock me-1"></i>
                                    Secure SSL Encryption
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Custom Styles -->
    <style>
        .payment-option {
            border: 2px solid #e9ecef;
            border-radius: 0.75rem;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 0.5rem;
            background-color: white;
        }

        .payment-option:hover {
            border-color: #8a9c6a;
            background-color: #f8f9fa;
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(138, 156, 106, 0.1);
        }

        .payment-option.active {
            border-color: #8a9c6a;
            background-color: #f8f9fa;
        }

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

        .form-control:read-only {
            background-color: #f8f9fa;
            border-color: #e9ecef;
            color: #495057;
        }

        .form-check-input:checked {
            background-color: #8a9c6a;
            border-color: #8a9c6a;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(138, 156, 106, 0.2);
            background-color: #7a8b5a !important;
        }

        .border-light {
            border-color: #e9ecef !important;
        }

        .bg-light {
            background-color: #f8f9fa !important;
        }

        /* Better text contrast */
        .text-dark {
            color: #212529 !important;
        }

        .text-secondary {
            color: #6c757d !important;
        }

        /* Focus states */
        .form-control:focus {
            border-color: #8a9c6a;
            box-shadow: 0 0 0 0.25rem rgba(138, 156, 106, 0.25);
        }

        /* Progress bar styling */
        .progress-bar {
            transition: width 0.3s ease;
        }

        /* Seller section styling */
        .seller-section {
            border: 1px solid #e9ecef;
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 1rem;
            background-color: #fff;
        }

        .seller-section .seller-header {
            background-color: #f8f9fa;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
        }

        /* Last item in seller section should not have border */
        .seller-section .border-bottom:last-child {
            border-bottom: none !important;
            padding-bottom: 0;
            margin-bottom: 0;
        }
    </style>

    <script>
        function selectPayment(method, event) {
            document.getElementById(method).checked = true;

            document.querySelectorAll('.payment-option').forEach(option => {
                option.classList.remove('active');
            });

            event.currentTarget.classList.add('active');
        }


        document.getElementById('checkoutForm').addEventListener('submit', function () {
            const btn = document.getElementById('placeOrderBtn');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
            btn.disabled = true;
            btn.style.opacity = '0.8';
        });
    </script>
@endsection