@extends('layouts.app')

@section('content')
<h1>Edit Product</h1>

<form action="{{ route('products.update', $product->id) }}" method="POST">
    @csrf @method('PUT')

    <label>Name:</label>
    <input type="text" name="name" class="form-control" value="{{ $product->name }}">

    <label>Description:</label>
    <textarea name="description" class="form-control">{{ $product->description }}</textarea>

    <label>Price:</label>
    <input type="number" name="price" class="form-control" value="{{ $product->price }}">

    <label>Stock:</label>
    <input type="number" name="stock" class="form-control" value="{{ $product->stock }}">

    <button class="btn btn-primary mt-3">Update</button>
</form>
@endsection