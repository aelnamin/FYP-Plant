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

                    {{-- PLANT CARE DETAILS --}}
                    @if (in_array($product->category_id, [2, 4, 5]))
                        {{-- Sunlight Requirement --}}
                        <div class="mb-3">
                            <label class="form-label">Sunlight Requirement</label>
                            <select class="form-control" disabled>
                                <option value="">Select</option>
                                <option value="Full Sun to Partial Shade" {{ $product->sunlight_requirement == 'Full Sun to Partial Shade' ? 'selected' : '' }}>Full Sun to Partial Shade</option>
                                <option value="Not Applicable (Accessory)" {{ $product->sunlight_requirement == 'Not Applicable (Accessory)' ? 'selected' : '' }}>Not Applicable (Accessory)</option>
                                <option value="Not Applicable (Cut Flowers)" {{ $product->sunlight_requirement == 'Not Applicable (Cut Flowers)' ? 'selected' : '' }}>Not Applicable (Cut Flowers)</option>
                                <option value="Full Sun" {{ $product->sunlight_requirement == 'Full Sun' ? 'selected' : '' }}>Full
                                    Sun</option>
                                <option value="Bright Indirect" {{ $product->sunlight_requirement == 'Bright Indirect' ? 'selected' : '' }}>Bright Indirect</option>
                            </select>
                        </div>

                        {{-- Watering Frequency --}}
                        <div class="mb-3">
                            <label class="form-label">Watering Frequency</label>
                            <select class="form-control" disabled>
                                <option value="">Select</option>
                                <option value="Daily" {{ $product->watering_frequency == 'Daily' ? 'selected' : '' }}>Daily
                                </option>
                                <option value="Consistent" {{ $product->watering_frequency == 'Consistent' ? 'selected' : '' }}>
                                    Consistent</option>
                                <option value="Weekly" {{ $product->watering_frequency == 'Weekly' ? 'selected' : '' }}>Weekly
                                </option>
                                <option value="Not Applicable (Accessory)" {{ $product->watering_frequency == 'Not Applicable (Accessory)' ? 'selected' : '' }}>Not Applicable (Accessory)</option>
                                <option value="Infrequent (Dormancy)" {{ $product->watering_frequency == 'Infrequent (Dormancy)' ? 'selected' : '' }}>Infrequent (Dormancy)</option>
                                <option value="Infrequent (Allow Soil to Dry)" {{ $product->watering_frequency == 'Infrequent (Allow Soil to Dry)' ? 'selected' : '' }}>Infrequent (Allow Soil to Dry)</option>
                                <option value="Moderate" {{ $product->watering_frequency == 'Moderate' ? 'selected' : '' }}>
                                    Moderate</option>
                            </select>
                        </div>

                        {{-- Difficulty Level --}}
                        <div class="mb-3">
                            <label class="form-label">Difficulty Level</label>
                            <select class="form-control" disabled>
                                <option value="">Select</option>
                                <option value="Easy" {{ $product->difficulty_level == 'Easy' ? 'selected' : '' }}>Easy</option>
                                <option value="Medium" {{ $product->difficulty_level == 'Medium' ? 'selected' : '' }}>Medium
                                </option>
                                <option value="Hard" {{ $product->difficulty_level == 'Hard' ? 'selected' : '' }}>Hard</option>
                            </select>
                        </div>

                        {{-- Growth Stage --}}
                        <div class="mb-3">
                            <label class="form-label">Growth Stage</label>
                            <select class="form-control" disabled>
                                <option value="">Select</option>
                                <option value="Cut Flowers" {{ $product->growth_stage == 'Cut Flowers' ? 'selected' : '' }}>Cut
                                    Flowers</option>
                                <option value="Bulb" {{ $product->growth_stage == 'Bulb' ? 'selected' : '' }}>Bulb</option>
                                <option value="Mature" {{ $product->growth_stage == 'Mature' ? 'selected' : '' }}>Mature</option>
                                <option value="Young" {{ $product->growth_stage == 'Young' ? 'selected' : '' }}>Young</option>
                                <option value="Tuber" {{ $product->growth_stage == 'Tuber' ? 'selected' : '' }}>Tuber</option>
                            </select>
                        </div>
                    @endif
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