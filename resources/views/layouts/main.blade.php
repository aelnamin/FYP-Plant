<!DOCTYPE html>
<html lang="en">

<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FYPPlant')</title>

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* --- BODY FLEX LAYOUT --- */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #ffffff;
        }

        main {
            flex-grow: 1; /* ensures main content pushes footer down */
        }

        /* --- TOP BAR MARQUEE --- */
        .top-bar {
            background-color: #A5B682;
            overflow: hidden;
            padding: 8px 0;
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        .slide-wrapper {
            white-space: nowrap;
            overflow: hidden;
        }

        .slide-text {
            display: inline-block;
            padding-left: 100%;
            color: white;
            font-size: 14px;
            animation: slide-left 18s linear infinite;
        }

        @keyframes slide-left {
            from {
                transform: translateX(0);
            }
            to {
                transform: translateX(-100%);
            }
        }

        /* --- CART BADGE --- */
        .cart-link {
            position: relative;
            display: inline-block;
        }

        .cart-badge {
            position: absolute;
            top: -1px;
            right: -4px;
            background-color: #198754;
            color: white;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 0.15rem 0.4rem;
            border-radius: 50%;
            min-width: 16px;
            height: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
        }

        /* --- FOOTER --- */
        .footer {
            background-color: #2c3e50;
            color: #ecf0f1;
            padding-top: 40px;
            padding-bottom: 20px;
        }

        .footer h5 {
            color: #A5B682;
            font-weight: 600;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }

        .footer h5:after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 40px;
            height: 2px;
            background-color: #A5B682;
        }

        .footer a {
            color: #bdc3c7;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer a:hover {
            color: #A5B682;
            padding-left: 5px;
        }

        .footer .social-icons a {
            display: inline-block;
            width: 36px;
            height: 36px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            text-align: center;
            line-height: 36px;
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        .footer .social-icons a:hover {
            background-color: #A5B682;
            transform: translateY(-3px);
        }

        .footer .contact-info i {
            color: #A5B682;
            width: 20px;
            margin-right: 10px;
        }

        .footer-bottom {
            background-color: rgba(0, 0, 0, 0.2);
            padding: 15px 0;
            margin-top: 30px;
        }

        .footer .newsletter-form {
            display: flex;
            margin-top: 15px;
        }

        .footer .newsletter-form input {
            flex: 1;
            padding: 10px 15px;
            border: none;
            border-radius: 4px 0 0 4px;
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .footer .newsletter-form input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .footer .newsletter-form button {
            background-color: #A5B682;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .footer .newsletter-form button:hover {
            background-color: #8a9c6a;
        }
    </style>
</head>

<body>

    @php
        $user = Auth::guard('web')->user();
    @endphp

    <main>
        <!-- TOP BAR -->
        <nav class="navbar navbar-light shadow-sm top-bar">
            <div class="slide-wrapper">
                <div class="slide-text">
                    Grown with care. Delivered with love — thoughtfully selected plants for every space &nbsp; • &nbsp;
                    Carefully packed to ensure healthy plant delivery from our nursery to your doorstep &nbsp; • &nbsp;
                    Free shipping on orders above RM150 across Semenanjung Malaysia
                </div>
            </div>
        </nav>

        <!-- MAIN NAVBAR -->
        <nav class="navbar navbar-expand-lg navbar-light shadow-sm" style="background-color:#FFFFFF;">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center gap-2" href="/" style="color: #6A8F4E;">
                    <img src="{{ asset('images/logo3.png') }}" alt="Logo" style="height: 56px; width: auto;">
                    <span>Aether & Leaf.Co</span>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto d-flex align-items-center">
                        <!-- HOME LINK -->
                        <li class="nav-item me-3">
                            <a class="nav-link" 
                            href="{{ auth()->check() && auth()->user()->role === 'buyer' 
                                        ? route('buyer.dashboard') 
                                        : url('/') }}">
                                <i class="bi bi-house" style="font-size: 1.3rem;"></i>
                            </a>
                        </li>

                        <li class="nav-item me-3 position-relative">
    @auth('web') {{-- If a buyer is logged in --}}
        <a href="{{ route('complaints.index') }}" class="nav-link">
            <i class="bi bi-megaphone" style="font-size: 1.3rem;"></i>
        </a>
    @else {{-- If guest --}}
        <a href="{{ route('auth.login') }}" class="nav-link">
            <i class="bi bi-megaphone" style="font-size: 1.3rem;"></i>
        </a>
    @endauth
