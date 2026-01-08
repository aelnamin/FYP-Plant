@extends('layouts.admin-main')

@section('title', 'Edit User')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">

                <!-- Back Button (Right Aligned) -->
                <div class="d-flex justify-content-end mb-4">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Back to List
                    </a>
                </div>

                <!-- Centered Header -->
                <div class="text-center mb-4">
                    <h1 class="h3 fw-bold text-gray-900 mb-2">Edit User</h1>
                    <p class="text-gray-600">Update user information and settings</p>
                </div>

                <!-- Form Card -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Personal Information -->
                            <div class="mb-4">
                                <h6 class="fw-semibold text-gray-700 mb-3">Personal Information</h6>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Full Name</label>
                                        <input type="text" name="name" class="form-control" value="{{ $user->name }}"
                                            required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Email Address</label>
                                        <input type="email" name="email" class="form-control" value="{{ $user->email }}"
                                            required>
                                    </div>
                                </div>
                            </div>

                            <!-- Password Update -->
                            <div class="mb-4">
                                <h6 class="fw-semibold text-gray-700 mb-3">Password Update</h6>
                                <p class="text-gray-600 small mb-3">Leave password fields blank to keep current password</p>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">New Password</label>
                                        <input type="password" name="password" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Confirm Password</label>
                                        <input type="password" name="password_confirmation" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="mb-4">
                                <h6 class="fw-semibold text-gray-700 mb-3">Contact Information</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Phone Number</label>
                                        <input type="text" name="phone" class="form-control" value="{{ $user->phone }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Role</label>
                                        <select name="role" class="form-control" required>
                                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin
                                            </option>
                                            <option value="buyer" {{ $user->role == 'buyer' ? 'selected' : '' }}>Buyer
                                            </option>
                                            <option value="seller" {{ $user->role == 'seller' ? 'selected' : '' }}>Seller
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Address</label>
                                        <input type="text" name="address" class="form-control" value="{{ $user->address }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-primary px-4">
                                    Update User
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card {
            border-radius: 12px;
        }

        .form-control:focus {
            border-color: #8a9c6a;
            box-shadow: 0 0 0 0.25rem rgba(138, 156, 106, 0.25);
        }

        .btn-primary {
            background-color: #8a9c6a;
            border-color: #8a9c6a;
        }

        .btn-primary:hover {
            background-color: #6e8055;
            border-color: #6e8055;
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

        .border-top {
            border-top: 1px solid #e9ecef !important;
        }

        .form-label {
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }

        .bi-arrow-left {
            font-size: 0.875rem;
        }
    </style>
@endsection