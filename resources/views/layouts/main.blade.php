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
</head>

<style> 
.cart-link {
    position: relative; /* Makes the badge position relative to this link */
    display: inline-block;
}

.cart-badge {
    position: absolute;
    top: -1px;       /* Move closer vertically */
    right: -4px;     /* Move closer horizontally */
    background-color: #198754; /* Bootstrap green */
    color: white;
    font-size: 0.65rem;       /* Smaller font for snug fit */
    font-weight: 700;
    padding: 0.15rem 0.4rem;  /* Smaller padding to hug icon */
    border-radius: 50%;
    min-width: 16px;
    height: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    line-height: 1;
}

</style>

<body style="background-color: #ffffff;">

    @php
        $user = Auth::guard('web')->user();
    @endphp

    <!-- TOP BAR -->
    <nav class="navbar navbar-expand-lg navbar-light shadow-sm"
        style="background-color:#A5B682; padding-top:8px; padding-bottom:8px;">
        <div class="container d-flex justify-content-center">
            <span class="text-white text-center" style="font-size: 14px; margin: 0; padding: 0;">
                Ships to all Semenanjung Malaysia, Free Shipping above RM150. Self pickup available. 14 Days Guarantee!
            </span>
        </div>
    </nav>

    <!-- MAIN NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light shadow-sm" style="background-color:#FFFFFF;">
        <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="/" style="color: #6A8F4E;">
    <img src="{{ asset('images/logo3.png') }}" alt="Logo"
         style="height: 56px; width: auto;">
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

        // Optional: function to update cart count dynamically
        function updateCartCount(count) {
            const badge = document.getElementById('cart-count');
            if (badge) badge.innerText = count;
        }
    </script>
    @endif

</body>

</html>
