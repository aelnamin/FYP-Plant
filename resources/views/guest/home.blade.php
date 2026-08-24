@extends('layouts.main')
@section('skip-font-awesome', true)

@push('head')
    <link rel="preload" as="image" type="image/webp"
        href="{{ asset('images/hero-plant-1280.webp') }}"
        imagesrcset="{{ asset('images/hero-plant-768.webp') }} 768w, {{ asset('images/hero-plant-1280.webp') }} 1280w, {{ asset('images/hero-plant-1537.webp') }} 1537w, {{ asset('images/hero-plant-1920.webp') }} 1920w"
        imagesizes="100vw" fetchpriority="high">
@endpush

@section('full-width-content')

    {{-- SHOW BANNER ONLY FOR GUESTS / NON-BUYERS --}}
    @if(!Auth::check() || Auth::user()->role !== 'buyer')

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

            @media (max-width: 575.98px) {
                .section-title {
                    font-size: 1.65rem;
                }

                .search-container {
                    display: flex;
                    flex-direction: column;
                    gap: 0.5rem;
                }

                .search-btn {
                    position: static;
                    width: 100%;
                    min-height: 44px;
                }

                .category-card {
                    width: 100px;
                }
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

            .homepage-deferred-section {
                content-visibility: auto;
                contain-intrinsic-size: auto 780px;
            }

            /* Homepage hero */
            .home-hero {
                position: relative;
                min-height: clamp(470px, 67vh, 650px);
                width: 100%;
                margin: 0;
                overflow: hidden;
                border-radius: 0;
                background: #253723;
                box-shadow: none;
                isolation: isolate;
            }

            .home-hero::after {
                content: '';
                position: absolute;
                inset: 0;
                z-index: 1;
                pointer-events: none;
                background:
                    linear-gradient(90deg, rgba(25, 43, 23, 0.91) 0%, rgba(34, 54, 30, 0.7) 44%, rgba(24, 36, 22, 0.2) 76%),
                    linear-gradient(0deg, rgba(13, 24, 12, 0.32), transparent 48%);
            }

            .home-hero__carousel,
            .home-hero__carousel .carousel-inner,
            .home-hero__carousel .carousel-item {
                position: absolute;
                inset: 0;
                height: 100%;
            }

            .home-hero__image {
                width: 100%;
                height: 100%;
                object-fit: cover;
                object-position: center;
                transform: scale(1.015);
            }

            .home-hero__content {
                position: relative;
                z-index: 2;
                display: flex;
                align-items: center;
                width: 100%;
                max-width: 1440px;
                min-height: clamp(470px, 67vh, 650px);
                margin-inline: auto;
                padding-inline: clamp(1.25rem, 6vw, 6rem);
                padding-block: clamp(3rem, 8vw, 6rem);
            }

            .home-hero__copy {
                max-width: 690px;
                color: #fff;
            }

            .home-hero__eyebrow {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                margin-bottom: 1.25rem;
                padding: 0.55rem 0.9rem;
                border: 1px solid rgba(255, 255, 255, 0.24);
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.1);
                color: #f2f6ed;
                font-size: 0.78rem;
                font-weight: 700;
                letter-spacing: 0.09em;
                text-transform: uppercase;
                backdrop-filter: blur(10px);
            }

            .home-hero__title {
                max-width: 650px;
                margin-bottom: 1rem;
                font-size: clamp(2.45rem, 5.7vw, 5.25rem);
                font-weight: 800;
                line-height: 0.98;
                letter-spacing: -0.045em;
                text-wrap: balance;
            }

            .home-hero__title-accent {
                color: #c9dfa9;
            }

            .home-hero__subtitle {
                max-width: 570px;
                margin-bottom: 1.75rem;
                color: rgba(255, 255, 255, 0.82);
                font-size: clamp(1rem, 1.7vw, 1.2rem);
                line-height: 1.7;
            }

            .home-hero .search-container {
                display: flex;
                max-width: 650px;
                margin: 0;
                padding: 0.4rem;
                border: 1px solid rgba(255, 255, 255, 0.38);
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.96);
                box-shadow: 0 16px 34px rgba(15, 28, 13, 0.24);
            }

            .home-hero .search-input {
                min-width: 0;
                padding: 0.8rem 1.1rem;
                border: 0;
                background: transparent;
                box-shadow: none;
            }

            .home-hero .search-input:focus {
                border: 0;
                box-shadow: none;
            }

            .home-hero .search-btn {
                position: static;
                flex: 0 0 auto;
                min-height: 48px;
                padding-inline: 1.5rem;
                border-radius: 999px;
                background: #5c7f51;
                box-shadow: 0 8px 18px rgba(62, 94, 52, 0.25);
            }

            .home-hero .search-btn:hover,
            .home-hero .search-btn:focus {
                color: #fff;
                background: #496a40;
                transform: translateY(-1px);
            }

            .home-hero__assurance {
                display: flex;
                flex-wrap: wrap;
                gap: 0.75rem 1.5rem;
                margin-top: 1.25rem;
                color: rgba(255, 255, 255, 0.78);
                font-size: 0.88rem;
            }

            .home-hero__assurance span {
                display: inline-flex;
                align-items: center;
                gap: 0.45rem;
            }

            .home-hero__assurance i {
                color: #c9dfa9;
            }

            .home-hero .carousel-indicators {
                z-index: 3;
                justify-content: flex-end;
                margin-right: clamp(1rem, 4vw, 3rem);
                margin-bottom: 1.5rem;
            }

            .home-hero .carousel-indicators [data-bs-target] {
                width: 2rem;
                height: 0.22rem;
                border: 0;
                border-radius: 999px;
            }

            @media (max-width: 767.98px) {
                .home-hero {
                    min-height: 560px;
                }

                .home-hero::after {
                    background: linear-gradient(0deg, rgba(21, 37, 19, 0.94) 0%, rgba(28, 47, 25, 0.72) 67%, rgba(22, 35, 20, 0.38) 100%);
                }

                .home-hero__content {
                    align-items: flex-end;
                    min-height: 560px;
                    padding: 3.5rem 0 4.25rem;
                }

                .home-hero__copy {
                    max-width: 100%;
                    text-align: center;
                }

                .home-hero__subtitle {
                    margin-inline: auto;
                }

                .home-hero .search-container {
                    max-width: 560px;
                    margin-inline: auto;
                }

                .home-hero__assurance {
                    justify-content: center;
                }

                .home-hero .carousel-indicators {
                    justify-content: center;
                    margin-right: 15%;
                    margin-bottom: 1rem;
                }
            }

            @media (max-width: 575.98px) {
                .home-hero {
                    min-height: 590px;
                }

                .home-hero__content {
                    min-height: 590px;
                    padding-inline: 1rem;
                }

                .home-hero .search-container {
                    display: grid;
                    gap: 0.45rem;
                    padding: 0.5rem;
                    border-radius: 1rem;
                }

                .home-hero .search-input,
                .home-hero .search-btn {
                    width: 100%;
                }

                .home-hero .search-input {
                    text-align: center;
                }

                .home-hero .search-btn {
                    border-radius: 0.75rem;
                }

                .home-hero__assurance {
                    gap: 0.5rem 1rem;
                    font-size: 0.8rem;
                }
            }
        </style>

        <!-- HOMEPAGE HERO -->
        <section class="home-hero" aria-labelledby="home-hero-title">
            <div id="plantBanner" class="carousel slide home-hero__carousel" data-bs-ride="carousel" data-bs-interval="5000">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#plantBanner" data-bs-slide-to="0" class="active"
                        aria-current="true" aria-label="Show indoor plants"></button>
                    <button type="button" data-bs-target="#plantBanner" data-bs-slide-to="1"
                        aria-label="Show garden plants"></button>
                    <button type="button" data-bs-target="#plantBanner" data-bs-slide-to="2"
                        aria-label="Show gardening essentials"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="{{ asset('images/hero-plant-1280.webp') }}"
                            srcset="{{ asset('images/hero-plant-768.webp') }} 768w, {{ asset('images/hero-plant-1280.webp') }} 1280w, {{ asset('images/hero-plant-1537.webp') }} 1537w, {{ asset('images/hero-plant-1920.webp') }} 1920w"
                            sizes="100vw" width="1537" height="1023" class="home-hero__image"
                            alt="A curated collection of healthy indoor plants" fetchpriority="high" decoding="async">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('images/hero-home-1280.webp') }}"
                            srcset="{{ asset('images/hero-home-768.webp') }} 768w, {{ asset('images/hero-home-1280.webp') }} 1280w, {{ asset('images/hero-home-1537.webp') }} 1537w, {{ asset('images/hero-home-1920.webp') }} 1920w"
                            sizes="100vw" width="1537" height="1023" class="home-hero__image"
                            alt="Green plants styled for a welcoming home" loading="lazy" fetchpriority="low" decoding="async">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('images/hero-essentials-1280.webp') }}"
                            srcset="{{ asset('images/hero-essentials-768.webp') }} 768w, {{ asset('images/hero-essentials-1280.webp') }} 1280w, {{ asset('images/hero-essentials-1537.webp') }} 1537w, {{ asset('images/hero-essentials-1920.webp') }} 1920w"
                            sizes="100vw" width="1537" height="1023" class="home-hero__image"
                            alt="Plant and gardening essentials from trusted sellers" loading="lazy" fetchpriority="low" decoding="async">
                    </div>
                </div>
            </div>

            <div class="home-hero__content">
                <div class="home-hero__copy">
                    <div class="home-hero__eyebrow">
                        <i class="bi bi-leaf-fill" aria-hidden="true"></i>
                        Curated for greener living
                    </div>
                    <h1 id="home-hero-title" class="home-hero__title">
                        Welcome to <span class="home-hero__title-accent">Aether & Leaf Co.</span>
                    </h1>
                    <p class="home-hero__subtitle">Your trusted place for plants & gardening essentials</p>

                    <form method="GET" action="{{ route('products.browse') }}" class="search-container" role="search">
                        <label for="hero-search" class="visually-hidden">Search products</label>
                        <input id="hero-search" type="search" name="search" class="form-control search-input"
                            placeholder="Search plants, tools, seeds..." value="{{ request('search') }}">
                        <button type="submit" class="search-btn">
                            <i class="bi bi-search me-2" aria-hidden="true"></i>Search
                        </button>
                    </form>

                    <div class="home-hero__assurance" aria-label="Shopping benefits">
                        <span><i class="bi bi-patch-check-fill" aria-hidden="true"></i>Trusted local sellers</span>
                        <span><i class="bi bi-box-seam-fill" aria-hidden="true"></i>Carefully packed</span>
                        <span><i class="bi bi-heart-fill" aria-hidden="true"></i>Selected with care</span>
                    </div>
                </div>
            </div>
        </section>

    @endif
