@extends('layouts.admin-main')
@section('title', 'Admin Dashboard')

@section('content')

<h2 class="fw-bold text-success mb-4">Dashboard</h2>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm rounded p-3">
            <div class="text-muted small">Total Users</div>
            <div class="h3 fw-bold">{{ $totalUsers }}</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm rounded p-3">
            <div class="text-muted small">Total Sellers</div>
            <div class="h3 fw-bold">{{ $totalSellers }}</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm rounded p-3">
            <div class="text-muted small">Total Products</div>
            <div class="h3 fw-bold">{{ $totalProducts }}</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm rounded p-3">
            <div class="text-muted small">Open Complaints</div>
            <div class="h3 fw-bold">{{ $openComplaints }}</div>
        </div>
    </div>
</div>

<div class="card shadow-sm rounded p-3 mb-4" style="height: 350px;">
    <h5 class="mb-3">Activity Overview</h5>
    <canvas id="activityChart"></canvas>
</div>

{{-- RECENT + QUICK ACTIONS --}}
<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm rounded p-3">
            <h5 class="mb-3">Recent Products</h5>

            <div class="row g-3">
                @foreach($recentProducts as $p)
                <div class="col-6 col-md-4">
                    <div class="d-flex">
                        <img src="{{ $p->images->first()->image_path ?? asset('images/default.jpg') }}"
                            class="rounded"
                            style="width:64px; height:64px; object-fit:cover;">

                        <div class="ms-2">
                            <div class="small text-muted">{{ $p->seller->business_name ?? 'Seller' }}</div>
                            <div class="fw-medium">{{ Str::limit($p->product_name, 22) }}</div>
                            <div class="text-success small">RM {{ number_format($p->price, 2) }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm rounded p-3 mb-3">
            <h6>Quick Actions</h6>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-success w-100 mb-2">Manage Products</a>
            <a href="{{ route('sellers.index') }}" class="btn btn-outline-success w-100 mb-2">Manage Sellers</a>
            <a href="{{ route('orders.index') }}" class="btn btn-outline-success w-100">Manage Orders</a>
        </div>

        <div class="card shadow-sm rounded p-3">
            <h6>Pending Items</h6>
            <ul class="list-unstyled small">
                <li>Pending Products: <strong>{{ $pendingProducts }}</strong></li>
                <li>Pending Sellers: <strong>{{ $pendingSellers }}</strong></li>
                <li>Open Complaints: <strong>{{ $openComplaints }}</strong></li>
            </ul>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('activityChart').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
            datasets: [{
                    label: "New Users",
                    data: [5, 8, 4, 6, 10, 3, 7],
                    borderColor: "#6A8F4E",
                    backgroundColor: "rgba(106,143,78,0.3)",
                    tension: 0.4
                },
                {
                    label: "New Products",
                    data: [2, 4, 3, 8, 5, 9, 6],
                    borderColor: "#A5B682",
                    backgroundColor: "rgba(165,182,130,0.3)",
                    tension: 0.4
                }
            ]
        },
    });
</script>
@endsection