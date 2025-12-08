@extends('layouts.app')

@section('content')
<h1>Add Product</h1>

<form action="{{ route('products.store') }}" method="POST">
    @csrf

    <label>Seller:</label>
    <select name="seller_id" class="form-control">
        @foreach ($sellers as $seller)
        <option value="{{ $seller->id }}">{{ $seller->name }}</option>
        @endforeach
    </select>

    <label>Name:</label>
    <input type="text" name="name" class="form-control">

    <label>Description:</label>
    <textarea name="description" class="form-control"></textarea>

    <label>Price:</label>
    <input type="number" name="price" class="form-control">

    <label>Stock:</label>
    <input type="number" name="stock" class="form-control">

    <button class="btn btn-success mt-3">Save</button>
</form>
@endsection