@extends('layouts.main')

@section('title', $product->product_name)

@section('content')

    <style>
        body {
            background-color: #ffffff;
        }

        .breadcrumb a {
            text-decoration: none;
            color: #6c757d;
            font-size: 14px;
        }

        .main-image {
            width: 100%;
            height: 520px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #eee;
        }

        .thumb-img {
            width: 100px;
            height: 90px;
            object-fit: cover;
            border-radius: 6px;
            cursor: pointer;
            border: 1px solid #ddd;
        }

        .thumb-img:hover {
            border-color: #198754;
        }

        .option-box {
            border: 1px solid #ddd;
            padding: 12px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            background: #fff;
        }

        .option-box.active,
        .option-box:hover {
            border-color: #198754;
            background-color: #f6fff9;
        }

        .price-old {
            text-decoration: line-through;
            color: #999;
            margin-right: 10px;
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

        .btn-cart:hover {
            background-color: #157347;
        }

        .back-btn {
            color: #4BAE7F;
            font-weight: bold;
            text-decoration: none;
        }

        .seller-card {
            background: linear-gradient(135deg, #f9f9f9 0%, rgb(200, 208, 184) 100%);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .seller-profile-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #4BAE7F;
        }

        .seller-info-item {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 5px;
            color: #555;
            font-size: 14px;
        }

        .seller-info-item i {
            color: #4BAE7F;
            width: 16px;
        }

        .verified-badge {
            background: #d4edda;
            color: #155724;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .product-card {
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.08);
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(92, 127, 81, 0.15);
        }
    </style>

    <div class="container mt-4">

        {{-- Breadcrumb --}}
        <nav class="breadcrumb mb-4">
            <a href="{{ route('buyer.dashboard') }}">Home</a>
            <span class="mx-2">/</span>
            <a href="#">{{ $product->category->category_name }}</a>
            <span class="mx-2">/</span>
            <span class="text-dark">{{ $product->product_name }}</span>
        </nav>

        <div class="row g-4">

            {{-- LEFT: Thumbnails --}}
            <div class="col-md-1 d-flex flex-column gap-2">
                @foreach ($product->images as $image)
                    <img src="{{ asset('images/' . $image->image_path) }}" class="thumb-img"
                        onclick="document.getElementById('mainPreview').src=this.src">
                @endforeach
            </div>

            {{-- CENTER: Main Image --}}
            <div class="col-md-5">
                <img id="mainPreview" src="{{ asset('images/' . $product->images->first()->image_path) }}"
                    class="main-image">
            </div>

            {{-- RIGHT: Product Details --}}
            <div class="col-md-6">
                <h2 class="fw-bold">{{ $product->product_name }}</h2>

                {{-- Seller Information Card --}}
                <div class="seller-card">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ $product->seller->user->profile_picture && file_exists(public_path($product->seller->user->profile_picture))
        ? asset($product->seller->user->profile_picture)
        : asset('images/default.png') }}" class="seller-profile-img me-3" alt="{{ $product->seller->business_name }}">

                        <div>
                            <h5 class="fw-bold mb-1">{{ $product->seller->business_name }}</h5>
                            <span class="verified-badge ms-2">
                                <i class="bi bi-check-circle"></i>Verified Seller
                            </span>
                        </div>
                    </div>

                    {{-- Location --}}
                    <div class="seller-info-item">
                        <i class="bi bi-geo-alt"></i>
                        <span><strong>Location:</strong> {{ $product->seller->business_address ?? 'N/A' }}</span>
                    </div>
                </div>

                {{-- Rating --}}
                @if ($totalReviews > 0)
                    <div class="text-success mb-2">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= floor($averageRating))
                                <i class="bi bi-star-fill"></i>
                            @elseif ($i - $averageRating < 1)
                                <i class="bi bi-star-half"></i>
                            @else
                                <i class="bi bi-star"></i>
                            @endif
                        @endfor
                        <span class="text-muted ms-2">{{ $averageRating }} ({{ $totalReviews }} reviews)</span>
                    </div>
                @else
                    <span class="text-muted">No reviews yet</span>
                @endif

                {{-- Price --}}
                <div class="my-3">
                    <span class="price-old">RM {{ number_format($product->price + 30, 2) }}</span>
                    <span class="fs-4 fw-bold text-success">RM {{ number_format($product->price, 2) }}</span>
                </div>
                <hr>

                {{-- Variant Selection (only if variants exist) --}}
                @if(count($variants) > 0)
                    <h6 class="fw-semibold mt-4">Select Variant</h6>
                    <div class="d-flex gap-2 flex-wrap" id="variantOptions">
                        @foreach($variants as $variant)
                            <div class="option-box" data-variant="{{ $variant }}">{{ $variant }}</div>
                        @endforeach
                    </div>
                @endif
                <input type="hidden" name="variant" id="selectedVariant" value="">
                <small class="text-danger d-none" id="variantError">Please select a variant</small>

                {{-- Stock indicator --}}
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

                {{-- Quantity Selector + Add to Cart --}}
                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="d-flex align-items-center mt-4"
                    id="addToCartForm">
                    @csrf
                    <div class="quantity-selector me-3 d-flex align-items-center">
                        <button type="button" class="btn btn-outline-secondary btn-sm me-1" id="decrement"
                            style="width: 30px; height: 30px; border-radius: 50%;">-</button>
                        <input type="number" name="quantity" id="quantity" value="1" min="1"
                            class="form-control text-center" style="width: 55px; height: 34px; font-size: 15px;">
                        <button type="button" class="btn btn-outline-secondary btn-sm ms-1" id="increment"
                            style="width: 30px; height: 30px; border-radius: 50%;">+</button>
                        <button type="button" class="btn btn-matcha ms-2" id="addToCartBtn">Add to Cart</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <hr>

    <!-- Product Description -->
    <div class="mt-4">
        <h4 class="fw-bold">Product Description</h4>
        <p style="line-height: 1.6;">{!! nl2br(e($product->description)) !!}</p>

        <!-- Plant Information -->
        <div class="mt-4">
            <h4 class="fw-bold">Plant Information</h4>
            <p class="text-muted mb-1"><strong>Sunlight Requirement:</strong>
                {{ $product->sunlight_requirement ?? 'Not specified' }}</p>
            <p class="text-muted mb-1"><strong>Watering Frequency:</strong>
                {{ $product->watering_frequency ?? 'Not specified' }}</p>
            <p class="text-muted mb-1"><strong>Difficulty Level:</strong>
                {{ $product->difficulty_level ?? 'Not specified' }}</p>
            <p class="text-muted mb-1"><strong>Growth Stage:</strong> {{ $product->growth_stage ?? 'Not specified' }}</p>
        </div>

        {{-- Reviews --}}
        <div class="mt-5">
            <h4 class="fw-bold mb-3">Customer Reviews</h4>
            @if ($totalReviews > 0)
                <div class="mb-4">
                    <div class="text-success fs-5">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= floor($averageRating))
                                <i class="bi bi-star-fill"></i>
                            @elseif ($i - $averageRating < 1)
                                <i class="bi bi-star-half"></i>
                            @else
                                <i class="bi bi-star"></i>
                            @endif
                        @endfor
                    </div>
                    <span class="text-muted">{{ $averageRating }} out of 5 · {{ $totalReviews }} reviews</span>
                </div>

                @foreach ($product->reviews as $review)
                    <div class="border rounded p-3 mb-3 bg-light">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <strong>{{ $review->user->name }}</strong>
                            <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                        </div>
                        <div class="text-warning mb-1">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $review->rating) ★ @else ☆ @endif
                            @endfor
                        </div>
                        <p class="mb-0 text-muted">{{ $review->comment }}</p>
                    </div>
                @endforeach
            @else
                <p class="text-muted">This product has no reviews yet.</p>
            @endif
        </div>

        {{-- Same Seller Products --}}
        @if($sameSellerProducts->count())
            <div class="container mt-5">
                <div class="col-md-8">
                    <h4 class="fw-bold">More from {{ $product->seller->business_name }}</h4>
                    <div class="row g-4">
                        @foreach ($sameSellerProducts as $p)
                            <div class="col-6 col-md-3">
                                <div class="card border-0 product-card shadow-sm h-100 rounded-4">
                                    <img src="{{ $p->images->first() ? asset('images/' . $p->images->first()->image_path) : asset('images/default.jpg') }}"
                                        class="card-img-top" style="height:220px; object-fit:cover;">
                                    <div class="card-body">
                                        <h6 class="fw-bold">{{ $p->product_name }}</h6>
                                        <div class="text-success fw-bold">RM {{ number_format($p->price, 2) }}</div>
                                    </div>
                                    <div class="card-footer bg-white border-0">
                                        <a href="{{ route('products.show', $p->id) }}"
                                            class="btn btn-outline-success w-100 rounded-pill">View Product</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const decrementBtn = document.getElementById('decrement');
            const incrementBtn = document.getElementById('increment');
            const quantityInput = document.getElementById('quantity');
            const variantBoxes = document.querySelectorAll('.option-box');
            const selectedVariantInput = document.getElementById('selectedVariant');
            const variantError = document.getElementById('variantError');
            const addToCartBtn = document.getElementById('addToCartBtn');
            const form = document.getElementById('addToCartForm');
            const cartSidebarEl = document.getElementById('cartSidebar');
            const cartSidebar = cartSidebarEl ? new bootstrap.Offcanvas(cartSidebarEl) : null;

            // Quantity buttons
            decrementBtn?.addEventListener('click', () => {
                let value = parseInt(quantityInput.value);
                if (value > 1) quantityInput.value = value - 1;
            });
            incrementBtn?.addEventListener('click', () => {
                quantityInput.value = parseInt(quantityInput.value) + 1;
            });

            // Variant selection (if exists)
            variantBoxes.forEach(box => {
                box.addEventListener('click', function () {
                    variantBoxes.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    selectedVariantInput.value = this.dataset.variant;
                    variantError.classList.add('d-none');
                });
            });

            // Add to Cart
            addToCartBtn?.addEventListener('click', function () {
                // If variants exist, make sure one is selected
                if (variantBoxes.length > 0 && !selectedVariantInput.value) {
                    variantError.classList.remove('d-none');
                    return;
                }

                fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form)
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            if (cartSidebar) cartSidebar.show();
                        } else {
                            alert(data.message || 'Error adding to cart');
                        }
                    })
                    .catch(err => console.error('Add to cart error:', err));
            });
        });
    </script>
@endsection