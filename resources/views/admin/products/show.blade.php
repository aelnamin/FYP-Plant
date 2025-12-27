@extends('layouts.admin-main')

@section('title', 'Product Details')

@section('content')
    <div class="container mt-4">

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold text-success">Product Details</h5>
            <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-secondary">Back to List</a>
        </div>

        <div class="card shadow-sm rounded-4 p-4">

            <div class="row">
                <!-- Product Image -->
                <div class="col-md-5 text-center">
                    <img src="{{ $product->images->first()
        ? asset('images/' . $product->images->first()->image_path)
        : asset('images/default.jpg') }}" class="img-fluid rounded mb-3" alt="{{ $product->product_name }}">
                </div>

                <!-- Product Details -->
                <div class="col-md-7">
                    <h4 class="fw-bold">{{ $product->product_name }}</h4>

                    <p><strong>Category:</strong> {{ $product->category->category_name ?? 'N/A' }}</p>
                    <p><strong>Seller:</strong> {{ $product->seller->business_name ?? 'N/A' }}</p>
                    <p><strong>Price:</strong> <span class="text-success fw-bold">RM
                            {{ number_format($product->price, 2) }}</span></p>
                    <p><strong>Stock:</strong> {{ $product->stock_quantity }}</p>
                    <p><strong>Description:</strong></p>
                    <div class="border rounded p-2 mb-3" style="background-color: #f9f9f9;">
                        {!! nl2br(e($product->description)) !!}
                    </div>

                    <div class="mt-3">
                        {{-- Delete product form --}}
                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Are you sure you want to delete this product?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-warning">Delete</button>
                        </form>

                        {{-- Back button --}}
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Back</a>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection