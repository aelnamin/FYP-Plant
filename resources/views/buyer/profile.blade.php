@extends('layouts.main')

@section('title', 'My Profile')

@section('content')

    <div class="d-flex justify-content-center mt-5"></div>

    <div class="card shadow p-4 rounded-4" style="width: 1300px; border: none;">

        <h3 class="text-center fw-bold mb-3" style="color: #5C7F51;">
            My Profile
        </h3>

        <p class="text-center text-muted mb-4" style="font-size: 14px;">
            Update your personal information.
        </p>

        {{-- Profile Picture --}}
        <div class="text-center mb-4">
            <img src="{{ Auth::user()->profile_picture ?? '/default.png' }}" class="rounded-circle" width="140" height="140"
                style="object-fit: cover; border: 4px solid #A5B682;">
        </div>
        <h4 class="mt-3 fw-bold" style="color:#5C7F51;">
            {{ Auth::user()->name }}
        </h4>

        {{-- Update Form --}}
        <form action="{{ route('buyer.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">

                {{-- Full Name --}}
                <div class="mb-3 col-md-6">
                    <label class="form-label fw-semibold">Full Name</label>
                    <input type="text" name="name" class="form-control rounded-3 py-2" value="{{ Auth::user()->name }}"
                        required style="border: 1px solid #c6d5c2;">
                </div>

                {{-- Email --}}
                <div class="mb-3 col-md-6">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" class="form-control rounded-3 py-2 bg-light" value="{{ Auth::user()->email }}"
                        readonly>
                </div>

                {{-- Phone Number --}}
                <div class="mb-3 col-md-6">
                    <label class="form-label fw-semibold">Phone Number</label>
                    <input type="text" name="phone" class="form-control rounded-3 py-2" value="{{ Auth::user()->phone }}"
                        placeholder="Enter your phone number" style="border: 1px solid #c6d5c2;">
                </div>

                {{-- Profile Picture Upload --}}
                <div class="mb-3 col-md-6">
                    <label class="form-label fw-semibold">Profile Picture</label>
                    <input type="file" name="profile_picture" class="form-control rounded-3 py-2"
                        style="border: 1px solid #c6d5c2;">

                    {{-- Address --}}
                    <div class="mb-3 col-12">
                        <label class="form-label fw-semibold">Address</label>
                        <textarea name="address" class="form-control rounded-3 py-2" rows="3"
                            placeholder="Enter your full address"
                            style="border: 1px solid #c6d5c2;">{{ Auth::user()->address }}</textarea>
                    </div>

                </div>

                <div class="d-flex justify-content-end">
                    <form action="..." method="POST" class="me-2">
                        @csrf
                        <button type="submit" class="btn btn-outline-success">
                            Save
                        </button>
                    </form>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger">
                            Logout
                        </button>
                    </form>
                </div>

            </div>

@endsection