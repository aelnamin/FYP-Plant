@extends('layouts.main')

@section('title', 'Order Details')

@section('content')
    <div class="container py-5">

        {{-- Order Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
            <div>
                <h1 class="fw-bold text-gradient-success mb-2">Order Details</h1>
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">
                        #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
                    </span>
                    <span class="text-muted">
                        <i class="bi bi-calendar3 me-1"></i>
                        {{ $order->created_at->format('F d, Y') }}
                    </span>
                </div>
            </div>
            <div class="text-end mt-2 mt-md-0">
                <button onclick="window.print()" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    <i class="bi bi-printer me-1"></i> Print
                </button>
            </div>
        </div>

        {{-- Order Status Timeline --}}
        <div class="card shadow-sm rounded-4 mb-4 border-0">
            <div class="card-body">
                <h5 class="fw-bold mb-4">Order Status</h5>
                <div class="row text-center">
                    @php
                        $statusStages = ['Placed' => 'check-lg', 'Paid' => 'credit-card', 'Shipped' => 'truck'];
                    @endphp
                    @foreach($statusStages as $stage => $icon)
                        <div class="col-md-4 mb-3 mb-md-0">
                            @php
                                $active = match ($stage) {
                                    'Placed' => true,
                                    'Paid' => in_array($order->status, ['Paid', 'Shipped']),
                                    'Shipped' => $order->status === 'Shipped',
                                    default => false,
                                };
                            @endphp
                            <div class="d-flex flex-column align-items-center">
                                <div class="mb-2">
                                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center 
                                                    {{ $active ? 'bg-success text-white' : 'bg-light text-dark border' }}"
                                        style="width:50px;height:50px;">
                                        <i class="bi bi-{{ $icon }} fs-5"></i>
                                    </div>
                                </div>
                                <h6 class="fw-bold">{{ $stage }}</h6>
                                <small class="text-muted">
                                    @if($stage === 'Placed') {{ $order->created_at->format('d M, h:i A') }}
                                    @elseif($stage === 'Paid') {{ $active ? 'Completed' : 'Pending' }}
                                    @elseif($stage === 'Shipped') {{ $active ? 'Shipped' : 'Processing' }}
                                    @endif
                                </small>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="row g-4">
            {{-- Order Items --}}
            <div class="col-lg-8">
                <div class="card shadow-sm rounded-4 border-0 h-100">
                    <div class="card-header bg-light border-0 py-3">
                        <h5 class="fw-bold mb-0">
                            <i class="bi bi-cart3 text-success me-2"></i>
                            Order Items ({{ $order->items->count() }})
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @foreach($order->items as $item)
                                @if($item->product)
                                    <div class="list-group-item border-0 px-0 py-3">
                                        <div class="row align-items-center">
                                            {{-- Product Image --}}
                                            <div class="col-auto">
                                                <div class="position-relative">
                                                    <img src="{{ $item->product->images->first() ? asset('images/' . $item->product->images->first()->image_path) : asset('images/default.jpg') }}"
                                                        class="rounded-3" style="width:80px;height:80px;object-fit:cover;"
                                                        alt="{{ $item->product->product_name }}">
                                                    @if($item->quantity > 1)
                                                        <span
                                                            class="badge bg-success position-absolute top-0 start-100 translate-middle rounded-circle">
                                                            {{ $item->quantity }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- Product Info --}}
                                            <div class="col">
                                                <h6 class="fw-bold mb-1">{{ $item->product->product_name }}</h6>
                                                @if($item->variant)
                                                    <small class="text-muted d-block">
                                                        <i class="bi bi-tag me-1"></i> Variant: {{ $item->variant }}
                                                    </small>
                                                @endif
                                                <small class="text-muted">Unit Price: RM
                                                    {{ number_format($item->price, 2) }}</small>
                                                <small class="text-muted d-block">
                                                    Seller: {{ $item->product->seller->business_name ?? 'Unknown' }}
                                                </small>
                                            </div>

                                            {{-- Price --}}
                                            <div class="col-auto text-end">
                                                <div class="fw-bold text-success fs-5">
                                                    RM {{ number_format($item->price * $item->quantity, 2) }}
                                                </div>
                                                <small class="text-muted">
                                                    RM {{ number_format($item->price, 2) }} × {{ $item->quantity }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Order Summary & Support --}}
            <div class="col-lg-4">
                <div class="card shadow-sm rounded-4 border-0 sticky-top" style="top:20px;">
                    <div class="card-header bg-light border-0 py-3">
                        <h5 class="fw-bold mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        {{-- Status --}}
                        <div class="text-center mb-4">
                            <span class="badge rounded-pill px-4 py-2 fs-6 
                                @if($order->status === 'Pending') bg-warning text-dark
                                @elseif($order->status === 'Paid') bg-primary
                                @elseif($order->status === 'Shipped') bg-success
                                @else bg-secondary @endif">
                                <i class="bi 
                                    @if($order->status === 'Pending') bi-clock
                                    @elseif($order->status === 'Paid') bi-check-circle
                                    @elseif($order->status === 'Shipped') bi-truck
                                    @endif me-2"></i>
                                {{ $order->status }}
                            </span>
                        </div>

                        {{-- Summary --}}
                        @php
                            $subtotal = $order->items->sum(fn($item) => $item->price * $item->quantity);
                            $shipping = 10.60;
                            $total = $subtotal + $shipping;
                        @endphp

                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal</span>
                                <span>RM {{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Shipping</span>
                                <span class="text-success">RM {{ number_format($shipping, 2) }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <strong>Total Amount</strong>
                                <strong class="text-success fs-4">RM {{ number_format($total, 2) }}</strong>
                            </div>
                        </div>

                        {{-- Payment Info --}}
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">Payment Information</h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Method</span>
                                <span>Online Payment</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Status</span>
                                <span class="badge bg-success">Paid</span>
                            </div>
                        </div>

        </div>

        <style>
            .text-gradient-success {
                background: linear-gradient(45deg, #28a745, #20c997);
                background-clip: text;
            }

            .sticky-top {
                z-index: 1020;
            }

            .card:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
                transition: all 0.2s ease;
            }
        </style>
    </div>
@endsection