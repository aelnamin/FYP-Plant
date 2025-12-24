@extends('layouts.sellers-main')

@section('title', 'Add New Product')

@section('content')
    <div class="container mt-4">
        <h2>Add New Product</h2>

        <form action="{{ route('sellers.inventory.store') }}" method="POST" enctype="multipart/form-data">
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
                <label>Description</label>
                <textarea name="description" class="form-control" rows="4"></textarea>
            </div>

            <div class="mb-3">
                <label>Variants (comma separated)</label>
                <input type="text" name="variants_input" class="form-control"
                    placeholder="White Lily, Pink Lily, Yellow Lily"
                    value="{{ old('variants_input', isset($product) && $product->variants ? implode(', ', json_decode($product->variants, true)) : '') }}">
                <small class="text-muted">
                    Example: Small, Medium, Large OR Red, Blue, Green
                </small>
            </div>


            <div class="mb-3">
                <label>Price (RM)</label>
                <input type="number" step="0.01" name="price" class="form-control">
            </div>

            <div class="mb-3">
                <label>Stock Quantity</label>
                <input type="number" name="stock_quantity" class="form-control">
            </div>

            {{-- Sunlight Requirement --}}
            <div class="mb-3">
                <label class="form-label">Sunlight Requirement</label>
                <select name="sunlight_requirement" class="form-control">
                    <option value="">Select</option>
                    <option value="Full Sun to Partial Shade">Full Sun to Partial Shade</option>
                    <option value="Not Applicable (Accessory)">Not Applicable (Accessory)</option>
                    <option value="Not Applicable (Cut Flowers)">Not Applicable (Cut Flowers)</option>
                    <option value="Full Sun">Full Sun</option>
                    <option value="Bright Indirect">Bright Indirect</option>
                </select>
            </div>

            {{-- Watering Frequency --}}
            <div class="mb-3">
                <label class="form-label">Watering Frequency</label>
                <select name="watering_frequency" class="form-control">
                    <option value="">Select</option>
                    <option value="Daily">Daily</option>
                    <option value="Consistent">Consistent</option>
                    <option value="Weekly">Weekly</option>
                    <option value="Not Applicable (Accessory)">Not Applicable (Accessory)</option>
                    <option value="Infrequent (Dormancy)">Infrequent (Dormancy)</option>
                    <option value="Infrequent (Allow Soil to Dry)">Infrequent (Allow Soil to Dry)</option>
                    <option value="Moderate">Moderate</option>
                </select>
            </div>

            {{-- Difficulty Level --}}
            <div class="mb-3">
                <label class="form-label">Difficulty Level</label>
                <select name="difficulty_level" class="form-control">
                    <option value="">Select</option>
                    <option value="Easy">Easy</option>
                    <option value="Medium">Medium</option>
                    <option value="Hard">Hard</option>
                </select>
            </div>

            {{-- Growth Stage --}}
            <div class="mb-3">
                <label class="form-label">Growth Stage</label>
                <select name="growth_stage" class="form-control">
                    <option value="">Select</option>
                    <option value="Cut Flowers">Cut Flowers</option>
                    <option value="Bulb">Bulb</option>
                    <option value="Mature">Mature</option>
                    <option value="Young">Young</option>
                    <option value="Tuber">Tuber</option>
                </select>
            </div>


            <div class="mb-3">
                <label>Upload Images</label>
                <input type="file" name="images[]" class="form-control" multiple>
            </div>

            <button type="submit" class="btn btn-success">Save Product</button>
        </form>
    </div>
@endsection