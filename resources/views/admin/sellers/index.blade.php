@extends('layouts.admin-main')

@section('title', 'Seller Management')

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
            <h1 class="page-title">Sellers</h1>
            <p class="page-subtitle">Manage sellers</p>
        </div>

        <form method="GET" class="d-flex gap-2">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search sellers..."
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
    <br>

    <!-- Seller Table -->
    <div class="card shadow-sm rounded-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Seller</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sellers as $seller)
                        <tr>
                            <td>{{ $seller->business_name ?? 'Unknown' }}</td>
                            <td>{{ $seller->email ?? 'Unknown' }}</td>

                            <!-- Status Badge -->
                            <td>
                                @if($seller->verification_status == 'Pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($seller->verification_status == 'Approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($seller->verification_status == 'Rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @else
                                    <span class="badge bg-success">Approved</span> <!-- optional fallback -->
                                @endif
                            </td>


                            <!-- Actions -->
                            <td class="text-end">
                                <a href="{{ route('admin.sellers.show', $seller->id) }}"
                                    class="btn btn-sm btn-outline-secondary">
                                    View
                                </a>

                                @if($seller->verification_status == 'Pending')
                                    <form action="{{ route('admin.sellers.approve', $seller->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success">Approve</button>
                                    </form>

                                    <form action="{{ route('admin.sellers.reject', $seller->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Reject</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No sellers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-end mt-3">
        {{ $sellers->links() }}
    </div>

    </div>
@endsection