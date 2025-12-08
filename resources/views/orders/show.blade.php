@extends('layouts.app')

@section('content')
<h1>Order Details</h1>

<p><strong>ID:</strong> {{ $order->id }}</p>
<p><strong>Buyer:</strong> {{ $order->buyer->name }}</p>
<p><strong>Product:</strong> {{ $order->product->name }}</p>
<p><strong>Quantity:</strong> {{ $order->quantity }}</p>
<p><strong>Total Price:</strong> RM {{ number_format($order->total_price, 2) }}</p>
<p><strong>Status:</strong> {{ $order->status }}</p>

<a href="{{ route('orders.index') }}" class="btn btn-secondary">Back</a>
@endsection