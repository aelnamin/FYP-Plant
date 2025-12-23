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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


<body style="background-color:rgb(255, 255, 255);">

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-light shadow-sm"
        style="background-color:#A5B682; padding-top:8px; padding-bottom:8px;">
        <div class="container d-flex justify-content-center">
            <span class="text-white text-center" style="font-size: 12px; padding: 0; margin: 0;">
                Ships to all Semenanjung Malaysia, Free Shipping above RM150. Self pickup available. 14 Days Guarantee!
            </span>
        </div>
    </nav>


    <nav class="navbar navbar-expand-lg navbar-light shadow-sm" style="background-color:#FFFFFF;">
        <div class="container">
            <a class="navbar-brand" href="/" style="color: #6A8F4E;">Aether & Leaf.Co</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav"> @php $user = Auth::guard('web')->user(); @endphp <ul class="navbar-nav ms-auto d-flex align-items-center"> <!-- CART ICON -->
                    <li class="nav-item me-3"> 
                        <a class="nav-link" href="{{ $user && $user->role === 'buyer' ? route('buyer.cart') : route('auth.login') }}"> 
                            <i class="bi bi-cart4"style="font-size: 1.3rem;"></i> </a> </li> <!-- PROFILE ICON -->

                    <li class="nav-item"> @if($user) @switch($user->role) @case('buyer') <a class="nav-link" href="{{ route('buyer.profile') }}">
                         <i class="bi bi-person-circle" style="font-size: 1.4rem;"></i> 

                        </a> @break @case('seller') <a class="nav-link" href="{{ route('sellers.dashboard') }}"> 
                            <i class="bi bi-person-circle" style="font-size: 1.4rem;"></i> 

                        </a> @break @case('admin') <a class="nav-link" href="{{ route('admin.dashboard') }}"> 
                            <i class="bi bi-person-circle" style="font-size: 1.4rem;"></i>

                        </a> @break @endswitch @else <a class="nav-link" href="{{ route('auth.login') }}">
                             <i class="bi bi-person" style="font-size: 1.4rem;"></i> </a> @endif </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- PAGE CONTENT -->
    <div class="container py-4">
        @yield('content')
    </div>

</body>

</html>