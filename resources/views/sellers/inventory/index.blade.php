@extends('layouts.sellers-main')

@section('title', 'My Inventory')

@section('content')
    <div class="container mt-4">
        <style>
            .page-header {
                margin-bottom: 2rem;
            }

            .page-title {
                font-weight: 600;
                color: #2c3e50;
                margin-bottom: 0.25rem;
            }

            .page-subtitle {
                color: #6c757d;
                font-size: 0.9rem;
            }
        </style>

        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">My Products</h1>
            <p class="page-subtitle">Manage Products</p>
        </div>
<div>
        <a href="{{ route('sellers.inventory.create') }}" class="btn btn-sm btn-success">
            + Add Product
        </a>
    </div>
    <br>

    <!-- Search & Filter -->
    <form method="GET" class="d-flex gap-2 mb-3">
        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search products..."
            value="{{ request('search') }}">

        <button class="btn btn-sm btn-success">Filter</button>
    </form>

    {{-- SUCCESS MESSAGE --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Inventory Table -->
    <div class="card shadow-sm rounded-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($products as $product)
                                <tr>
                                    <!-- Product Info -->
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $product->images->first()
                        ? asset('images/' . $product->images->first()->image_path)
                        : asset('images/default.jpg') }}" class="rounded me-2"
                                                style="width:60px;height:60px;object-fit:cover;">

                                            <div>
                                                <div class="fw-semibold">{{ $product->product_name }}</div>
                                                <small class="text-muted">ID: {{ $product->id }}</small>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Category -->
                                    <td>{{ $product->category->category_name ?? '-' }}</td>

                                    <!-- Price -->
                                    <td class="text-success fw-semibold">
                                        RM {{ number_format($product->price, 2) }}
                                    </td>

                                    <!-- Stock -->
                                    <td>{{ $product->stock_quantity }}</td>


                                    <!-- Status Badge -->
                                    <td>
                                        @if($product->approval_status == 'Pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif($product->approval_status == 'Approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($product->approval_status == 'Rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @else
                                            <span class="badge bg-success">Approved</span> <!-- optional fallback -->
                                        @endif
                                    </td>


                                    <!-- Actions -->
                                    <td class="text-end">
                                        <a href="{{ route('sellers.inventory.show', $product->id) }}"
                                            class="btn btn-sm btn-outline-secondary">View</a>

                                        <a href="{{ route('sellers.inventory.edit', $product->id) }}"
                                            class="btn btn-sm btn-outline-warning">Edit</a>

                                        <form action="{{ route('sellers.inventory.destroy', $product->id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Delete this product?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-end mt-3">
        {{ $products->links() }}
    </div>
    </div>
@endsection