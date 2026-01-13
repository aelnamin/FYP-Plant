@extends('layouts.main')

@section('title', 'Buyer Dashboard')

@section('content')

    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    </head>

    <style>
:root {
    --primary-green: #5C7F51;  /* Your brand green */
    --light-green: #8AA67E;    /* Lighter green */
    --primary-gold: #FFD700;   /* Gold for Best Sellers */
    --light-gold: #FFA500;     /* Orange gold */
    --primary-blue: #4A90E2;   /* Blue for Latest Products */
    --light-blue: #7B68EE;     /* Purple blue */
}

/* Section Title Styles */
.section-title {
    position: relative;
    display: inline-block;
    padding-bottom: 15px;
    margin-bottom: 20px;
    font-size: 2.2rem;
    font-weight: 800;
    letter-spacing: 0.5px;
}

.section-title:after {
    content: '';
    position: absolute;
    left: 50%;
    bottom: 0;
    transform: translateX(-50%);
    width: 100px;
    height: 4px;
    border-radius: 2px;
}

.section-subtitle {
    font-size: 1.1rem;
    color: #6c757d;
    margin-top: 10px;
}

/* Color Variations */
.section-title.best-sellers:after {
    background: linear-gradient(90deg, var(--primary-gold), var(--light-green));
}

.section-title.latest-products:after {
    background: linear-gradient(90deg, var(--primary-gold), var(--light-green));
}

.section-title.top-sellers:after {
    background: linear-gradient(90deg, var(--primary-gold), var(--light-green));
}

/* Optional: Add animation on hover */
.section-title {
    transition: all 0.3s ease;
}

