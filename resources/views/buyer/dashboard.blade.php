@extends('layouts.main')

@section('title', 'Buyer Dashboard')

@section('content')


    <!-- SEARCH BAR -->
    <div class="container my-5">
        <form method="GET" action="{{ route('home.search') }}" class="d-flex justify-content-center">
            <input type="text" name="search" class="form-control w-100" placeholder="Search products...">
            <button class="btn btn-success ms-2">Search</button>
        </form>
    </div>

    <!-- BEST SELLERS -->
    <div class="container mt-5">
        <h2 class="fw-bold mb-3">Best Sellers</h2>

        <div class="row g-4">
            @foreach ($bestSellers as $p)
                <div class="col-6 col-md-3">
                    <div class="card shadow-sm border-0 rounded-4 h-100">

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


    <!-- Latest Products -->
    <div class="container mt-5">
        <h2 class="fw-bold mb-3">Latest Products</h2>

        <div class="row g-4">
            @foreach ($latestProducts as $p)
                <div class="col-6 col-md-3">
                    <div class="card shadow-sm border-0 rounded-4 h-100">

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
                    <div class="text-center p-4 bg-white shadow-sm rounded-4">

                        <div class="rounded-circle bg-success mx-auto mb-3" style="width:80px; height:80px;"></div>

                        <h6 class="fw-bold">{{ $seller->business_name }}</h6>
                        <p class="text-muted small">Trusted Seller</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>


    <div class="container my-5">
        <div class="row">

            <!-- Left Column: Features -->
            <div class="col-md-6">
                <!-- FEATURE 1 BOX -->
                <div class="p-4 shadow-sm rounded-4 mb-4 d-flex align-items-start"
                    style="background:#f9f9f9; text-align:left;">
                    <img src="{{ asset('images/leaf.png') }}" alt="Leaf Icon" style="width:50px; height:50px;" class="me-3">
                    <div>
                        <h4 style="font-weight: 400;">Discover a world of green at your fingertips</h4>
                        <p class="text-muted">
                            Discover unique foliage, vibrant succulents, and over 100 species
                            selected to suit every lifestyle and corner of your home.
                        </p>
                    </div>
                </div>

                <!-- FEATURE 2 BOX -->
                <div class="p-4 shadow-sm rounded-4 mb-4 d-flex align-items-start"
                    style="background:#f9f9f9; text-align:left;">
                    <img src="{{ asset('images/box.png') }}" alt="Box Icon" style="width:50px; height:50px;" class="me-3">
                    <div>
                        <h4 style="font-weight: 400;">Grown with love</h4>
                        <p class="text-muted">
                            Each plant is grown with care and checked by our team to ensure it arrives
                            healthy and beautiful. We’re committed to making plant care simple.
                        </p>
                    </div>
                </div>

                <!-- FEATURE 3 BOX -->
                <div class="p-4 shadow-sm rounded-4 d-flex align-items-start" style="background:#f9f9f9; text-align:left;">
                    <img src="{{ asset('images/delivery.png') }}" alt="Delivery Icon" style="width:50px; height:50px;"
                        class="me-3">
                    <div>
                        <h4 style="font-weight: 400;">Same-day delivery across KL/Selangor</h4>
                        <p class="text-muted">
                            In a hurry? Order by 4pm and enjoy same-day delivery—fresh, fast, and handled with care.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right Column: About Section -->
            <div class="col-md-6">
                <div class="p-5 rounded-4 shadow-sm" style="background:#f6ecef;">
                    <h2 class="fw-bold">About Aether & Leaf Co.</h2>
                    <p class="text-muted mt-3">
                        Aether & Leaf Co. is where nature meets minimalism. We curate a collection of indoor plants,
                        premium pots, and gardening essentials designed to bring calm, beauty, and freshness into every
                        space.
                        Whether you’re a beginner or a plant lover, we make it easy to grow greenery with confidence.
                    </p>
                </div>
            </div>

        </div>
    </div>

@endsection