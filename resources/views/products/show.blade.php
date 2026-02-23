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

        .price-old {
            text-decoration: line-through;
            color: #999;
            margin-right: 10px;
        }

        .btn-matcha {
            height: 42px;
            padding: 0 22px;
            font-weight: 500;
            border-radius: 40px;
        }


        .btn-matcha:hover {
            background-color: rgb(216, 220, 214);
        }

        .quantity-selector input {
            width: 55px;
            height: 34px;
            font-size: 15px;
            text-align: center;
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

        .qty-pill {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 140px;
            /* matches image */
            height: 42px;
            border: 1.5px solid #cfcfcf;
            border-radius: 999px;
            /* pill shape */
            padding: 0 14px;
            background: #fff;
        }

        .qty-btn {
            border: none;
            background: transparent;
            font-size: 20px;
            font-weight: 500;
            cursor: pointer;
            color: #333;
            width: 24px;
            height: 24px;
            line-height: 1;
        }

        .qty-btn:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        .qty-value {
            font-size: 16px;
            font-weight: 500;
            color: #000;
            min-width: 20px;
            text-align: center;
        }

        .seller-clickable {
            cursor: pointer;
            transition: all 0.25s ease;
        }

        .seller-clickable:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(92, 127, 81, 0.25);
        }

        .variant-radio {
            display: none;
            /* hide the actual radio button */
        }

        .variant-label {
            display: block;
            transition: all 0.2s ease;
        }

        .variant-label:hover {
            background-color: #e2e6ea !important;
            /* subtle hover effect */
            border-color: #adb5bd;
        }

        .variant-radio:checked+.variant-label {
            background-color: rgb(231, 231, 231) !important;
            color: #000;
            border-color: rgb(0, 0, 0);
        }
    </style>

    <div class="container mt-4">

        {{-- Breadcrumb --}}
        <nav class="breadcrumb mb-4">
            <a
                href="{{ auth()->check() && auth()->user()->role === 'buyer' ? route('buyer.dashboard') : route('home') }}">Home</a>
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

                <a href="{{ route('seller-shop', $product->seller->id) }}" class="text-decoration-none text-dark">

                    <div class="seller-card seller-clickable">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ $product->seller->user->profile_picture && file_exists(public_path($product->seller->user->profile_picture))
        ? asset($product->seller->user->profile_picture)
        : asset('images/default.png') }}" class="seller-profile-img me-3" alt="{{ $product->seller->business_name }}">

                            <div>
                                <h5 class="fw-bold mb-1">
                                    {{ $product->seller->business_name }}
                                </h5>
                                <span class="verified-badge">
                                    <i class="bi bi-check-circle"></i> Verified Seller
                                </span>
                            </div>
                        </div>

                        <div class="seller-info-item">
                            <i class="bi bi-geo-alt"></i>
                            <span>
                                <strong>Location:</strong>
                                {{ $product->seller->business_address ?? 'N/A' }}
                            </span>
                        </div>
                    </div>
                </a>


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
                @php
                    $oldPrice = $product->price + 30;
                    $discount = $oldPrice - $product->price;
                    $discountPercent = ($discount / $oldPrice) * 100;
                @endphp

                <div class="my-3">

                    {{-- Discount Percentage Above --}}
                    <div class="text-success fw-semibold mb-1">
                        Save {{ round($discountPercent) }}%
                    </div>

                    {{-- Old + New Price --}}
                    <div>
                        <span class="price-old text-muted text-decoration-line-through me-2">
                            RM {{ number_format($oldPrice, 2) }}
                        </span>

                        <span class="fs-4 fw-bold text-success">
                            RM {{ number_format($product->price, 2) }}
                        </span>
                    </div>

                </div>
                <hr>


                {{-- Add to Cart Form --}}
                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-4 d-flex flex-column gap-3">
                    @csrf

                    @if(!empty($variants) && count($variants) > 0)
                        <div class="mb-3">
                            <label class="form-label fw-semibold d-block mb-2">Select Variant</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($variants as $index => $variant)
                                    @php
                                        $id = 'variant-' . $index; // unique ID for each radio
                                    @endphp
                                    <div class="variant-option">
                                        <input type="radio" name="variant" id="{{ $id }}" value="{{ $variant }}"
                                            class="variant-radio" required>
                                        <label for="{{ $id }}" class="variant-label p-3 border rounded-3 shadow-sm text-center"
                                            style="min-width: 120px; background-color: #f8f9fa; cursor: pointer;">
                                            {{ $variant }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <input type="hidden" name="variant" value="">
                    @endif


                    {{-- Quantity selector --}}
                    <div class="d-flex align-items-center gap-3">
                        {{-- Quantity selector --}}
                        <div class="qty-pill">
                            <button type="button" class="qty-btn" id="decrement">−</button>
                            <span class="qty-value" id="quantity">1</span>
                            <button type="button" class="qty-btn" id="increment">+</button>
                        </div>

                        <input type="hidden" name="quantity" id="quantityInput" value="1">

                        @if ($product->stock_quantity <= 0)
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    document.getElementById('increment')?.setAttribute('disabled', true);
                                    document.getElementById('decrement')?.setAttribute('disabled', true);
                                });
                            </script>
                        @endif


                        {{-- Add to cart --}}
                        @if ($product->stock_quantity > 0)
                            <button type="submit" class="btn btn-matcha border-secondary text-dark shadow-sm">
                                Add to Cart
                            </button>
                        @else
                            <button type="button" class="btn btn-secondary shadow-sm" disabled>
                                Out of Stock
                            </button>
                        @endif

                    </div>
                </form>


                {{-- Stock indicator --}}
                <div class="mt-3">
                    @if ($product->stock_quantity > 0)
                        <div class="d-flex align-items-center"><svg width="12" height="12" class="me-2">
                                <circle cx="6" cy="6" r="6" fill="#28a745"></circle>
                            </svg><span class="text-muted fw-medium">Stock Available</span></div>
                    @else
                        <div class="d-flex align-items-center"><svg width="12" height="12" class="me-2">
                                <circle cx="6" cy="6" r="6" fill="#ff0000"></circle>
                            </svg><span class="text-muted fw-medium">Out of Stock</span></div>
                    @endif
                </div>
            </div>
        </div>

        <hr>

        {{-- Product Description --}}
        <div class="mt-4">
            <h4 class="fw-bold">Product Description</h4>
            <div class="col-6">
                <p style="line-height: 1.6;">{!! nl2br(e($product->description)) !!}</p>
            </div>

            {{-- Plant Information --}}
            @if($product->category->category_name !== 'Tools')
                <div class="mt-4">
                    <h4 class="fw-bold">Plant Information</h4>
                    <p class="text-muted mb-1"><strong>Sunlight Requirement:</strong>
                        {{ $product->sunlight_requirement ?? 'Not specified' }}</p>
                    <p class="text-muted mb-1"><strong>Watering Frequency:</strong>
                        {{ $product->watering_frequency ?? 'Not specified' }}</p>
                    <p class="text-muted mb-1"><strong>Difficulty Level:</strong>
                        {{ $product->difficulty_level ?? 'Not specified' }}</p>
                    <p class="text-muted mb-1"><strong>Growth Stage:</strong> {{ $product->growth_stage ?? 'Not specified' }}
                    </p>
                </div>
                {{-- Batch Plant Health Monitoring --}}
                @if($latestGrowth || $latestWatering) {{-- Only show if monitoring data exists --}}
                    <div class="mt-4 p-4 col-4 rounded-3 shadow-sm" style="background-color:#f3f8f4;">
                        <p class="text-muted mb-1" style="font-size: 18px;">
                            <strong>Overall Plant Health:</strong>
                            <span class="badge bg-{{ $healthColor ?? 'secondary' }} px-3 py-2 rounded-pill shadow-sm"
                                style="font-size: 16px;">
                                {{ $healthStatus ?? 'Unknown' }}
                            </span>
                        </p>
                    </div>
                @endif

            @endif


            {{-- Reviews --}}
            <div id="reviews" class="mt-5">
                <h4 class="fw-bold mb-3">Customer Reviews</h4>

                @if ($totalReviews > 0)
                    @foreach ($reviews as $review)
                        <div class="border rounded p-3 mb-3 bg-light">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <strong>{{ $review->user->name }}</strong>
                                <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="text-warning mb-1 fs-4">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= floor($review->rating))
                                        <i class="bi bi-star-fill"></i>
                                    @elseif ($i - $review->rating < 1)
                                        <i class="bi bi-star-half"></i>
                                    @else
                                        <i class="bi bi-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <p class="mb-0 text-muted">{{ $review->comment }}</p>
                        </div>
                    @endforeach

                    {{-- Pagination --}}
                    <div class="mt-3" id="reviews-pagination">
                        {{ $reviews->withQueryString()->fragment('reviews')->links() }}
                    </div>
                @else
                    <p class="text-muted">This product has no reviews yet</p>
                @endif
            </div>


            {{-- More from same seller --}}
            @if($sameSellerProducts->count())
                <div class="container mt-5">
                    <h4 class="fw-bold">More from {{ $product->seller->business_name }}</h4>
                    <br>
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
            @endif
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const decrementBtn = document.getElementById('decrement');
                const incrementBtn = document.getElementById('increment');
                const qtyDisplay = document.getElementById('quantity'); // the span
                const qtyInput = document.getElementById('quantityInput'); // hidden input

                decrementBtn?.addEventListener('click', () => {
                    let value = parseInt(qtyInput.value);
                    if (value > 1) {
                        value -= 1;
                        qtyInput.value = value;     // update hidden input
                        qtyDisplay.textContent = value; // update visible span
                    }
                });

                incrementBtn?.addEventListener('click', () => {
                    let value = parseInt(qtyInput.value);
                    value += 1;
                    qtyInput.value = value;         // update hidden input
                    qtyDisplay.textContent = value; // update visible span
                });
            });
        </script>


        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const variantSelect = document.querySelector('select[name="variant"]');
                const addToCartBtn = document.querySelector('.btn-matcha');

                if (!variantSelect || !addToCartBtn) return;

                // Save selected variant when "Add to Cart" is clicked
                addToCartBtn.addEventListener('click', function () {
                    const selectedValue = variantSelect.value;
                    if (selectedValue) {
                        localStorage.setItem('last_variant_product{{ $product->id }}', selectedValue);
                    }
                });

                // Load saved variant on page load
                const savedVariant = localStorage.getItem('last_variant_product{{ $product->id }}');
                if (savedVariant) {
                    for (let option of variantSelect.options) {
                        if (option.value === savedVariant) {
                            option.selected = true;
                            break;
                        }
                    }
                }
            });

        </script>
@endsection