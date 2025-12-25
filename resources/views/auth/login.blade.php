@extends('layouts.main')

@section('title', 'Login')

@section('content')
    <style>
        .login-page {
            min-height: 100vh;
            background: linear-gradient(135deg, rgb(255, 255, 255) 0%, rgb(255, 255, 255) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(92, 127, 81, 0.1);
            width: 100%;
            max-width: 600px;
            overflow: hidden;
            border: 1px solid rgba(92, 127, 81, 0.1);
            transition: transform 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(92, 127, 81, 0.15);
        }

        .card-header {
            background: linear-gradient(135deg, #5C7F51 0%, #A5B682 100%);
            padding: 30px;
            text-align: center;
        }

        .logo-container {
            margin-bottom: 15px;
        }

        .logo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            padding: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .logo img {
            width: 134%;
            height: 134%;
            object-fit: cover;
        }

        .brand-name {
            font-size: 24px;
            font-weight: 700;
            color: white;
            margin: 0;
        }

        .brand-tagline {
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
            margin-top: 5px;
        }

        .card-body {
            padding: 40px;
        }

        .welcome-text {
            text-align: center;
            margin-bottom: 30px;
        }

        .welcome-text h3 {
            color: #5C7F51;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .welcome-text p {
            color: #6c757d;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #495057;
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1.5px solid #e9ecef;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #5C7F51;
            box-shadow: 0 0 0 3px rgba(92, 127, 81, 0.1);
        }

        .password-wrapper {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            padding: 5px;
        }

        .btn-login {
            background: linear-gradient(135deg, #5C7F51 0%, #A5B682 100%);
            color: white;
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 16px;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 10px;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #4a6b42 0%, #5C7F51 100%);
            transform: translateY(-2px);
        }

        .login-links {
            margin-top: 25px;
            text-align: center;
        }

        .link-item {
            display: block;
            color: #5C7F51;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            padding: 8px;
            border-radius: 6px;
            transition: all 0.3s ease;
            margin-bottom: 8px;
        }

        .link-item:hover {
            background: rgba(92, 127, 81, 0.1);
            color: #4a6b42;
        }

        .alert-error {
            background: #fee;
            border: 1px solid #fcc;
            color: #c00;
            padding: 12px;
            border-radius: 8px;
            margin-top: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 25px 0;
            color: #999;
            font-size: 13px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e9ecef;
        }

        .divider span {
            padding: 0 15px;
        }
    </style>

    <div class="login-page">
        <div class="login-card">
            <!-- Header -->
            <div class="card-header">
                <div class="logo-container">
                    <div class="logo">
                        <img src="{{ asset('images/logo3.png') }}" alt="Logo">
                    </div>
                </div>
                <h1 class="brand-name">Aether & Leaf.Co</h1>
                <p class="brand-tagline">Nature's finest collection</p>
            </div>

            <!-- Body -->
            <div class="card-body">
                <div class="welcome-text">
                    <h3>Welcome Back</h3>
                    <p>Sign in to access your account</p>
                </div>

                <!-- Login Form -->
                <form action="/login" method="POST">
                    @csrf

                    <!-- Email -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-envelope"></i> Email Address
                        </label>
                        <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-lock"></i> Password
                        </label>
                        <div class="password-wrapper">
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="Enter your password" required>
                            <button type="button" class="toggle-password" onclick="togglePassword()">
                                <i class="bi bi-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="remember" style="border-color: #5C7F51;">
                        <label class="form-check-label" for="remember" style="color: #666; font-size: 14px;">
                            Remember me
                        </label>
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="btn-login">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Sign In
                    </button>

                    <!-- Error Message -->
                    @if (session('login_error'))
                        <div class="alert-error mt-3">
                            <i class="bi bi-exclamation-circle"></i>
                            {{ session('login_error') }}
                        </div>
                    @endif
                </form>

                <!-- Links -->
                <div class="login-links">
                    <a href="/register" class="link-item">
                        <i class="bi bi-person-plus me-2"></i>
                        Create Buyer Account
                    </a>

                    <a href="{{ route('seller.register') }}" class="link-item">
                        <i class="bi bi-shop me-2"></i>
                        Register as Seller
                    </a>
                </div>

                <!-- Divider -->
                <div class="divider">
                    <span>or continue with</span>
                </div>

                <!-- Social Login -->
                <div class="text-center">
                    <button type="button" class="btn btn-outline-secondary rounded-pill me-2"
                        style="border-color: #ddd; padding: 8px 20px; font-size: 14px;">
                        <i class="bi bi-google me-1"></i> Google
                    </button>
                    <button type="button" class="btn btn-outline-secondary rounded-pill"
                        style="border-color: #ddd; padding: 8px 20px; font-size: 14px;">
                        <i class="bi bi-facebook me-1"></i> Facebook
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            }
        }

        // Add focus effect to form inputs
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function () {
                this.parentElement.parentElement.querySelector('.form-label').style.color = '#5C7F51';
            });

            input.addEventListener('blur', function () {
                this.parentElement.parentElement.querySelector('.form-label').style.color = '#495057';
            });
        });
    </script>
@endsection