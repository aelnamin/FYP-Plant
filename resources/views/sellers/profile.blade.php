@extends('layouts.sellers-main')

@section('title', 'Seller Profile')

@section('content')
    <div class="container py-5">

        <h2 class="fw-bold mb-4">My Profile</h2>

        <div class="row">
            <!-- Left: Profile Summary -->
            <div class="col-md-4">
                <div class="card shadow-sm p-3 text-center">

                    <img src="{{ $seller->profile_picture ?? asset('images/default-user.png') }}"
                        class="rounded-circle mb-3" width="140" height="140">

                    <h4 class="fw-bold">{{ $seller->name }}</h4>
                    <p class="text-muted">{{ $seller->email }}</p>
                    <p class="text-muted small">Seller Type: {{ $seller->seller_type }}</p>

                    <hr>

                    <form action="{{ route('seller.profile.updatePhoto') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="profile_picture" class="form-control mb-2">
                        <button class="btn btn-success w-100">Update Photo</button>
                    </form>

                </div>
            </div>

            <!-- Right: Editable Fields -->
            <div class="col-md-8">
                <div class="card shadow-sm p-4">

                    <h5 class="fw-bold mb-3">Business Information</h5>

                    <form action="{{ route('seller.profile.update') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Store Name</label>
                            <input type="text" name="store_name" value="{{ $seller->store_name }}" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Contact Number</label>
                            <input type="text" name="phone" value="{{ $seller->phone }}" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Business Address</label>
                            <textarea name="address" rows="3" class="form-control">{{ $seller->address }}</textarea>
                        </div>

                        <button class="btn btn-primary px-4">Save Changes</button>
                    </form>

                    <hr class="my-4">

                    <h5 class="fw-bold mb-3">Change Password</h5>

                    <form action="{{ route('seller.profile.updatePassword') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="new_password" class="form-control">
                        </div>

                        <button class="btn btn-warning px-4">Update Password</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection