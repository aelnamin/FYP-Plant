@extends('layouts.admin-main')

@section('title', 'Seller Details')

@section('content')
    <div class="container mt-4">

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold text-success">Seller Details</h5>
            <a href="{{ route('admin.sellers.index') }}" class="btn btn-sm btn-outline-secondary">Back to List</a>
        </div>

        <!-- Seller Info Card -->
        <div class="card shadow-sm rounded-4 mb-4 p-3">
            <h6 class="fw-bold">Seller Information</h6>
            <div class="row mt-2">
                <div class="col-md-6">
                    <p><strong>Business Name:</strong> {{ $seller->business_name }}</p>
                    <p><strong>Email:</strong> {{ $seller->user->email }}</p>
                    <p><strong>Phone:</strong> {{ $seller->user->phone ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Address:</strong> {{ $seller->business_address ?? '-' }}</p>
                    <p><strong>Verification Status:</strong>
                        @if($seller->verification_status == 'Pending')
                            <span class="badge bg-warning text-dark">Pending</span>
                        @elseif($seller->verification_status == 'Approved')
                            <span class="badge bg-success">Approved</span>
                        @else
                            <span class="badge bg-danger">Rejected</span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Actions for admin -->
            @if($seller->verification_status == 'Pending')
                <div class="mt-3">
                    <form action="{{ route('admin.sellers.approve', $seller->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-success">Approve</button>
                    </form>

                    <form action="{{ route('admin.sellers.reject', $seller->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger">Reject</button>
                    </form>
                </div>
            @endif
        </div>

        <!-- Seller Products -->
        <div class="card shadow-sm rounded-4 p-3">
            <h6 class="fw-bold">Products by {{ $seller->business_name }}</h6>
            @if($seller->products->count() > 0)
                <div class="table-responsive mt-2">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($seller->products as $product)
                                <tr>
                                    <td>{{ $product->product_name }}</td>
                                    <td>{{ $product->category->category_name ?? '-' }}</td>
                                    <td>RM {{ number_format($product->price, 2) }}</td>
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
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted mt-2">This seller has no products yet.</p>
            @endif
        </div>

    </div>
@endsection