.section-title:hover:after {
    width: 120px;
    box-shadow: 0 0 15px rgba(92, 127, 81, 0.3);
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

        .category-card {
            width: 120px;
            background: #f9f9f9;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .category-card:hover {
            background: #eaf7f1;
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .category-img {
            width: 48px;
            height: 48px;
            display: block;
            margin: 0 auto 8px;
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        .category-card:hover .category-img {
            transform: scale(1.15);
        }

        .cart-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .cart-header h1 {
            font-size: 1.9rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
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

        .product-card {
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.08);
            box-shadow: 0 15px 30px rgba(92, 127, 81, 0.15);
        }

        .product-card .card-footer {
            background: #fff;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(92, 127, 81, 0.15);
        }
    </style>

    <div class="container py-4">

        <!-- SEARCH BAR -->
        <form method="GET" action="{{ route('products.browse') }}" class="search-container mb-4">
            <input type="text" name="search" class="form-control search-input"
                placeholder="Search for plants, tools, seeds..." value="{{ request('search') }}">
            <button type="submit" class="search-btn">
                <i class="bi bi-search me-2"></i> Search
            </button>
        </form>

        <!-- CATEGORY FILTER -->
        <div class="d-flex flex-wrap gap-3 justify-content-center mb-5">

            @foreach($categories as $cat)

                @php
                    $image = match (strtolower($cat->category_name)) {
                        'indoor plants' => 'indoor-plant.png',
                        'outdoor plants' => 'outdoor-plant.png',
                        'herbs' => 'herbs.png',
                        'flowering' => 'flowering.png',
                        'seeds' => 'seeds3.png',
                        'tools' => 'tools.png',
                        default => 'default.png',
                    };
                @endphp

                <a href="{{ route('products.browse', array_merge(request()->all(), ['category' => $cat->id])) }}"
                    class="text-decoration-none text-dark">

                    <div class="category-card text-center p-3 shadow-sm rounded-4">
                        <img src="{{ asset('images/' . $image) }}" alt="{{ $cat->category_name }}" class="category-img">

                        <p class="mt-2 mb-0 small fw-semibold">
                            {{ $cat->category_name }}
                        </p>
                    </div>

                </a>
            @endforeach

            <!-- ALL -->
            <a href="{{ route('products.browse', request()->except('category')) }}" class="text-decoration-none text-dark">

                <div class="category-card text-center p-3 shadow-sm rounded-4">
                    <img src="{{ asset('images/all-products.png') }}" alt="All Categories" class="category-img">

                    <p class="mt-2 mb-0 small fw-semibold">All</p>
                </div>
            </a>

        </div>

        <!-- BEST SELLERS -->
        <div class="container mt-5">
        <div class="text-center mb-5">
        <h2 class="section-title best-sellers">
            Best Sellers
        </h2>
        <div class="section-subtitle">
            <i class="bi bi-star-fill text-warning me-2"></i>
            Most loved by our customers
            <i class="bi bi-star-fill text-warning ms-2"></i>
        </div>
    </div>

            <div class="row g-4">
                @foreach ($bestSellers as $p)
                    <div class="col-6 col-md-3">
                        <div class="card shadow-sm product-card border-0 rounded-4 h-100">

                            <img src="{{ $p->images->first() ? asset('images/' . $p->images->first()->image_path) : asset('images/default.jpg') }}"
                                class="card-img-top rounded-top-4" style="height:280px; object-fit:cover;">

                            <div class="card-body">
                                <h6 class="fw-bold">{{ $p->product_name }}</h6>
                                <div class="text-muted small"><i
                                        class="bi bi-shop me-2"></i>{{ $p->seller->business_name ?? 'Unknown Seller' }}</div>
                                <div class="fw-bold text-success mt-2">RM {{ number_format($p->price, 2) }}</div>
                            </div>

                            <div class="card-footer bg-white rounder-bottom-4">
                                <a href="{{ route('products.show', $p->id) }}"
                                    class="btn btn-outline-success w-100 rounded-pill">
                                    View Details<i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>


        <!-- Latest Products -->
        <div class="container mt-5">
        <div class="text-center mb-5">
        <h2 class="section-title latest-products">
            Latest Products
        </h2>
        <div class="section-subtitle">
            Fresh arrivals just for you
        </div>
    </div>

            <div class="row g-4">
                @foreach ($latestProducts as $p)
                    <div class="col-6 col-md-3">
                        <div class="card shadow-sm product-card border-0 rounded-4 h-100">

                            <img src="{{ $p->images->first() ? asset('images/' . $p->images->first()->image_path) : asset('images/default.jpg') }}"
                                class="card-img-top rounded-top-4" style="height:280px; object-fit:cover;">

                            <div class="card-body">
                                <h6 class="fw-bold">{{ $p->product_name }}</h6>
                                <div class="text-muted small"><i
                                        class="bi bi-shop me-2"></i>{{ $p->seller->business_name ?? 'Unknown Seller' }}</div>
                                <div class="fw-bold text-success mt-2">RM {{ number_format($p->price, 2) }}</div>
                            </div>

                            <div class="card-footer bg-white rounder-bottom-4">
                                <a href="{{ route('products.show', $p->id) }}"
                                    class="btn btn-outline-success w-100 rounded-pill">
                                    View Details<i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Top Sellers -->
        <div class="container mt-5">
        <div class="text-center mb-5">
        <h2 class="section-title top-sellers">
            Top Sellers
        </h2>
        <div class="section-subtitle">
            <i class="bi bi-award-fill text-warning me-2"></i>
            Most trusted plant sellers
            <i class="bi bi-award-fill text-warning ms-2"></i>
        </div>
    </div>

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

                                {{-- Visit Shop Button --}}
                                <a href="{{ route('seller-shop', $seller->id) }}"
                                    class="btn btn-sm btn-outline-primary rounded-pill px-4 mt-2">
                                    Visit Shop
                                </a>
                            </div>
                        </div>
                @endforeach
            </div>
        </div>


        <div class="container my-5">
            <div class="row">

                <!-- FEATURES & ABOUT -->
                <div class="container py-5">
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="p-4 shadow-sm product-card feature-section rounded-4 mb-4 d-flex align-items-start">
                                <div class="feature-icon me-4">
                                    <i class="bi bi-leaf"></i>
                                </div>
                                <div>
                                    <h4 class="fw-semibold mb-2">Discover Green Diversity</h4>
                                    <p class="text-muted mb-0">
                                        Explore unique foliage, vibrant succulents, and over 100 species selected to suit
                                        every
                                        lifestyle and space.
                                    </p>
                                </div>
                            </div>

                            <div class="p-4 shadow-sm product-card feature-section rounded-4 mb-4 d-flex align-items-start">
                                <div class="feature-icon me-4">
                                    <i class="bi bi-box-seam"></i>
                                </div>
                                <div>
                                    <h4 class="fw-semibold mb-2">Grown with Love</h4>
                                    <p class="text-muted mb-0">
                                        Each plant is carefully nurtured and inspected to ensure it arrives healthy and
                                        beautiful at
                                        your doorstep.
                                    </p>
                                </div>
                            </div>

                            <div class="p-4 shadow-sm product-card feature-section rounded-4 d-flex align-items-start">
                                <div class="feature-icon me-4">
                                    <i class="bi bi-truck"></i>
                                </div>
                                <div>
                                    <h4 class="fw-semibold mb-2">Fast Delivery</h4>
                                    <p class="text-muted mb-0">
                                        Order by 4pm for same-day delivery across KL/Selangor—fresh, fast, and handled with
                                        care.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="p-5 about-section rounded-4 h-100">
                                <h2 class="fw-bold mb-4">About Aether & Leaf Co.</h2>
                                <p class="text-muted mb-4">
                                    Aether & Leaf Co. is where nature meets minimalism. We curate a collection of indoor
                                    plants,
                                    premium pots, and gardening essentials designed to bring calm, beauty, and freshness
                                    into every
                                    space.
                                </p>
                                <p class="text-muted mb-0">
                                    Whether you're a beginner or a plant lover, we make it easy to grow greenery with
                                    confidence,
                                    offering expert advice and quality products for every plant journey.
                                </p>
                            </div>
                        </div>
                    </div>
@endsection