@extends('layouts.app')

@section('content')
<h1>Order List</h1>

<a href="{{ route('orders.create') }}" class="btn btn-primary">Create Order</a>

<table class="table mt-3">
    <tr>
        <th>ID</th>
        <th>Buyer</th>
        <th>Product</th>
        <th>Qty</th>
        <th>Total Price</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>

    @foreach($orders as $order)
    <tr>
        <td>{{ $order->id }}</td>
        <td>{{ $order->buyer->name }}</td>
        <td>{{ $order->product->name }}</td>
        <td>{{ $order->quantity }}</td>
        <td>RM {{ number_format($order->total_price, 2) }}</td>
        <td>{{ $order->status }}</td>
        <td>
            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-info btn-sm">View</a>
            <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-warning btn-sm">Edit</a>
            <form action="{{ route('orders.destroy', $order->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger btn-sm" onclick="return confirm('Delete order?')">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
@endsection