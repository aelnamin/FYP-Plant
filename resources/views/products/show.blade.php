@extends('layouts.main')

@section('content')

<style>
    body {
        background-color: #FFE8F0;
        /* soft pink */
    }

    .product-image {
        width: 100%;
        height: 620px;
        object-fit: cover;
        border-radius: 16px;
    }

    .thumb-img {
        width: 90px;
        height: 90px;
        object-fit: cover;
        border-radius: 12px;
        cursor: pointer;
        border: 2px solid transparent;
    }

    .thumb-img:hover {
        border-color: #4BAE7F;
        /* matcha green */
    }

    .btn-matcha {
        background-color: #4BAE7F;
        color: white;
        padding: 12px 24px;
        border-radius: 50px;
        font-size: 17px;
        border: none;
        transition: 0.3s;
    }

    .btn-matcha:hover {
        background-color: #3e966d;
    }

    .back-btn {
        color: #4BAE7F;
        font-weight: bold;
        text-decoration: none;
    }
</style>

<a href="{{ route('products.browse') }}" class="back-btn">&larr; Back</a>

<div class="row mt-4 gy-4">

    <!-- LEFT IMAGE SECTION -->
    <div class="col-md-6">
        <img id="mainPreview"
            src="{{ asset('images/' . $product->images->first()->image_path) }}"
            class="product-image">

        <!-- Thumbnails -->
        <div class="mt-3 d-flex gap-2 flex-wrap">
            @foreach ($product->images as $image)
            <img src="{{ asset('images/' . $image->image_path) }}"
                class="thumb-img"
                onclick="document.getElementById('mainPreview').src=this.src;">
            @endforeach
        </div>

        <!-- Product Description (moved BELOW image) -->
        <div class="mt-4">
            <h4 class="fw-bold">Product Description</h4>
            <p style="line-height: 1.6;">
                {!! nl2br(e($product->description)) !!}
            </p>

            <!-- Plant Information Section -->
            <div class="mt-4">

                <h4 class="fw-bold">Plant Information</h4>

                <p class="text-muted mb-1">
                    <strong>Sunlight Requirement:</strong>
                    {{ $product->sunlight_requirement ?? 'Not specified' }}
                </p>

                <p class="text-muted mb-1">
                    <strong>Watering Frequency:</strong>
                    {{ $product->watering_frequency ?? 'Not specified' }}
                </p>

                <p class="text-muted mb-1">
                    <strong>Difficulty Level:</strong>
                    {{ $product->difficulty_level ?? 'Not specified' }}
                </p>

                <p class="text-muted mb-1">
                    <strong>Growth Stage:</strong>
                    {{ $product->growth_stage ?? 'Not specified' }}
                </p>

            </div>

        </div>
    </div>


    <!-- RIGHT DETAILS SECTION -->
    <div class="col-md-6">
        <h2 class="fw-bold">{{ $product->product_name }}</h2>

        <p class="text-muted mb-1">Seller:
            <strong>{{ $product->seller->business_name }}</strong>
        </p>

        <p class="text-muted">Category:
            {{ $product->category->category_name }}
        </p>


        <h3 class="text-success fw-bold">RM {{ number_format($product->price, 2) }}</h3>

        <!-- Stock indicator (NO white container) -->
        <div class="mt-3">
            @if ($product->stock_quantity > 0)
            <div class="d-flex align-items-center">
                <svg width="12" height="12" class="me-2">
                    <circle cx="6" cy="6" r="6" fill="#28a745"></circle>
                </svg>
                <span class="text-muted fw-medium">Stock Available</span>
            </div>
            @else
            <div class="d-flex align-items-center">
                <svg width="12" height="12" class="me-2">
                    <circle cx="6" cy="6" r="6" fill="#ff0000"></circle>
                </svg>
                <span class="text-muted fw-medium">Out of Stock</span>
            </div>
            @endif
        </div>

        <!-- Rating -->
        <div class="mt-3 flex items-center text-yellow-400">
            ⭐⭐⭐⭐⭐ <span class="text-gray-500 ms-2 text-sm">4.9/5 (128 reviews)</span>
        </div>
        <!-- Quantity Selector + Add to cart -->
        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="d-flex align-items-center mt-4">
            @csrf
            <div class="quantity-selector me-3 d-flex align-items-center">
                <button type="button" class="btn btn-outline-secondary btn-sm me-1" id="decrement" style="width: 30px; height: 30px; border-radius: 50%;">-</button>

                <input type="number" name="quantity" id="quantity" value="1" min="1"
                    class="form-control text-center" style="width: 55px; height: 34px; font-size: 15px;">

                <button type="button" class="btn btn-outline-secondary btn-sm ms-1" id="increment" style="width: 30px; height: 30px; border-radius: 50%;">+</button>

                <button type="submit" class="btn btn-matcha ms-2">Add to Cart</button>
            </div>
        </form>
    </div>
</div>

</div>

@endsection


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const decrementBtn = document.getElementById('decrement');
        const incrementBtn = document.getElementById('increment');
        const quantityInput = document.getElementById('quantity');
        const cartQuantityHidden = document.getElementById('cartQuantity');

        function sync() {
            cartQuantityHidden.value = quantityInput.value;
        }

        decrementBtn.addEventListener('click', function() {
            let value = parseInt(quantityInput.value);
            if (value > 1) quantityInput.value = value - 1;
            sync();
        });

        incrementBtn.addEventListener('click', function() {
            let value = parseInt(quantityInput.value);
            quantityInput.value = value + 1;
            sync();
        });

        quantityInput.addEventListener('input', sync);
    });
</script>