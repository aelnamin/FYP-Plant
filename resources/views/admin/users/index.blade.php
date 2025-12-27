@extends('layouts.admin-main')

@section('title', 'User Management')

@section('content')
    <div class="container mt-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold text-success">User Management</h5>
            <a href="{{ route('admin.users.create') }}" class="btn btn-success">Add New User</a>
        </div>

        <!-- Search & Filter -->
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by name or email"
                    value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <select name="role" class="form-select form-select-sm">
                    <option value="">All Roles</option>
                    <option value="buyer" {{ request('role') == 'buyer' ? 'selected' : '' }}>Buyer</option>
                    <option value="seller" {{ request('role') == 'seller' ? 'selected' : '' }}>Seller</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-sm btn-success w-100">Filter</button>
            </div>
        </form>

        @if($users->isEmpty())
            <p class="text-center text-muted py-4">No users found.</p>
        @else
            <div class="card shadow-sm rounded-4">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $u)
                                <tr class="align-middle">
                                    <td>{{ $u->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="fw-semibold">{{ $u->name }}</div>
                                        </div>
                                    </td>
                                    <td>{{ $u->email }}</td>
                                    <td>
                                        @if($u->role == 'admin')
                                            <span class="badge bg-success">Admin</span>
                                        @elseif($u->role == 'seller')
                                            <span class="badge bg-primary">Seller</span>
                                        @else
                                            <span class="badge bg-secondary">Buyer</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.users.edit', $u->id) }}"
                                            class="btn btn-outline-primary btn-sm me-1">Edit</a>

                                        <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-warning btn-sm me-1">Delete</button>
                                        </form>

                                        <a href="{{ route('admin.users.reports', $u->id) }}"
                                            class="btn btn-outline-success btn-sm">Reports</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $users->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

    <style>
        .table tbody tr:hover {
            background-color: #f8f9fa;
            transition: all 0.2s ease-in-out;
        }
    </style>
@endsection