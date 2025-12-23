@extends('layouts.main')

@section('title', 'My Profile')

@section('content')

    <style>
        .profile-container {
            background: linear-gradient(135deg, rgb(255, 255, 255) 0%, rgb(255, 255, 255) 100%);
            min-height: 100vh;
            padding: 40px 0;
        }

        .profile-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(92, 127, 81, 0.15);
            border: none;
            overflow: hidden;
        }

        .profile-header {
            background: linear-gradient(135deg, #5C7F51 0%, #A5B682 100%);
            padding: 40px 0;
            position: relative;
        }

        .profile-pic-wrapper {
            position: relative;
            width: 160px;
            height: 160px;
            margin: 0 auto;
            border: 6px solid white;
            border-radius: 50%;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            background: white;
        }

        .profile-pic-wrapper:hover .profile-pic-overlay {
            opacity: 1;
        }

        .profile-pic {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .profile-pic-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(92, 127, 81, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            border-radius: 50%;
            cursor: pointer;
        }

        .profile-pic-overlay i {
            color: white;
            font-size: 24px;
        }

        .file-input {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .user-name {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .user-email {
            color: #5C7F51;
            font-size: 14px;
            font-weight: 500;
        }

        .form-section {
            padding: 40px;
        }

        .section-title {
            color: #5C7F51;
            font-weight: 600;
            font-size: 18px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e9f5e9;
            position: relative;
        }

        .section-title:after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 60px;
            height: 2px;
            background: #5C7F51;
        }

        .form-label {
            color: #495057;
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #5C7F51;
            box-shadow: 0 0 0 3px rgba(92, 127, 81, 0.1);
        }

        .form-control[readonly] {
            background-color: #f8f9fa;
            border-color: #e9ecef;
            color: #6c757d;
        }

        .btn-save {
            background: linear-gradient(135deg, #5C7F51 0%, #A5B682 100%);
            color: white;
            padding: 12px 36px;
            border-radius: 50px;
            border: none;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(92, 127, 81, 0.2);
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(92, 127, 81, 0.3);
        }

        .btn-logout {
            color: #dc3545;
            border: 2px solid #dc3545;
            padding: 10px 30px;
            border-radius: 50px;
            font-weight: 500;
            transition: all 0.3s ease;
            background: white;
        }

        .btn-logout:hover {
            background: #dc3545;
            color: white;
            transform: translateY(-2px);
        }

        .member-since {
            color: rgb(26, 85, 137);
            font-size: 13px;
            font-weight: 500;
        }
    </style>

    <div class="profile-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-lg-12">
                    <div class="profile-card">

                        {{-- Success Message --}}
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        {{-- Profile Header --}}
                        <div class="profile-header text-center">
                            <div class="position-relative">
                                {{-- Profile Picture Upload --}}
                                <div class="profile-pic-wrapper">
                                    <img src="{{ Auth::user()->profile_picture ? asset(Auth::user()->profile_picture) : asset('images/default.png') }}"
                                        class="profile-pic" id="profilePicPreview">
                                    <div class="profile-pic-overlay">
                                        <i class="bi bi-camera"></i>
                                    </div>
                                </div>
                            </div>

                            <h1 class="user-name mt-4">{{ Auth::user()->name }}</h1>
                            <p class="user-email">
                                <i class="bi bi-envelope me-2"></i>{{ Auth::user()->email }}
                            </p>
                            <p class="member-since mt-1">
                                <i class="bi bi-calendar-event me-1"></i>
                                Member since {{ Auth::user()->created_at->format('M Y') }}
                            </p>
                        </div>

                        {{-- Update Profile Form --}}
                        <div class="form-section">
                            <form action="{{ route('buyer.profile.update') }}" method="POST" enctype="multipart/form-data"
                                id="profileForm">
                                @csrf
                                @method('PUT')

                                <h4 class="section-title">Personal Information</h4>

                                <div class="row g-4">
                                    {{-- Full Name --}}
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="bi bi-person me-2"></i>Full Name
                                        </label>
                                        <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}"
                                            required placeholder="Enter your full name">
                                    </div>

                                    {{-- Email (Readonly) --}}
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="bi bi-envelope me-2"></i>Email Address
                                        </label>
                                        <input type="email" class="form-control" value="{{ Auth::user()->email }}" readonly
                                            placeholder="Your email">
                                        <small class="text-muted mt-1 d-block">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Contact admin to change email
                                        </small>
                                    </div>

                                    {{-- Phone Number --}}
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="bi bi-phone me-2"></i>Phone Number
                                        </label>
                                        <input type="text" name="phone" class="form-control"
                                            value="{{ Auth::user()->phone }}" placeholder="Enter phone number">
                                    </div>

                                    {{-- Profile Picture Input --}}
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="bi bi-image me-2"></i>Profile Picture
                                        </label>
                                        <input type="file" name="profile_picture" class="form-control"
                                            id="profilePictureInput" accept="image/*">
                                        <small class="text-muted mt-1 d-block">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Max 2MB • JPG, PNG, GIF, WEBP
                                        </small>
                                    </div>

                                    {{-- Address --}}
                                    <div class="col-12">
                                        <label class="form-label">
                                            <i class="bi bi-house me-2"></i>Delivery Address
                                        </label>
                                        <textarea name="address" rows="3" class="form-control"
                                            placeholder="Enter your complete address">{{ Auth::user()->address }}</textarea>
                                    </div>
                                </div>

                                {{-- Form Actions --}}
                                <div class="d-flex justify-content-between align-items-center mt-5 pt-4 border-top">
                                    <div>
                                        <button type="button" class="btn-logout"
                                            onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                                        </button>
                                    </div>

                                    <div>
                                        <button type="submit" class="btn-save">
                                            <i class="bi bi-check-circle me-2"></i>Save Changes
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Hidden Logout Form --}}
    <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const profilePicInput = document.getElementById('profilePictureInput');
            const profilePicPreview = document.getElementById('profilePicPreview');
            const profilePicOverlay = document.querySelector('.profile-pic-overlay');

            // When user clicks on the overlay, trigger file input
            profilePicOverlay.addEventListener('click', function () {
                profilePicInput.click();
            });

            // When user selects a new profile picture
            profilePicInput.addEventListener('change', function (e) {
                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function (e) {
                        profilePicPreview.src = e.target.result;
                    }

                    reader.readAsDataURL(e.target.files[0]);
                }
            });

            // Preview profile picture on hover
            const profilePicWrapper = document.querySelector('.profile-pic-wrapper');
            profilePicWrapper.addEventListener('mouseenter', function () {
                profilePicPreview.style.transform = 'scale(1.1)';
            });

            profilePicWrapper.addEventListener('mouseleave', function () {
                profilePicPreview.style.transform = 'scale(1)';
            });
        });
    </script>

@endsection