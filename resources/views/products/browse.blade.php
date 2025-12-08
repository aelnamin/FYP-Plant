@extends('layouts.main')

@section('content')

<style>
    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .product-card:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }
</style>


<div class="container py-4">

    <!-- Search Bar -->
    <form method="GET" action="{{ route('products.browse') }}" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search products...">
            <button class="btn btn-success">Search</button>
        </div>
    </form>

    <h2 class="mb-4 fw-bold">Browse Products</h2>

    @if ($products->count() > 0)
    <div class="row g-4">

        @foreach ($products as $p)
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 h-100 rounded-4 overflow-hidden product-card">

                <!-- Product Image -->
                <img
                    src="{{ $p->images->first() ? asset('images/' . $p->images->first()->image_path) : asset('images/default.jpg') }}"
                    class="card-img-top"
                    style="height:280px; object-fit:cover;">

                <!-- Product Details -->
                <div class="card-body">
                    <h6 class="fw-bold">{{ Str::limit($p->product_name ?? $p->name, 40) }}</h6>
                    <div class="small text-muted">{{ $p->seller->business_name ?? 'Unknown Seller' }}</div>
                    <div class="fw-bold text-success mt-2">RM {{ number_format($p->price, 2) }}</div>
                </div>

                <!-- Button -->
                <div class="card-footer bg-white">
                    <a href="{{ route('products.show', $p->id) }}" class="btn btn-outline-success w-100">
                        View Details
                    </a>
                </div>

            </div>
        </div>
        @endforeach

    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $products->links() }}
    </div>

    @else
    <p>No products found.</p>
    @endif

</div>
@endsection