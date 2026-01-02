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

            <div class="mb-3">
                <label>Variants (comma separated)</label>
                <input type="text" name="variants_input" class="form-control"
                    placeholder="White Lily, Pink Lily, Yellow Lily"
                    value="{{ old('variants_input', isset($product) && $product->variants ? implode(', ', json_decode($product->variants, true)) : '') }}">
                <small class="text-muted">
                    Example: Small, Medium, Large OR Red, Blue, Green
                </small>
            </div>

            {{-- PLANT CARE DETAILS (ONLY FOR PLANT CATEGORIES) --}}
            <div id="plant-care-fields" style="display: none;">

                {{-- SUNLIGHT REQUIREMENT --}}
                <div class="mb-3">
                    <label>Sunlight Requirement</label>
                    <select name="sunlight_requirement" class="form-control">
                        <option value="">Select</option>
                        <option value="Full Sun to Partial Shade" {{ $product->sunlight_requirement == 'Full Sun to Partial Shade' ? 'selected' : '' }}>Full Sun to Partial Shade</option>
                        <option value="Not Applicable (Accessory)" {{ $product->sunlight_requirement == 'Not Applicable (Accessory)' ? 'selected' : '' }}>Not Applicable (Accessory)</option>
                        <option value="Not Applicable (Cut Flowers)" {{ $product->sunlight_requirement == 'Not Applicable (Cut Flowers)' ? 'selected' : '' }}>Not Applicable (Cut Flowers)</option>
                        <option value="Full Sun" {{ $product->sunlight_requirement == 'Full Sun' ? 'selected' : '' }}>Full Sun
                        </option>
                        <option value="Bright Indirect" {{ $product->sunlight_requirement == 'Bright Indirect' ? 'selected' : '' }}>Bright Indirect</option>
                    </select>
                </div>

                {{-- WATERING FREQUENCY --}}
                <div class="mb-3">
                    <label>Watering Frequency</label>
                    <select name="watering_frequency" class="form-control">
                        <option value="">Select</option>
                        <option value="Daily" {{ $product->watering_frequency == 'Daily' ? 'selected' : '' }}>Daily</option>
                        <option value="Consistent" {{ $product->watering_frequency == 'Consistent' ? 'selected' : '' }}>
                            Consistent</option>
                        <option value="Weekly" {{ $product->watering_frequency == 'Weekly' ? 'selected' : '' }}>Weekly
                        </option>
                        <option value="Not Applicable (Accessory)" {{ $product->watering_frequency == 'Not Applicable (Accessory)' ? 'selected' : '' }}>Not Applicable (Accessory)</option>
                        <option value="Infrequent (Dormancy)" {{ $product->watering_frequency == 'Infrequent (Dormancy)' ? 'selected' : '' }}>Infrequent (Dormancy)</option>
                        <option value="Infrequent (Allow Soil to Dry)" {{ $product->watering_frequency == 'Infrequent (Allow Soil to Dry)' ? 'selected' : '' }}>Infrequent (Allow Soil to Dry)</option>
                        <option value="Moderate" {{ $product->watering_frequency == 'Moderate' ? 'selected' : '' }}>Moderate
                        </option>
                    </select>
                </div>

                {{-- DIFFICULTY LEVEL --}}
                <div class="mb-3">
                    <label>Difficulty Level</label>
                    <select name="difficulty_level" class="form-control">
                        <option value="">Select</option>
                        <option value="Easy" {{ $product->difficulty_level == 'Easy' ? 'selected' : '' }}>Easy</option>
                        <option value="Medium" {{ $product->difficulty_level == 'Medium' ? 'selected' : '' }}>Medium</option>
                        <option value="Hard" {{ $product->difficulty_level == 'Hard' ? 'selected' : '' }}>Hard</option>
                    </select>
                </div>

                {{-- GROWTH STAGE --}}
                <div class="mb-3">
                    <label>Growth Stage</label>
                    <select name="growth_stage" class="form-control">
                        <option value="">Select</option>
                        <option value="Cut Flowers" {{ $product->growth_stage == 'Cut Flowers' ? 'selected' : '' }}>Cut
                            Flowers</option>
                        <option value="Bulb" {{ $product->growth_stage == 'Bulb' ? 'selected' : '' }}>Bulb</option>
                        <option value="Mature" {{ $product->growth_stage == 'Mature' ? 'selected' : '' }}>Mature</option>
                        <option value="Young" {{ $product->growth_stage == 'Young' ? 'selected' : '' }}>Young</option>
                        <option value="Tuber" {{ $product->growth_stage == 'Tuber' ? 'selected' : '' }}>Tuber</option>
                    </select>
                </div>


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

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const categorySelect = document.querySelector('select[name="category_id"]');
            const plantFields = document.getElementById('plant-care-fields');

            const plantCategories = [2, 4, 5];

            function togglePlantFields() {
                const selectedCategory = parseInt(categorySelect.value);

                if (plantCategories.includes(selectedCategory)) {
                    plantFields.style.display = 'block';
                } else {
                    plantFields.style.display = 'none';
                }
            }

            // IMPORTANT for edit form
            togglePlantFields();

            categorySelect.addEventListener('change', togglePlantFields);
        });
    </script>

@endsection