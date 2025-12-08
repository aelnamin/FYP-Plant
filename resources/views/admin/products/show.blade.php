@extends('layouts.admin-main')

@section('content')
<div class="container mt-4">
    <h2>Product Details</h2>

    <div class="card p-4">

        <h4>{{ $product->product_name }}</h4>

        <p><strong>Category:</strong> {{ $product->category->category_name ?? 'N/A' }}</p>
        <p><strong>Seller:</strong> {{ $product->seller->business_name ?? 'N/A' }}</p>
        <p><strong>Price:</strong> RM {{ number_format($product->price, 2) }}</p>
        <p><strong>Stock:</strong> {{ $product->stock_quantity }}</p>
        <p><strong>Description:</strong> {!! nl2br(e($product->description)) !!}</p>

        <h5 class="mt-4">Product Images</h5>
        @foreach($product->images as $img)
        <img src="{{ asset('storage/' . $img->image_path) }}" width="150" class="me-2 mb-3">
        @endforeach

        <div class="mt-3">
            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning">Edit</a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Back</a>
        </div>

    </div>
</div>
@endsection