@extends('layouts.main')

@section('title', 'My Profile')

@section('content')

    <div class="d-flex justify-content-center mt-5">
        <div class="card shadow p-4 rounded-4" style="width: 1300px; border: none;">

            <h3 class="text-center fw-bold mb-3" style="color: #5C7F51;">My Profile</h3>
            <p class="text-center text-muted mb-4" style="font-size: 14px;">
                Update your personal information.
            </p>

            {{-- Profile Picture --}}
            <div class="text-center mb-4">
                <img src="{{ Auth::user()->profile_picture
        ? asset(Auth::user()->profile_picture)
        : asset('images/default.png') }}" class="rounded-circle" width="140" height="140"
                    style="object-fit: cover; border: 4px solid #A5B682;">
            </div>

            <h4 class="mt-3 fw-bold text-center" style="color:#5C7F51;">
                {{ Auth::user()->name }}
            </h4>

            {{-- UPDATE PROFILE FORM --}}
            <form action="{{ route('buyer.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">

                    {{-- Full Name --}}
                    <div class="mb-3 col-md-6">
                        <label class="form-label fw-semibold">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}" required>
                    </div>

                    {{-- Email --}}
                    <div class="mb-3 col-md-6">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control bg-light" value="{{ Auth::user()->email }}" readonly>
                    </div>

                    {{-- Phone --}}
                    <div class="mb-3 col-md-6">
                        <label class="form-label fw-semibold">Phone Number</label>
                        <input type="text" name="phone" class="form-control" value="{{ Auth::user()->phone }}">
                    </div>

                    {{-- Profile Picture --}}
                    <div class="mb-3 col-md-6">
                        <label class="form-label fw-semibold">Profile Picture</label>
                        <input type="file" name="profile_picture" class="form-control">
                    </div>

                    {{-- Address --}}
                    <div class="mb-3 col-12">
                        <label class="form-label fw-semibold">Address</label>
                        <textarea name="address" rows="3" class="form-control">{{ Auth::user()->address }}</textarea>
                    </div>

                </div>

                {{-- Buttons --}}
                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-success me-2">
                        Save
                    </button>
                </div>

            </form>

            {{-- LOGOUT (SEPARATE FORM — IMPORTANT) --}}
            <div class="d-flex justify-content-end mt-2">
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