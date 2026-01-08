@extends('layouts.admin-main')

@section('title', 'User Management')

@section('content')
    <div class="container py-5">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold text-gray-900 mb-1">User Management</h1>
                <p class="text-gray-600 mb-0">Manage buyers and sellers</p>
            </div>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Add New User
            </a>
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
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Search by name or email" value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <select name="role" class="form-select">
                                    <option value="">All Roles</option>
                                    <option value="buyer" {{ request('role') == 'buyer' ? 'selected' : '' }}>Buyer</option>
                                    <option value="seller" {{ request('role') == 'seller' ? 'selected' : '' }}>Seller</option>
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

        <!-- Users Table -->
        @if($users->isEmpty())
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="bi bi-people display-4 text-gray-300"></i>
                </div>
                <h5 class="text-gray-700 mb-2">No users found</h5>
                <p class="text-gray-500">Try adjusting your search or filters</p>
            </div>
        @else
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th class="pe-4 text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $u)
                                    <tr class="border-top">
                                        <td class="ps-4">
                                            <div class="fw-semibold text-gray-900">{{ $u->name }}</div>
                                        </td>
                                        <td>
                                            <div class="text-gray-600">{{ $u->email }}</div>
                                        </td>
                                        <td>
                                            @if($u->role == 'admin')
                                                <span class="badge bg-success py-1 px-3">Admin</span>
                                            @elseif($u->role == 'seller')
                                                <span class="badge bg-primary py-1 px-3">Seller</span>
                                            @else
                                                <span class="badge bg-secondary py-1 px-3">Buyer</span>
                                            @endif
                                        </td>
                                        <td class="pe-4 text-end">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.users.edit', $u->id) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    Edit
                                                </a>
                                                <a href="{{ route('admin.users.reports', $u->id) }}"
                                                    class="btn btn-sm btn-outline-warning ms-1">
                                                    Reports
                                                </a>
                                                <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST"
                                                    class="d-inline ms-1"
                                                    onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        Delete
                                                    </button>
                                                </form>
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
            @if($users->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-gray-600">
                        Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
                    </div>
                    <nav>
                        {{ $users->links() }}
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