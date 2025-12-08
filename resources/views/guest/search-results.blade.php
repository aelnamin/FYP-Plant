@extends('layouts.main')

@section('content')
<div class="container mt-5">
    <h3>Search results for: <strong>{{ $keyword }}</strong></h3>
    <div class="row g-4 mt-4">

        @forelse ($products as $p)
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <img src="{{ $p->images->first() ? asset('images/' . $p->images->first()->image_path) : asset('images/default.jpg') }}"
                    class="card-img-top rounded-top-4"
                    style="height:280px; object-fit:cover;">

                <div class="card-body">
                    <h6 class="fw-bold">{{ $p->product_name }}</h6>
                    <div class="text-muted small">{{ $p->seller->business_name }}</div>
                    <div class="fw-bold text-success mt-2">RM {{ number_format($p->price, 2) }}</div>
                </div>

                <div class="card-footer bg-white">
                    <a href="{{ route('products.show', $p->id) }}" class="btn btn-outline-success w-100 rounded-pill">
                        View Details
                    </a>
                </div>
            </div>
        </div>
        @empty
        <p>No products found.</p>
        @endforelse

    </div>
</div>
@endsection