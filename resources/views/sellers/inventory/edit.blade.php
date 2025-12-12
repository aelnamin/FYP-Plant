@extends('layouts.sellers-main')

@section('title', 'Edit Product')

@section('content')
    <div class="container mt-4">

        <h2>Edit Product</h2>

        {{-- SUCCESS MESSAGE --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- UPDATE PRODUCT FORM --}}
        <form action="{{ route('sellers.inventory.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') {{-- Keep POST because your route uses POST for update --}}

            {{-- CATEGORY --}}
            <div class="mb-3">
                <label>Category</label>
                <select name="category_id" class="form-control" required>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->category_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- PRODUCT NAME --}}
            <div class="mb-3">
                <label>Product Name</label>
                <input type="text" class="form-control" name="product_name" value="{{ $product->product_name }}" required>
            </div>

            {{-- DESCRIPTION --}}
            <div class="mb-3">
                <label>Description</label>
                <textarea class="form-control" name="description" rows="4">{{ $product->description }}</textarea>
            </div>

            {{-- PRICE --}}
            <div class="mb-3">
                <label>Price (RM)</label>
                <input type="number" class="form-control" name="price" value="{{ $product->price }}" step="0.01" required>
            </div>

            {{-- STOCK --}}
            <div class="mb-3">
                <label>Stock Quantity</label>
                <input type="number" class="form-control" name="stock_quantity" value="{{ $product->stock_quantity }}"
                    required>
            </div>

            {{-- EXISTING IMAGES --}}
            <div class="mb-3">
                <label>Existing Images</label>
                <div class="row">
                    @foreach($product->images as $img)
                        <div class="col-md-3 text-center mb-3" data-image-id="{{ $img->id }}">
                            <img src="{{ asset('images/' . $img->image_path) }}" class="img-fluid rounded mb-2">
                            <input type="checkbox" name="remove_images[]" value="{{ $img->id }}"> Remove
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- UPLOAD NEW IMAGES --}}
            <div class="mb-3">
                <label>Upload New Images (optional)</label>
                <input type="file" name="images[]" class="form-control" multiple>
                <small class="text-muted">Leave empty if you do NOT want to change images.</small>
            </div>

            {{-- UPDATE PRODUCT BUTTON --}}
            <button type="submit" class="btn btn-primary">Update Product</button>
        </form>
    </div>
@endsection