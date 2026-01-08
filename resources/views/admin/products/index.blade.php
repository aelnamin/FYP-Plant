@extends('layouts.admin-main')

@section('title', 'Product Management')

@section('content')
    <div class="container py-5">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold text-gray-900 mb-1">Product Management</h1>
                <p class="text-gray-600 mb-0">Manage and approve seller products</p>
            </div>
        </div>

        <!-- Centered Search & Filter -->
        <div class="row justify-content-center mb-4">
            <div class="col-md-8 col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form method="GET" class="row g-3 align-items-center">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent">
                                        <i class="bi bi-search text-gray-500"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control" placeholder="Search products"
                                        value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved
                                    </option>
                                    <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary w-100">
                                    Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Table -->
        @if($products->isEmpty())
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="bi bi-box display-4 text-gray-300"></i>
                </div>
                <h5 class="text-gray-700 mb-2">No products found</h5>
                <p class="text-gray-500">Try adjusting your search or filters</p>
            </div>
        @else
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Product</th>
                                    <th>Seller</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th class="pe-4 text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                                <tr class="border-top">
                                                    <td class="ps-4">
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ $product->images->first()
                                    ? asset('images/' . $product->images->first()->image_path)
                                    : asset('images/default.jpg') }}" class="rounded me-3"
                                                                style="width:60px;height:60px;object-fit:cover;">
                                                            <div>
                                                                <div class="fw-semibold text-gray-900">{{ $product->product_name }}</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="text-gray-600">{{ $product->seller->business_name ?? 'Unknown' }}</div>
                                                    </td>
                                                    <td>
                                                        <div class="text-gray-600">{{ $product->category->category_name ?? '-' }}</div>
                                                    </td>
                                                    <td class="fw-semibold text-success">
                                                        RM {{ number_format($product->price, 2) }}
                                                    </td>
                                                    <td>
                                                        @if($product->approval_status == 'Pending')
                                                            <span class="badge bg-warning py-1 px-3">Pending</span>
                                                        @elseif($product->approval_status == 'Approved')
                                                            <span class="badge bg-success py-1 px-3">Approved</span>
                                                        @elseif($product->approval_status == 'Rejected')
                                                            <span class="badge bg-danger py-1 px-3">Rejected</span>
                                                        @endif
                                                    </td>
                                                    <td class="pe-4 text-end">
                                                        <div class="btn-group" role="group">
                                                            <a href="{{ route('admin.products.show', $product->id) }}"
                                                                class="btn btn-sm btn-outline-primary">
                                                                View
                                                            </a>

                                                            @if($product->approval_status == 'Pending')
                                                                <form action="{{ route('admin.products.approve', $product->id) }}" method="POST"
                                                                    class="d-inline ms-1">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-sm btn-outline-success">
                                                                        Approve
                                                                    </button>
                                                                </form>
                                                                <form action="{{ route('admin.products.reject', $product->id) }}" method="POST"
                                                                    class="d-inline ms-1">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                                        Reject
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-gray-600">
                        Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} products
                    </div>
                    <nav>
                        {{ $products->links() }}
                    </nav>
                </div>
            @endif
        @endif
    </div>

    <style>
        :root {
            --color-primary: #8a9c6a;
            --color-primary-dark: #6e8055;
        }

        .card {
            border-radius: 12px;
        }

        .table th {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #495057;
            border-bottom: 2px solid #e9ecef !important;
            padding: 1rem 0.75rem;
        }

        .table td {
            padding: 1.25rem 0.75rem;
            vertical-align: middle;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(138, 156, 106, 0.05) !important;
        }

        .badge {
            font-weight: 500;
            border-radius: 50px;
        }

        .btn-primary {
            background-color: var(--color-primary);
            border-color: var(--color-primary);
        }

        .btn-primary:hover {
            background-color: var(--color-primary-dark);
            border-color: var(--color-primary-dark);
        }

        .btn-outline-primary {
            color: var(--color-primary);
            border-color: var(--color-primary);
        }

        .btn-outline-primary:hover {
            background-color: var(--color-primary);
            border-color: var(--color-primary);
            color: white;
        }

        .btn-outline-success {
            color: #198754;
            border-color: #198754;
        }

        .btn-outline-success:hover {
            background-color: #198754;
            border-color: #198754;
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

        .form-control:focus,
        .form-select:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 0.25rem rgba(138, 156, 106, 0.25);
        }

        .input-group-text {
            background-color: transparent;
            border-color: #dee2e6;
        }
    </style>
@endsection