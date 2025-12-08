@extends('layouts.admin-main')

@section('content')
<div class="container mt-4">
    <h2>Add New Product</h2>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Product Name</label>
            <input type="text" name="product_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Category</label>
            <select class="form-control" name="category_id" required>
                <option value="">Select Category</option>
                @foreach($categories as $c)
                <option value="{{ $c->id }}">{{ $c->category_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Seller</label>
            <select class="form-control" name="seller_id" required>
                <option value="">Select Seller</option>
                @foreach($sellers as $s)
                <option value="{{ $s->id }}">{{ $s->business_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="4"></textarea>
        </div>

        <div class="mb-3">
            <label>Price (RM)</label>
            <input type="number" step="0.01" name="price" class="form-control">
        </div>

        <div class="mb-3">
            <label>Stock Quantity</label>
            <input type="number" name="stock_quantity" class="form-control">
        </div>

        <div class="mb-3">
            <label>Upload Images</label>
            <input type="file" name="images[]" class="form-control" multiple>
        </div>

        <button class="btn btn-success">Save Product</button>
    </form>
</div>
@endsection