@extends('layouts.sellers-main')

@section('title', 'Orders')

@section('content')
    <div class="container py-5">

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger rounded-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Success Message --}}
        @if (session('success'))
            <div class="alert alert-success rounded-3">
                {{ session('success') }}
            </div>
        @endif

        {{-- Error Message --}}
        @if (session('error'))
            <div class="alert alert-danger rounded-3">
                {{ session('error') }}
            </div>
        @endif

        @if(isset($sellerId) && $orders->count() > 0)

            @foreach($orders as $order)
                @php
                    // Filter only items belonging to this seller
                    $sellerItems = $order->items->filter(
                        fn($item) =>
                        $item->product && $item->product->seller_id == $sellerId
                    );

                    $sellerTotal = $sellerItems->sum(
                        fn($item) =>
                        $item->price * $item->quantity
                    );
                @endphp

                @if($sellerItems->count() > 0)

                    {{-- BUYER NAME CONTAINER --}}
                    <div class="mb-2">
                        <h5 class="fw-bold text-success fs-4">
                            {{ $order->buyer->name ?? 'Unknown Buyer' }} Order
                        </h5>
                    </div>

                    <div class="card mb-4 shadow-sm rounded-4">
                        <div class="card-body">

                            {{-- Order Header --}}
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="mb-1">
                                        Order #{{ str_pad($order->id, 10, '0', STR_PAD_LEFT) }}
                                    </h6>
                                    <small class="text-muted">
                                        Placed on {{ $order->created_at->format('d M Y, h:i A') }}
                                    </small>
                                </div>

                                <span class="badge
                                                    @if($order->status === 'Paid') bg-primary
                                                    @elseif($order->status === 'Shipped') bg-success
                                                    @elseif($order->status === 'Delivered') bg-success
                                                    @else bg-secondary
                                                    @endif">
                                    {{ $order->status }}
                                </span>
                            </div>

                            <hr>

                            {{-- Seller Products --}}
                            @foreach($sellerItems as $item)
                                <div class="d-flex align-items-center mb-3">

                                    <div style="width: 80px; height: 80px; flex-shrink: 0;">
                                        <img src="{{ $item->product->images->first()
                                        ? asset('images/' . $item->product->images->first()->image_path)
                                        : asset('images/default.png') }}" class="rounded-3"
                                            style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>

                                    <div class="flex-grow-1 ms-3">
                                        <div class="fw-semibold">
                                            {{ $item->product->product_name }}
                                        </div>

                                        <!-- Variant -->
                                        <div class="text-secondary small">
                                            <i class="fas fa-tag me-1"></i>
                                            {{ $item->variant && $item->variant !== '' ? $item->variant : 'Standard' }}
                                        </div>

                                        <small class="text-muted">
                                            Qty: {{ $item->quantity }}
                                        </small>
                                    </div>

                                    <div class="fw-bold text-success">
                                        RM {{ number_format($item->price * $item->quantity, 2) }}
                                    </div>

                                </div>
                            @endforeach

                            {{-- Seller Total --}}
                            <div class="d-flex justify-content-end mt-3">
                                <strong>
                                    Total: RM {{ number_format($sellerTotal, 2) }}
                                </strong>
                            </div>

                            {{-- Action --}}
                            <div class="mt-3 text-end">
                                @if($order->status === 'Pending')
                                    <form action="{{ route('sellers.orders.paid', $order->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-primary btn-sm rounded-pill">
                                            Mark as Paid
                                        </button>
                                    </form>

                                @elseif($order->status === 'Paid' && !$order->delivery)
                                    <form action="{{ route('sellers.deliveries.store', $order->id) }}" method="POST" class="d-inline">
                                        @csrf

                                        <input type="hidden" name="courier_name" value="J&T Express">

                                        <input type="text" name="tracking_number" class="form-control form-control-sm d-inline w-50 me-2"
                                            placeholder="Tracking Number" required>

                                        <button class="btn btn-success btn-sm rounded-pill">
                                            Ship Order
                                        </button>
                                    </form>

                                @elseif($order->status === 'Shipped' && $order->delivery)
                                    <span class="text-success fw-semibold">
                                        Shipped • {{ $order->delivery->courier_name }} |
                                        {{ $order->delivery->tracking_number }}
                                    </span>

                                    {{-- Mark as Delivered Button --}}
                                    @if(!$order->delivery->delivered_at)
                                        <form action="{{ route('sellers.deliveries.deliver', $order->id) }}" method="POST"
                                            class="d-inline ms-2">
                                            @csrf
                                            <button class="btn btn-info btn-sm rounded-pill">
                                                Mark as Delivered
                                            </button>
                                        </form>
                                    @endif

                                @elseif($order->status === 'Delivered')
                                    <span class="text-success fw-semibold">
                                        Delivered • {{ $order->delivery->courier_name }} |
                                        {{ $order->delivery->tracking_number }}
                                    </span>
                                @endif
                            </div>

                        </div>
                    </div>

                @endif
            @endforeach

        @else
            <p class="text-muted">You have no orders yet.</p>
        @endif
    </div>
@endsection