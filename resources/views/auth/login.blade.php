@extends('layouts.main')

@section('title', 'Login')

@section('content')

<div class="d-flex justify-content-center mt-5">

    <div class="card shadow p-4 rounded-4" style="width: 380px; border: none;">

        <h3 class="text-center fw-bold mb-3" style="color: #5C7F51;">
            Login to Aether & Leaf.Co
        </h3>

        <p class="text-center text-muted mb-4" style="font-size: 14px;">
            Welcome back! Please enter your credentials.
        </p>

        <!-- LOGIN FORM -->
        <form action="/login" method="POST">
            @csrf

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Email Address</label>
                <input type="email" name="email" class="form-control rounded-3 py-2"
                    placeholder="Enter your email" required
                    style="border: 1px solid #c6d5c2;">
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Password</label>
                <input type="password" name="password" class="form-control rounded-3 py-2"
                    placeholder="Enter your password" required
                    style="border: 1px solid #c6d5c2;">
            </div>

            <!-- Login Button -->
            <button type="submit"
                class="btn w-100 py-2 rounded-4 fw-semibold"
                style="background-color: #A5B682; color: white; transition: 0.3s;">
                Login
            </button>

        </form>

        @if (session('login_error'))
        <div class="alert alert-danger text-center mt-2">
            {{ session('login_error') }}
        </div>
        @endif


        <!-- OPTIONAL: Register Link -->
        <p class="text-center mt-3" style="font-size: 14px;">
            Don’t have an account?
            <a href="/register" class="fw-semibold" style="color: #5C7F51;">Register</a>
        </p>

    </div>

</div>

@endsection