@endsection

@section('content')
<br>
    <div class="container py-4">

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
                    <img src="{{ asset('images/' . $image) }}" alt="{{ $cat->category_name }}" class="category-img"
                        width="48" height="48" loading="lazy" decoding="async">

                    <p class="mt-2 mb-0 small fw-semibold">
                        {{ $cat->category_name }}
                    </p>
                </div>

            </a>
        @endforeach

        <!-- ALL -->
        <a href="{{ route('products.browse', request()->except('category')) }}" class="text-decoration-none text-dark">

            <div class="category-card text-center p-3 shadow-sm rounded-4">
                <img src="{{ asset('images/all-products.png') }}" alt="All Categories" class="category-img"
                    width="48" height="48" loading="lazy" decoding="async">

                <p class="mt-2 mb-0 small fw-semibold">All</p>
            </div>
        </a>

    </div>

    <!-- BEST SELLERS -->
    <div class="container mt-5 homepage-deferred-section">
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

                        <img src="{{ $p->image_path ? asset('images/' . $p->image_path) : asset('images/default.jpg') }}"
                            class="card-img-top rounded-top-4" width="600" height="560" loading="lazy" decoding="async"
                            alt="{{ $p->product_name }}" style="height:280px; object-fit:cover;">

                        <div class="card-body">
                            <h3 class="h6 fw-bold">{{ $p->product_name }}</h3>
                            <div class="text-muted small"><i
                                    class="bi bi-shop me-2"></i>{{ $p->seller_business_name ?? 'Unknown Seller' }}</div>
                            <div class="fw-bold text-success mt-2">RM {{ number_format($p->price, 2) }}</div>
                        </div>

                        <div class="card-footer bg-white rounder-bottom-4">
                            <a href="{{ route('products.show', $p->id) }}" class="btn btn-outline-success w-100 rounded-pill">
                                View Details<i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>


    <!-- Latest Products -->
    <div class="container mt-5 homepage-deferred-section">
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

                        <img src="{{ $p->image_path ? asset('images/' . $p->image_path) : asset('images/default.jpg') }}"
                            class="card-img-top rounded-top-4" width="600" height="560" loading="lazy" decoding="async"
                            alt="{{ $p->product_name }}" style="height:280px; object-fit:cover;">

                        <div class="card-body">
                            <h3 class="h6 fw-bold">{{ $p->product_name }}</h3>
                            <div class="text-muted small"><i
                                    class="bi bi-shop me-2"></i>{{ $p->seller_business_name ?? 'Unknown Seller' }}</div>
                            <div class="fw-bold text-success mt-2">RM {{ number_format($p->price, 2) }}</div>
                        </div>

                        <div class="card-footer bg-white rounder-bottom-4">
                            <a href="{{ route('products.show', $p->id) }}" class="btn btn-outline-success w-100 rounded-pill">
                                View Details<i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Top Sellers -->
    <div class="container mt-5 homepage-deferred-section">
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
                        <img src="{{ $seller->profile_picture_path
                ? asset($seller->profile_picture_path)
                : asset('images/default.png') }}" class="rounded-circle mx-auto mb-3"
                            width="90" height="90" loading="lazy" decoding="async"
                            style="width:90px; height:90px; object-fit:cover;" alt="{{ $seller->business_name }}">


                        {{-- Seller Name --}}
                        <h3 class="h6 fw-bold">{{ $seller->business_name }}</h3>
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


    <div class="container my-5 homepage-deferred-section">
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
                                <h3 class="h4 fw-semibold mb-2">Discover Green Diversity</h3>
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
                                <h3 class="h4 fw-semibold mb-2">Grown with Love</h3>
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
                                <h3 class="h4 fw-semibold mb-2">Fast Delivery</h3>
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
