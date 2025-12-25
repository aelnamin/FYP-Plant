@extends('layouts.sellers-main')

@section('title', 'Order Details')

@section('content')
    <div class="container py-5">
        <h2 class="fw-bold mb-4 text-success">Order #{{ str_pad($order->id, 10, '0', STR_PAD_LEFT) }}</h2>

        {{-- Order Info --}}
        <div class="card mb-4 shadow-sm rounded-4">
            <div class="card-body">
                <p><strong>Order Status:</strong>
                    <span class="badge 
                        @if($order->status === 'Paid') bg-primary
                        @elseif($order->status === 'Shipped') bg-success
                        @else bg-secondary
                        @endif">
                        {{ $order->status }}
                    </span>
                </p>
                <p><strong>Placed On:</strong> {{ $order->created_at->format('d M Y, h:i A') }}</p>
                <p><strong>Buyer:</strong> {{ $order->buyer->name ?? 'Unknown' }} ({{ $order->buyer->email ?? '-' }})</p>
            </div>
        </div>

        {{-- Seller Products --}}
        <h5 class="fw-bold mb-3">Your Products in this Order</h5>
        @php
            $sellerItems = $order->items->filter(fn($item) => $item->product && $item->product->seller_id == auth()->id());
            $sellerTotal = $sellerItems->sum(fn($i) => $i->price * $i->quantity);
        @endphp

        @if($sellerItems->count() > 0)
            @foreach($sellerItems as $item)
                <div class="card mb-3 shadow-sm rounded-4">
                    <div class="card-body d-flex align-items-center">
                        <div style="width:100px; height:100px; flex-shrink:0;">
                            <img src="{{ $item->product->images->first()
                        ? asset('images/' . $item->product->images->first()->image_path)
                        : asset('images/default.png') }}" class="rounded-3"
                                style="width:100%; height:100%; object-fit:cover;">
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-semibold">{{ $item->product->product_name }}</div>
                            <small class="text-muted">Qty: {{ $item->quantity }}</small>
                            <div class="text-success fw-bold">RM {{ number_format($item->price * $item->quantity, 2) }}</div>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Total --}}
            <div class="d-flex justify-content-between mt-3 mb-4">
                <span class="fw-semibold">Total for your products</span>
                <strong class="fw-bold text-success">RM {{ number_format($sellerTotal, 2) }}</strong>
            </div>

            {{-- Action --}}
            @if($order->status === 'Paid')
                <form action="{{ route('seller.orders.ship', $order->id) }}" method="POST">
                    @csrf
                    <button class="btn btn-success">Mark as Shipped</button>
                </form>
            @elseif($order->status === 'Shipped')
                <span class="text-success fw-semibold">Already Shipped</span>
            @endif
        @else
            <p class="text-muted">No products from you in this order.</p>
        @endif
    </div>
@endsection