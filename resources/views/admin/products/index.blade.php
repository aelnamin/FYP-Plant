@extends('layouts.admin-main')

@section('title', 'Product Management')

@section('content')
    <div class="container mt-4">

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold text-success">Product Management</h5>

            <form method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search products..."
                    value="{{ request('search') }}">

                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                    <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                </select>

                <button class="btn btn-sm btn-success">Filter</button>
            </form>
        </div>

        <!-- Product Table -->
        <div class="card shadow-sm rounded-4">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Seller</th>
                            <th>Category</th>
                            <th>Price</th>
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

                                            <td>{{ $product->seller->business_name ?? 'Unknown' }}</td>
                                            <td>{{ $product->category->category_name ?? '-' }}</td>
                                            <td class="text-success fw-semibold">
                                                RM {{ number_format($product->price, 2) }}
                                            </td>

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
                                                <a href="{{ route('admin.products.show', $product->id) }}"
                                                    class="btn btn-sm btn-outline-secondary">
                                                    View
                                                </a>

                                                @if($product->approval_status == 'Pending')
                                                    <form action="{{ route('admin.products.approve', $product->id) }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-success">Approve</button>
                                                    </form>

                                                    <form action="{{ route('admin.products.reject', $product->id) }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">Reject</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    No products found.
                                </td>
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