@extends('layouts.sellers-main')

@section('title', 'Seller Profile')

@section('content')

    <style>
        .profile-avatar-form {
            width: 120px;
            height: 120px;
            object-fit: cover;
            object-position: center;
            border: 4px solid #A5B682;
        }
    </style>

    <div class="container mt-5">
        <div class="card shadow p-4 rounded-4 border-0">

            <h3 class="fw-bold mb-2" style="color:#5C7F51;">
                My Profile
            </h3>
            <p class="text-muted mb-4" style="font-size:14px;">
                Manage your shop information and account details.
            </p>

            {{-- Profile Header --}}
            <div class="d-flex align-items-center mb-4">

                {{-- Profile Picture --}}
                <img src="{{ Auth::user()->profile_picture
        ? asset(Auth::user()->profile_picture)
        : asset('images/default.png') }}" class="rounded-circle me-4 profile-avatar-form" alt="Profile Picture">


                {{-- Seller Info --}}
                <div>
                    <h4 class="fw-bold mb-1" style="color:#5C7F51;">
                        {{ Auth::user()->name }}
                    </h4>
                    <p class="text-muted mb-0">
                        {{ Auth::user()->business_name ?? 'Nursery Owner' }}
                    </p>
                </div>
            </div>

            {{-- UPDATE PROFILE FORM --}}
            <form action="{{ route('sellers.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">

                    {{-- Full Name --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}" required>
                    </div>

                    {{-- Business Name --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Shop Name</label>
                        <input type="text" name="business_name" class="form-control"
                            value="{{ Auth::user()->sellerProfile->business_name }}">
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control bg-light" value="{{ Auth::user()->email }}" readonly>
                    </div>

                    {{-- Phone --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Phone Number</label>
                        <input type="text" name="phone" class="form-control" value="{{ Auth::user()->phone }}">
                    </div>

                    {{-- Profile Picture --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Profile Picture</label>
                        <input type="file" name="profile_picture" class="form-control">
                    </div>

                    {{-- Business Address --}}
                    <div class="col-12 mb-3">
                        <label class="form-label fw-semibold">Shop Address</label>
                        <textarea name="business_address"
                            class="form-control">{{ Auth::user()->sellerProfile->business_address ?? '' }}</textarea>
                    </div>

                </div>

                {{-- Buttons --}}
                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-success px-4">
                        Save Changes
                    </button>
                </div>

            </form>

            {{-- LOGOUT --}}
            <div class="d-flex justify-content-end mt-3">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-outline-danger">
                        Logout
                    </button>
                </form>
            </div>

        </div>
    </div>

@endsection