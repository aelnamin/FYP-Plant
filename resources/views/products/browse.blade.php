@extends('layouts.main')

@section('content')

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .product-card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
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


        <!-- Header -->
        <div class="cart-header text-dark">
            <h1>All Products</h1>
        </div>

        <!-- PRODUCTS -->
        @if ($products->count() > 0)
            <div class="row g-4">

                @foreach ($products as $p)
                    <div class="col-6 col-md-3">
                        <div class="card shadow-sm border-0 h-100 rounded-4 overflow-hidden product-card">

                            <img src="{{ $p->images->first()
                        ? asset('images/' . $p->images->first()->image_path)
                        : asset('images/default.jpg') }}" class="card-img-top" style="height:280px; object-fit:cover;">

                            <div class="card-body">
                                <h6 class="fw-bold">
                                    {{ Str::limit($p->product_name ?? $p->name, 40) }}
                                </h6>
                                <div class="small text-muted">
                                    <i class="bi bi-shop me-1"></i>{{ $p->seller->business_name ?? 'Unknown Seller' }}
                                </div>
                                <div class="fw-bold text-success mt-2">
                                    RM {{ number_format($p->price, 2) }}
                                </div>
                            </div>

                            <div class="card-footer bg-white">
                                <a href="{{ route('products.show', $p->id) }}" class="btn btn-outline-success w-100">
                                    View Details
                                </a>
                            </div>

                        </div>
                    </div>
                @endforeach

            </div>

            <div class="mt-4">
                {{ $products->links() }}
            </div>
        @else
            <p>No products found.</p>
        @endif

    </div>
@endsection