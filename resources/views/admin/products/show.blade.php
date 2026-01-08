@extends('layouts.admin-main')

@section('title', 'Product Details')

@section('content')
    <div class="container py-5">
        <!-- Header with Back Button -->
        <div class="d-flex justify-content-end mb-4">
            <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to List
            </a>
        </div>

        <!-- Centered Content -->
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5">

                        <!-- Product Header -->
                        <div class="text-center mb-4">
                            <h1 class="h3 fw-bold text-gray-900 mb-2">Product Details</h1>
                            <p class="text-gray-600">Complete product information and images</p>
                        </div>

                        <div class="row g-4">
                            <!-- Product Images -->
                            <div class="col-md-5">
                                <div class="sticky-top" style="top: 20px;">
                                    <!-- Main Image -->
                                    <div class="text-center mb-4">
                                        <img src="{{ $product->images->first()
        ? asset('images/' . $product->images->first()->image_path)
        : asset('images/default.jpg') }}" class="img-fluid rounded-3 shadow-sm"
                                            alt="{{ $product->product_name }}"
                                            style="max-height: 350px; object-fit: contain;">
                                    </div>

                                    <!-- Thumbnail Gallery -->
                                    @if($product->images->count() > 1)
                                        <div class="mb-4">
                                            <h6 class="fw-semibold text-gray-700 mb-3">Additional Images</h6>
                                            <div class="row g-2">
                                                @foreach($product->images as $image)
                                                    <div class="col-3">
                                                        <img src="{{ asset('images/' . $image->image_path) }}"
                                                            class="img-thumbnail rounded-2"
                                                            style="width: 80px; height: 80px; object-fit: cover; cursor: pointer;"
                                                            onclick="changeMainImage(this.src)"
                                                            alt="Product image {{ $loop->iteration }}">
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Quick Stats -->
                                    <div class="card border mt-4">
                                        <div class="card-body p-3">
                                            <div class="row g-2 text-center">
                                                <div class="col-6">
                                                    <div class="text-gray-600 small">Price</div>
                                                    <div class="fw-bold text-success">RM
                                                        {{ number_format($product->price, 2) }}</div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="text-gray-600 small">Stock</div>
                                                    <div class="fw-bold">{{ $product->stock_quantity }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Product Details -->
                            <div class="col-md-7">
                                <div class="mb-4">
                                    <h2 class="fw-bold text-gray-900 mb-2">{{ $product->product_name }}</h2>

                                    <!-- Status Badge -->
                                    @if($product->approval_status == 'Pending')
                                        <span class="badge bg-warning py-2 px-3 mb-3">Pending Approval</span>
                                    @elseif($product->approval_status == 'Approved')
                                        <span class="badge bg-success py-2 px-3 mb-3">Approved</span>
                                    @elseif($product->approval_status == 'Rejected')
                                        <span class="badge bg-danger py-2 px-3 mb-3">Rejected</span>
                                    @endif
                                </div>

                                <!-- Basic Info -->
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <div class="card border h-100">
                                            <div class="card-body p-3">
                                                <div class="text-gray-600 small">Category</div>
                                                <div class="fw-medium">{{ $product->category->category_name ?? 'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card border h-100">
                                            <div class="card-body p-3">
                                                <div class="text-gray-600 small">Seller</div>
                                                <div class="fw-medium">{{ $product->seller->business_name ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="mb-4">
                                    <h6 class="fw-semibold text-gray-700 mb-2">Description</h6>
                                    <div class="card border">
                                        <div class="card-body p-3">
                                            <div class="text-gray-700" style="line-height: 1.6;">
                                                {!! nl2br(e($product->description)) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Health Condition -->
                                @if($product->health_condition)
                                    <div class="mb-4">
                                        <h6 class="fw-semibold text-gray-700 mb-2">Health & Condition</h6>
                                        <div class="card border">
                                            <div class="card-body p-3">
                                                <div class="text-gray-700" style="line-height: 1.6;">
                                                    {{ $product->health_condition }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Admin Actions -->
                                <div class="d-flex justify-content-between align-items-center pt-4 border-top">
                                    <div>
                                        @if($product->approval_status == 'Pending')
                                            <form action="{{ route('admin.products.approve', $product->id) }}" method="POST"
                                                class="d-inline me-2">
                                                @csrf
                                                <button type="submit" class="btn btn-success">
                                                    <i class="bi bi-check-circle me-1"></i> Approve
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.products.reject', $product->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="bi bi-x-circle me-1"></i> Reject
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this product?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">
                                            <i class="bi bi-trash me-1"></i> Delete Product
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            border-radius: 12px;
        }

        .badge {
            font-weight: 500;
            border-radius: 50px;
        }

        .btn-success {
            background-color: #198754;
            border-color: #198754;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-outline-secondary {
            color: #6c757d;
            border-color: #6c757d;
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
        }

        .btn-outline-danger {
            color: #dc3545;
            border-color: #dc3545;
        }

        .btn-outline-danger:hover {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
        }

        .border-top {
            border-top: 1px solid #e9ecef !important;
        }

        .rounded-3 {
            border-radius: 12px !important;
        }

        .rounded-2 {
            border-radius: 8px !important;
        }

        .img-thumbnail:hover {
            border-color: #8a9c6a;
            transform: scale(1.05);
            transition: all 0.2s ease;
        }
    </style>

    <script>
        function changeMainImage(newSrc) {
            document.querySelector('.img-fluid').src = newSrc;
        }
    </script>
@endsection