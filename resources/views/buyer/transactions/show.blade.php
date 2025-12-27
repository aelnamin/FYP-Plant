@extends('layouts.main')

@section('title', 'Order Details')

@section('content')
    <div class="container py-5">

        <h2 class="fw-bold text-success mb-4">
            Order #{{ $order->id }}
        </h2>

        {{-- ORDER SUMMARY --}}
        <div class="card shadow-sm rounded-4 mb-4">
            <div class="card-body">
                <p class="mb-1">
                    <strong>Status:</strong>
                    <span class="badge
                            @if($order->status === 'Pending') bg-warning
                            @elseif($order->status === 'Paid') bg-primary
                            @elseif($order->status === 'Shipped') bg-success
                            @else bg-secondary
                            @endif
                        ">
                        {{ $order->status }}
                    </span>
                </p>

                <p class="mb-1">
                    <strong>Total:</strong>
                    RM {{ number_format($order->total_amount, 2) }}
                </p>

                <p class="mb-0">
                    <strong>Placed On:</strong>
                    {{ $order->created_at->format('d M Y') }}
                </p>
            </div>
        </div>

        <h5 class="fw-bold mb-3">Items</h5>

        {{-- ORDER ITEMS --}}
        @foreach($order->items as $item)
            @if($item->product)
                <div class="card mb-3 shadow-sm rounded-4 border-0">
                    <div class="card-body d-flex align-items-center">

                        {{-- PRODUCT IMAGE --}}
                        <img src="{{ $item->product->images->first()
                        ? asset('images/' . $item->product->images->first()->image_path)
                        : asset('images/default.jpg') }}" class="rounded-3 me-3"
                            style="width: 80px; height: 80px; object-fit: cover;" alt="{{ $item->product->product_name }}">

                        {{-- PRODUCT INFO --}}
                        <div class="flex-grow-1">
                            <div class="fw-semibold">
                                {{ $item->product->product_name }}
                            </div>

                            @if($item->variant)
                                <small class="text-muted">
                                    Variant: {{ $item->variant }}
                                </small><br>
                            @endif

                            <small class="text-muted">
                                Qty: {{ $item->quantity }}
                            </small>
                        </div>

                        {{-- PRICE --}}
                        <div class="fw-bold text-success">
                            RM {{ number_format($item->price * $item->quantity, 2) }}
                        </div>

                    </div>
                </div>
            @endif
        @endforeach

    </div>
@endsection