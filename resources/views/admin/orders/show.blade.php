@extends('layouts.admin-main')

@section('title', 'Order Details')

@section('content')
    <div class="container py-5">
        <h2 class="fw-bold mb-4 text-success">Order #{{ $order->id }}</h2>

        <p><strong>Buyer ID:</strong> {{ $order->buyer_id }}</p>
        <p><strong>Status:</strong> {{ $order->status }}</p>
        <p><strong>Total:</strong> RM {{ number_format($order->total_amount, 2) }}</p>
        <p><strong>Placed on:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>

        <h4 class="mt-4">Items:</h4>
        @foreach($order->items as $item)
            <div class="border p-2 mb-2 rounded">
                {{ $item->product->product_name ?? 'Unknown Product' }} — Qty: {{ $item->quantity }} — RM
                {{ number_format($item->price, 2) }}
            </div>
        @endforeach
    </div>
@endsection