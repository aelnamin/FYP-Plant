@extends('layouts.sellers-main')

@section('title', 'Product Details')

@section('content')
    <div class="container mt-4">

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold text-success">Product Details</h5>
            <a href="{{ route('sellers.inventory.index') }}" class="btn btn-sm btn-outline-secondary">
                Back to Inventory
            </a>
        </div>

        <div class="card shadow-sm rounded-4 p-4">

            <div class="row g-4">

                <!-- LEFT: MAIN IMAGE + THUMBNAILS -->
                <div class="col-md-5">

                    {{-- MAIN IMAGE --}}
                    <div class="text-center mb-3">
                        <img id="mainImage" src="{{ $product->images->first()
        ? asset('images/' . $product->images->first()->image_path)
        : asset('images/default.jpg') }}" class="img-fluid rounded" style="max-height: 350px; object-fit: cover;"
                            alt="{{ $product->product_name }}">
                    </div>

                    {{-- THUMBNAILS (LEFT-ALIGNED) --}}
                    <div class="d-flex justify-content-start gap-2 flex-wrap">
                        @foreach($product->images as $img)
                            <img src="{{ asset('images/' . $img->image_path) }}" width="70" height="70" class="rounded border"
                                style="object-fit: cover; cursor: pointer;"
                                onclick="document.getElementById('mainImage').src=this.src">
                        @endforeach
                    </div>
                </div>

                <!-- RIGHT: PRODUCT INFO -->
                <div class="col-md-7">

                    <h4 class="fw-bold">{{ $product->product_name }}</h4>

                    <p class="text-muted mb-1">
                        Category: {{ $product->category->category_name ?? 'N/A' }}
                    </p>

                    <p class="fs-5 fw-semibold text-success">
                        RM {{ number_format($product->price, 2) }}
                    </p>

                    <p>
                        <strong>Stock:</strong>
                        {{ $product->stock_quantity }}
                    </p>

                    <hr>

                    <p>
                        <strong>Description</strong><br>
                        {!! nl2br(e($product->description)) !!}
                    </p>

                    <hr>

                    <!-- ACTIONS -->
                    <div class="mt-3">
                        <a href="{{ route('sellers.inventory.edit', $product->id) }}" class="btn btn-sm btn-warning">
                            Edit Product
                        </a>

                        <a href="{{ route('sellers.inventory.index') }}" class="btn btn-sm btn-outline-secondary">
                            Back
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection