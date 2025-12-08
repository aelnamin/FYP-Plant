@extends('layouts.app')

@section('content')
<h1>Edit Order</h1>

<form method="POST" action="{{ route('orders.update', $order->id) }}">
    @csrf
    @method('PUT')

    <label>Quantity:</label>
    <input type="number" name="quantity" class="form-control" value="{{ $order->quantity }}">

    <label>Status:</label>
    <select name="status" class="form-control">
        <option {{ $order->status == 'Pending' ? 'selected' : '' }}>Pending</option>
        <option {{ $order->status == 'Shipped' ? 'selected' : '' }}>Shipped</option>
        <option {{ $order->status == 'Completed' ? 'selected' : '' }}>Completed</option>
        <option {{ $order->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
    </select>

    <button class="btn btn-primary mt-3">Update</button>
</form>
@endsection