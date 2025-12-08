@extends('layouts.app')

@section('content')
<h1>Create Order</h1>

<form method="POST" action="{{ route('orders.store') }}">
    @csrf

    <label>Buyer:</label>
    <select name="buyer_id" class="form-control">
        @foreach($buyers as $buyer)
        <option value="{{ $buyer->id }}">{{ $buyer->name }}</option>
        @endforeach
    </select>

    <label>Product:</label>
    <select name="product_id" class="form-control">
        @foreach($products as $product)
        <option value="{{ $product->id }}">{{ $product->name }}</option>
        @endforeach
    </select>

    <label>Quantity:</label>
    <input type="number" name="quantity" class="form-control" required>

    <button class="btn btn-success mt-3">Create Order</button>
</form>
@endsection