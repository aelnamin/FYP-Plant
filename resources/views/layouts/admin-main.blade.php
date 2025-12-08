<!DOCTYPE html>
<html lang="en">

<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta charset="UTF-8">
    <title>@yield('title')</title>

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body style="background-color:#F8F9F5;">

    <div class="container-fluid">
        <div class="row">

            {{-- UNIVERSAL ADMIN SIDEBAR --}}
            <div class="col-md-2 bg-white shadow-sm min-vh-100 p-3">
                <h4 class="text-success fw-bold mb-4">Admin Panel</h4>

                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link text-dark">📊 Dashboard</a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="{{ route('admin.products.index') }}" class="nav-link text-dark">🌿 Products</a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="{{ route('sellers.index') }}" class="nav-link text-dark">🛍️ Sellers</a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="{{ route('orders.index') }}" class="nav-link text-dark">📦 Orders</a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="{{ route('complaints.index') }}" class="nav-link text-dark">⚠️ Complaints</a>
                    </li>

                    <li class="nav-item mt-3">
                        <a href="/" class="btn btn-outline-success w-100">Return to Website</a>
                    </li>
                </ul>
            </div>

            {{-- MAIN CONTENT AREA --}}
            <div class="col-md-10 p-4">
                @yield('content')
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')

</body>

</html>