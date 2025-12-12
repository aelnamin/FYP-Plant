<!DOCTYPE html>
<html lang="en">

<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta charset="UTF-8">
    <title>@yield('title')</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body style="background-color:#F8F9F5;">

    <div class="container-fluid">
        <div class="row">

            {{-- SIDEBAR --}}
            <div class="col-md-2 bg-white shadow-sm min-vh-100 p-3">
                <h4 class="text-success fw-bold mb-4">Seller Panel</h4>

                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a href="{{ route('sellers.dashboard') }}" class="nav-link text-dark">📊 Dashboard</a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="{{ route('sellers.inventory.index') }}" class="nav-link text-dark">🌿 Products</a>
                    </li>

                    <li class="nav-item mt-3">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-success w-100">
                                Return to Website
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