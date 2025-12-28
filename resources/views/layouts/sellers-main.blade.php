<!DOCTYPE html>
<html lang="en">

<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta charset="UTF-8">
    <title>@yield('title')</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

</head>
<style>
    .profile-avatar {
        width: 160px;
        height: 160px;
        object-fit: cover;
        object-position: center;
        border: 4px solid #A5B682;
    }
</style>

<body style="background-color:#F8F9F5;">

    <div class="container-fluid">
        <div class="row">

            {{-- SIDEBAR --}}
            <div class="col-md-2 bg-white shadow-sm min-vh-100 p-3">

                {{-- Profile Picture --}}
                <img src="{{ Auth::user()->profile_picture
    ? asset(Auth::user()->profile_picture)
    : asset('images/default.png') }}" class="rounded-circle me-4 profile-avatar" alt="Profile Picture">

                <h4 class="mt-3 fw-bold" style="color:#5C7F51;">
                    {{ Auth::user()->sellerProfile->business_name ?? Auth::user()->name }}
                </h4>


                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a href="{{ route('sellers.dashboard') }}" class="nav-link text-dark">Dashboard</a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="{{ route('sellers.inventory.index') }}" class="nav-link text-dark">Manage Products</a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="{{ route('sellers.orders.index') }}" class="nav-link text-dark">Orders</a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="{{ route('sellers.profile') }}" class="nav-link text-dark">Profile</a>
                    </li>

                    <li class="nav-item mt-3">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-success w-100">
                                Log Out
                            </button>
                        </form>

                    </li>
                </ul>
            </div>

            {{-- MAIN CONTENT --}}
            <div class="col-md-10 p-4">
                @yield('content')
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')

</body>

</html>