</li>


                        <li class="nav-item me-3 position-relative">
                            <a href="{{ route('buyer.chats.index') }}" class="nav-link">
                                <i class="bi bi-chat-dots" style="font-size: 1.3rem;"></i>
                            </a>
                        </li>

                        <!-- CART ICON -->
                        <li class="nav-item me-3">
                            @if($user && $user->role === 'buyer')
                                <a class="nav-link cart-link" href="#" data-bs-toggle="offcanvas" data-bs-target="#cartSidebar">
                                    <i class="bi bi-cart4" style="font-size: 1.3rem;"></i>
                                    <span id="cart-count" class="cart-badge">
                                        {{ $user->cart?->items()->sum('quantity') ?? 0 }}
                                    </span>
                                </a>
                            @else
                                <a class="nav-link" href="{{ route('auth.login') }}">
                                    <i class="bi bi-cart4" style="font-size: 1.3rem;"></i>
                                </a>
                            @endif
                        </li>

                        <!-- PROFILE ICON -->
                        <li class="nav-item me-3">
                            @if($user)
                                @switch($user->role)
                                    @case('buyer')
                                        <a class="nav-link" href="{{ route('buyer.profile') }}">
                                            <i class="bi bi-person-circle" style="font-size: 1.4rem;"></i>
                                        </a>
                                        @break
                                    @case('seller')
                                        <a class="nav-link" href="{{ route('sellers.dashboard') }}">
                                            <i class="bi bi-person-circle" style="font-size: 1.4rem;"></i>
                                        </a>
                                        @break
                                    @case('admin')
                                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                            <i class="bi bi-person-circle" style="font-size: 1.4rem;"></i>
                                        </a>
                                        @break
                                @endswitch
                            @else
                                <a class="nav-link" href="{{ route('auth.login') }}">
                                    <i class="bi bi-person" style="font-size: 1.4rem;"></i>
                                </a>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- PAGE CONTENT -->
        <div class="container py-4">
            @yield('content')
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="footer mt-auto">
        <div class="container">
            <div class="row">
                <!-- Company Info -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5>Aether & Leaf.Co</h5>
                    <p class="mb-4">Bringing nature's beauty to your doorstep. We specialize in carefully curated plants for every space, ensuring quality and sustainability.</p>
                </div>

                <!-- Quick Links -->
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                    <li class="mb-2">
    <a href="
        @if(auth()->check() && auth()->user()->role === 'buyer')
            {{ route('buyer.dashboard') }}
        @else
            {{ route('auth.login') }}
        @endif
    ">
        Home
    </a>
</li>

<li class="mb-2">
    <a href="{{ (auth()->check() && auth()->user()->role === 'buyer') ? route('buyer.profile') : route('auth.login') }}">
        My Account
    </a>
</li>

<li class="mb-2">
    <a href="{{ (auth()->check() && auth()->user()->role === 'buyer') ? route('buyer.cart') : route('auth.login') }}">
        Shopping Cart
    </a>
</li>
                        <li class="mb-2"><a href="{{ route('buyer.profile') }}">Track Order</a></li>
                    </ul>
                </div>

                <!-- Categories -->
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5>Categories</h5>
                    <ul class="list-unstyled">
                    <li class="mb-2">Indoor Plants</li>
        <li class="mb-2">Herbs</li>
        <li class="mb-2">Outdoor Plants</li>
        <li class="mb-2">Seeds</li>
        <li class="mb-2">Flowering</li>
        <li class="mb-2">Tools</li>
                    </ul>
                </div>

                <!-- Contact & Newsletter -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5>Contact Us</h5>
                    <div class="contact-info mb-4">
                        <p class="mb-2"><i class="fas fa-map-marker-alt"></i> 1016, Jalan Sultan Ismail, 50250 Kuala Lumpur</p>
                        <p class="mb-2"><i class="fas fa-phone"></i> +60 17-274 3933</p>
                        <p class="mb-2"><i class="fas fa-envelope"></i> aether&leaf@.com</p>
                    </div>

                    <div class="col-lg-8 col-md-6 mb-4">
    <h5>Help Center</h5>
    <ul class="list-unstyled">
        <li class="mb-2">
            <a href="{{ route('buyer.help-center') }}">
            <i class="bi bi-question-circle me-2 "></i>Help Center
            </a>
        </li>
    </ul>
</div>
            </div>

            <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-md-start mb-3 mb-md-0">
                    <p class="mb-0">&copy; 2024 Aether & Leaf.Co. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="{{ route('privacy.policy') }}">Privacy Policy</a>
                    <a href="#" class="ms-3">Terms of Service</a>
                    <a href="#" class="ms-3">Cookie Policy</a>
                </div>
            </div>
        </div>
        </div>
    </footer>

    <!-- CART SIDEBAR -->
    @if($user && $user->role === 'buyer')
    <div class="offcanvas offcanvas-end" tabindex="-1" id="cartSidebar">
        <div class="offcanvas-header">
            <h5 class="fw-bold">Basket</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <div id="cartSidebarItems">
                <div class="text-center text-muted py-3">Loading cart...</div>
            </div>

            <a href="{{ route('buyer.cart') }}" class="btn btn-success w-100 rounded-pill mt-3">View Cart</a>
            <a href="{{ route('buyer.checkout') }}" class="btn btn-outline-success w-100 rounded-pill mt-2">Checkout</a>
        </div>
    </div>
    @endif

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Cart Sidebar AJAX -->
    @if($user && $user->role === 'buyer')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const cartSidebar = document.getElementById('cartSidebar');
            if (!cartSidebar) return;

            cartSidebar.addEventListener('show.bs.offcanvas', function () {
                fetch("{{ route('buyer.cart.sidebar') }}")
                    .then(res => res.text())
                    .then(html => {
                        document.getElementById('cartSidebarItems').innerHTML = html;
                    })
                    .catch(err => console.error(err));
            });
        });

        function updateCartCount(count) {
            const badge = document.getElementById('cart-count');
            if (badge) badge.innerText = count;
        }
    </script>
    @endif

</body>

</html>
