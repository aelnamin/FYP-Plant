@extends('layouts.sellers-main')

@section('title', 'My Orders')

@section('content')
    <div class="container py-5">
        <h2 class="fw-bold mb-4 text-success">My Orders</h2>

        @if(isset($sellerId) && $orders->count() > 0)
            @foreach($orders as $order)
                @php
                    // Filter only items belonging to this seller
                    $sellerItems = $order->items->filter(fn($item) => $item->product && $item->product->seller_id == $sellerId);
                    $sellerTotal = $sellerItems->sum(fn($i) => $i->price * $i->quantity);
                @endphp

                @if($sellerItems->count() > 0)
                    <div class="card mb-4 shadow-sm rounded-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h5 class="mb-1">Order #{{ str_pad($order->id, 10, '0', STR_PAD_LEFT) }}</h5>
                                    <small class="text-muted">Placed on {{ $order->created_at->format('d M Y, h:i A') }}</small>
                                </div>
                                <span class="badge 
                                                                @if($order->status === 'Paid') bg-primary
                                                                @elseif($order->status === 'Shipped') bg-success
                                                                @else bg-secondary
                                                                @endif">
                                    {{ $order->status }}
                                </span>
                            </div>

                            <hr>

                            {{-- Seller Products --}}
                            @foreach($sellerItems as $item)
                                <div class="d-flex align-items-center mb-3">
                                    <div style="width:80px; height:80px; flex-shrink:0;">
                                        <img src="{{ $item->product->images->first()
                                        ? asset('images/' . $item->product->images->first()->image_path)
                                        : asset('images/default.png') }}" class="rounded-3"
                                            style="width:100%; height:100%; object-fit:cover;">
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <div class="fw-semibold">{{ $item->product->product_name }}</div>
                                        <small class="text-muted">Qty: {{ $item->quantity }}</small>
                                    </div>
                                    <div class="fw-bold text-success">
                                        RM {{ number_format($item->price * $item->quantity, 2) }}
                                    </div>
                                </div>
                            @endforeach

                            {{-- Total for this seller --}}
                            <div class="d-flex justify-content-between mt-3">
                                <span>Total for your products</span>
                                <strong>RM {{ number_format($sellerTotal, 2) }}</strong>
                            </div>

                            {{-- Action button --}}
                            <div class="mt-3 text-end">
                                @if($order->status === 'Paid')
                                    <form action="{{ route('sellers.orders.ship', $order->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-success btn-sm rounded-pill">Mark as Shipped</button>
                                    </form>
                                @elseif($order->status === 'Shipped')
                                    <span class="text-success fw-semibold">Already Shipped</span>
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