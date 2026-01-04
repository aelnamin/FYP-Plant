@extends('layouts.main')

@section('title', 'Register')

@section('content')

    <div class="d-flex justify-content-center mt-5">

        <div class="card shadow p-4 rounded-4" style="width: 800px; max-width: 95%; border: none;">

            <h3 class="text-center fw-bold mb-3" style="color: #5C7F51;">
                Create Your Aether & Leaf.Co Account
            </h3>

            <p class="text-center text-muted mb-4" style="font-size: 14px;">
                Join us and begin your plant shopping journey!
            </p>

            <!-- ERROR MESSAGES -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="m-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- REGISTER FORM -->
            <form action="{{ route('register') }}" method="POST">
                @csrf

                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <!-- Full Name -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Full Name</label>
                            <input type="text" name="name" class="form-control rounded-3 py-2"
                                placeholder="Enter your full name" required style="border: 1px solid #c6d5c2;">
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email Address</label>
                            <input type="email" name="email" class="form-control rounded-3 py-2"
                                placeholder="Enter your email" required style="border: 1px solid #c6d5c2;">
                        </div>

                        <!-- Phone -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Phone Number</label>
                            <input type="text" name="phone" class="form-control rounded-3 py-2" placeholder="012-3456789"
                                required style="border: 1px solid #c6d5c2;">
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <!-- Address -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Address</label>
                            <textarea name="address" class="form-control rounded-3 py-2" placeholder="Enter your address"
                                rows="2" style="border: 1px solid #c6d5c2;"></textarea>
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password</label>
                            <input type="password" name="password" class="form-control rounded-3 py-2"
                                placeholder="Create a password" required style="border: 1px solid #c6d5c2;">
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control rounded-3 py-2"
                                placeholder="Confirm your password" required style="border: 1px solid #c6d5c2;">
                        </div>
                    </div>
                </div>

                <!-- Register Button -->
                <div class="mt-3">
                    <button type="submit" class="btn w-100 py-2 rounded-4 fw-semibold"
                        style="background-color: #A5B682; color: white; transition: 0.3s;">
                        Create Account
                    </button>
                </div>

            </form>

            <!-- Already have an account -->
            <p class="text-center mt-3" style="font-size: 14px;">
                Already have an account?
                <a href="/login" class="fw-semibold" style="color: #5C7F51;">Login</a>
            </p>

        </div>

    </div>

@endsection