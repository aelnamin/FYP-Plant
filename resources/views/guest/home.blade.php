@extends('layouts.main')

@section('content')

    {{-- SHOW BANNER ONLY FOR GUESTS / NON-BUYERS --}}
    @if(!Auth::check() || Auth::user()->role !== 'buyer')

        <head>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

        </head>

        <style>
            .icon-circle {
                width: 50px;
                height: 50px;
                background: #e9f5ec;
                color: #5C7F51;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 24px;
            }

            .search-container {
                position: relative;
                max-width: 600px;
                margin: 0 auto;
            }

            .search-input {
                border: 2px solid #e9ecef;
                border-radius: 50px;
                padding: 1rem 1.5rem;
                font-size: 1rem;
                transition: all 0.3s ease;
            }

            .search-input:focus {
                border-color: var(--primary);
                box-shadow: 0 0 0 3px rgba(75, 174, 127, 0.2);
            }

            .search-btn {
                position: absolute;
                right: 5px;
                top: 5px;
                bottom: 5px;
                background: var(--gradient);
                color: white;
                border: none;
                border-radius: 50px;
                padding: 0 1.5rem;
                font-weight: 600;
                transition: transform 0.3s ease;
            }

            .search-btn:hover {
                transform: scale(1.05);
            }

            .feature-section {
                background: linear-gradient(135deg, #f9f9f9 0%, rgb(241, 239, 218) 100%);
            }

            .feature-icon {
                width: 60px;
                height: 60px;
                border-radius: 16px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 28px;
                color: rgb(87, 125, 85);
                flex-shrink: 0;
            }

            .about-section {
                background: linear-gradient(135deg, #f9f9f9 0%, rgb(207, 201, 131) 100%);
            }

            .text-shadow {
                text-shadow: 0px 2px 6px rgba(0, 0, 0, 0.6);
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

        <!-- BANNER SECTION WITH SEARCH BAR ON TOP -->
        <section style="position: relative;">

            <!-- SEARCH BAR OVERLAY -->
            <div
                style="position: absolute; top: 50%; left: 50%;
                                                                                                                                                                                                                                                                                                                                                                        transform: translate(-50%, -50%); z-index: 10; width: 100%; max-width: 700px;
                                                                                                                                                                                                                                                                                                                                                                        text-align: center; color: white;">

                <h1 class="fw-bold text-shadow">Welcome to Aether & Leaf Co.</h1>
                <p class="text-shadow">Your trusted place for plants & gardening essentials</p>


                <!-- SEARCH BAR -->
                <form method="GET" action="{{ route('products.browse') }}" class="search-container mb-4">
                    <input type="text" name="search" class="form-control search-input"
                        placeholder="Search for plants, tools, seeds..." value="{{ request('search') }}">
                    <button type="submit" class="search-btn">
                        <i class="bi bi-search me-2"></i> Search
                    </button>
                </form>
            </div>

            <!-- IMAGE CAROUSEL -->
            <div id="plantBanner" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                <div class="carousel-inner">

                    <div class="carousel-item active">
                        <img src="{{ asset('images/banner7.jpg') }}" class="d-block w-100"
                            style="height: 529px; object-fit: cover; border-radius: 20px;">
                    </div>

                    <div class="carousel-item">
                        <img src="{{ asset('images/banner9.jpg') }}" class="d-block w-100"
                            style="height: 529px; object-fit: cover; border-radius: 20px;">
                    </div>

                    <div class="carousel-item">
                        <img src="{{ asset('images/banner11.jpg') }}" class="d-block w-100"
                            style="height: 529px; object-fit: cover; border-radius: 20px;">
                    </div>

                </div>
            </div>

        </section>

    @endif


    <!-- BEST SELLERS -->
    <div class="container mt-5">
        <h2 class="fw-bold mb-3">Best Sellers</h2>

        <div class="row g-4">
            @foreach ($bestSellers as $p)
                <div class="col-6 col-md-3">
                    <div class="card product-card border-0 rounded-4 h-100 overflow hidden">

                        <img src="{{ $p->images->first() ? asset('images/' . $p->images->first()->image_path) : asset('images/default.jpg') }}"
                            class="card-img-top rounded-top-4" style="height:280px; object-fit:cover;">

                        <div class="card-body">
                            <h6 class="fw-bold">{{ $p->product_name }}</h6>
                            <div class="text-muted small"> <i
                                    class="bi bi-shop me-1"></i>{{ $p->seller->business_name ?? 'Unknown Seller' }}</div>
                            <div class="fw-bold text-success mt-2">RM {{ number_format($p->price, 2) }}</div>
                        </div>

                        <div class="card-footer bg-white">
                            <a href="{{ route('products.show', $p->id) }}" class="btn btn-outline-success w-100 rounded-pill">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>


    <!-- Latest Products -->
    <div class="container mt-5">
        <h2 class="fw-bold mb-3">Latest Products</h2>

        <div class="row g-4">
            @foreach ($latestProducts as $p)
                <div class="col-6 col-md-3">
                    <div class="card product-card border-0 rounded-4 h-100 overflow hidden">

                        <img src="{{ $p->images->first() ? asset('images/' . $p->images->first()->image_path) : asset('images/default.jpg') }}"
                            class="card-img-top rounded-top-4" style="height:280px; object-fit:cover;">

                        <div class="card-body">
                            <h6 class="fw-bold">{{ $p->product_name }}</h6>
                            <div class="text-muted small">{{ $p->seller->business_name ?? 'Unknown Seller' }}</div>
                            <div class="fw-bold text-success mt-2">RM {{ number_format($p->price, 2) }}</div>
                        </div>

                        <div class="card-footer bg-white">
                            <a href="{{ route('products.show', $p->id) }}" class="btn btn-outline-success w-100 rounded-pill">
                                View Details
                            </a>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    </div>


    <!-- Top Sellers -->
    <div class="container mt-5">
        <h2 class="fw-bold mb-3">Top Sellers</h2>

        <div class="row g-4">
            @foreach ($topSellers as $seller)
                <div class="col-6 col-md-3">
                    <div class="text-center p-4 bg-white card product-card border-0 rounded-4 h-100 overflow hidden">

                        {{-- Profile Picture --}}
                        <img src="{{ $seller->user && $seller->user->profile_picture
                ? asset($seller->user->profile_picture)
                : asset('images/default.png') }}" class="rounded-circle mx-auto mb-3"
                            style="width:90px; height:90px; object-fit:cover;" alt="{{ $seller->business_name }}">


                        {{-- Seller Name --}}
                        <h6 class="fw-bold">{{ $seller->business_name }}</h6>
                        <p class="text-muted small"><i class="bi bi-patch-check"></i> Trusted Seller</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>


    <!-- FEATURES & ABOUT -->
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="p-4 shadow-sm feature-section rounded-4 mb-4 d-flex align-items-start">
                    <div class="feature-icon me-4">
                        <i class="bi bi-leaf"></i>
                    </div>
                    <div>
                        <h4 class="fw-semibold mb-2">Discover Green Diversity</h4>
                        <p class="text-muted mb-0">
                            Explore unique foliage, vibrant succulents, and over 100 species selected to suit every
                            lifestyle and space.
                        </p>
                    </div>
                </div>

                <div class="p-4 shadow-sm feature-section rounded-4 mb-4 d-flex align-items-start">
                    <div class="feature-icon me-4">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div>
                        <h4 class="fw-semibold mb-2">Grown with Love</h4>
                        <p class="text-muted mb-0">
                            Each plant is carefully nurtured and inspected to ensure it arrives healthy and beautiful at
                            your doorstep.
                        </p>
                    </div>
                </div>

                <div class="p-4 shadow-sm feature-section rounded-4 d-flex align-items-start">
                    <div class="feature-icon me-4">
                        <i class="bi bi-truck"></i>
                    </div>
                    <div>
                        <h4 class="fw-semibold mb-2">Fast Delivery</h4>
                        <p class="text-muted mb-0">
                            Order by 4pm for same-day delivery across KL/Selangor—fresh, fast, and handled with care.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="p-5 about-section rounded-4 h-100">
                    <h2 class="fw-bold mb-4">About Aether & Leaf Co.</h2>
                    <p class="text-muted mb-4">
                        Aether & Leaf Co. is where nature meets minimalism. We curate a collection of indoor plants,
                        premium pots, and gardening essentials designed to bring calm, beauty, and freshness into every
                        space.
                    </p>
                    <p class="text-muted mb-0">
                        Whether you're a beginner or a plant lover, we make it easy to grow greenery with confidence,
                        offering expert advice and quality products for every plant journey.
                    </p>
                </div>
            </div>
        </div>
@endsection