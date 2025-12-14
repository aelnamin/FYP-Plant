@extends('layouts.main')

@section('title', 'My Cart')

@section('content')

    <div class="container mt-5">

        <h3 class="fw-bold mb-4">Your Cart</h3>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($cartItems->isNotEmpty())
            <div class="row">

                {{-- LEFT SIDE — CART ITEMS --}}
                <div class="col-lg-8">

                    @php $total = 0; @endphp

                    @foreach($cartItems as $item)

                        @php
                            $price = $item->product->price;
                            $subtotal = $price * $item->quantity;
                            $total += $subtotal;
                        @endphp

                        <div class="card shadow-sm p-3 mb-3 border-0 rounded-4">
                            <div class="row align-items-center">

                                {{-- IMAGE --}}
                                <div class="col-md-2 text-center">
                                    <img src="{{ $item->image_url }}" class="img-fluid rounded"
                                        style="width:100px;height:100px;object-fit:cover;">
                                </div>

                                {{-- PRODUCT DETAILS --}}
                                <div class="col-md-4">
                                    <h5 class="fw-bold mb-1">
                                        {{ $item->product->product_name }}
                                    </h5>

                                    <p class="text-muted mb-1">
                                        Price: RM {{ number_format($price, 2) }}
                                    </p>

                                    <p class="fw-semibold">
                                        Subtotal: RM {{ number_format($subtotal, 2) }}
                                    </p>
                                </div>

                                {{-- QUANTITY BUTTONS --}}
                                <div class="col-md-3 text-center">
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST"
                                        class="d-flex justify-content-center">
                                        @csrf
                                        @method('PUT')

                                        <button class="btn btn-light border rounded-circle px-3" name="quantity"
                                            value="{{ $item->quantity - 1 }}" {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                            −
                                        </button>

                                        <span class="px-3 fw-bold">
                                            {{ $item->quantity }}
                                        </span>

                                        <button class="btn btn-light border rounded-circle px-3" name="quantity"
                                            value="{{ $item->quantity + 1 }}">
                                            +
                                        </button>
                                    </form>
                                </div>

                                {{-- REMOVE BUTTON --}}
                                <div class="col-md-3 text-center">
                                    <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn border-0 bg-transparent p-0 text-dark">
                                            <i class="bi bi-trash-fill fs-4"></i>
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </div>

                    @endforeach

                </div>

                {{-- ORDER SUMMARY --}}
                <div class="col-lg-4">
                    <div class="card p-4 shadow border-0 rounded-4">

                        <h5 class="fw-bold mb-3">Order Summary</h5>
                        <hr>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Products ({{ $cartItems->count() }})</span>
                            <span class="fw-bold">
                                RM {{ number_format($total, 2) }}
                            </span>
                        </div>

                        @php $delivery = 15.00; @endphp

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Delivery price (inc. 6% SST)</span>
                            <span class="fw-bold">
                                RM {{ number_format($delivery, 2) }}
                            </span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="fw-semibold">Subtotal (incl. delivery)</span>
                            <span class="fw-bold fs-5">
                                RM {{ number_format($total + $delivery, 2) }}
                            </span>
                        </div>

                        <p class="text-muted small mt-3 mb-2">
                            By clicking "Checkout" you're agreeing to our
                            <a href="#" class="text-decoration-none">Privacy Policy</a>
                        </p>

                        <a href="{{ route('checkout') }}" class="btn w-100 text-white fw-semibold mt-2 py-3"
                            style="background-color:#0058A3; border-radius:30px;">
                            Checkout
                        </a>

                        {{-- Payment icons --}}
                        <div class="text-center mt-3">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Visa.svg" width="40">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Mastercard-logo.svg" width="40">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/3/30/FPX_Logo.svg" width="40">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/9/9d/Grab_Pay_logo.png" width="45">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/5/5a/Apple_Pay_logo.svg" width="40">
                        </div>

                    </div>
                </div>

            </div>
        @else

            <p class="text-center fw-semibold mt-5">Your cart is empty.</p>
            <div class="text-center mt-3">
                <a href="{{ route('products.browse') }}" class="btn btn-outline-success">
                    Continue Shopping
                </a>
            </div>

        @endif

    </div>

@endsection