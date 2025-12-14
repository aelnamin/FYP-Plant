@extends('layouts.admin-main')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="container mt-4">

        <h5 class="fw-bold text-success mb-4">Dashboard</h5>

        <!-- Metrics Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm border-start p-3">
                    <div class="text-muted small">Total Users</div>
                    <div class="h3 fw-bold">{{ $totalUsers }}</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-start p-3">
                    <div class="text-muted small">Total Sellers</div>
                    <div class="h3 fw-bold">{{ $totalSellers }}</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-start p-3">
                    <div class="text-muted small">Total Products</div>
                    <div class="h3 fw-bold">{{ $totalProducts }}</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-start p-3">
                    <div class="text-muted small">Open Complaints</div>
                    <div class="h3 fw-bold">{{ $openComplaints }}</div>
                </div>
            </div>
        </div>

        <!-- Activity Overview Chart -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm rounded p-3">
                    <h5 class="mb-3">Activity Overview</h5>
                    <canvas id="activityChart" style="height:200px; width:100%;"></canvas>
                </div>
            </div>

            <!-- Recent Products -->
            <div class="col-md-6">
                <div class="card shadow-sm rounded p-3">
                    <h5 class="mb-3">Recent Products Added</h5>
                    <div class="row g-3" style="max-height: 240px; overflow-y:auto;">
                        @foreach($recentProducts as $p)
                            <div class="col-6 col-md-6">
                                <div class="d-flex align-items-center bg-light rounded p-2">
                                    <img src="{{ $p->images->first() ? asset('images/' . $p->images->first()->image_path) : asset('images/default.jpg') }}"
                                        class="rounded" style="width:64px; height:74px; object-fit:cover;">
                                    <div class="ms-2">
                                        <div class="small text-muted">{{ $p->seller->business_name ?? 'Seller' }}</div>
                                        <div class="fw-medium">{{ Str::limit($p->product_name, 20) }}</div>
                                        <div class="text-success small">RM {{ number_format($p->price, 2) }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Items & Quick Actions -->
        <div class="row g-4">
            <!-- Pending Items -->
            <div class="col-md-3">
                <div class="card shadow-sm rounded p-3">
                    <h6 class="mb-2">Pending Items</h6>
                    <ul class="list-unstyled small mb-0">
                        <li>Pending Products: <strong>{{ $pendingProducts }}</strong></li>
                        <li>Pending Sellers: <strong>{{ $pendingSellers }}</strong></li>
                        <li>Open Complaints: <strong>{{ $openComplaints }}</strong></li>
                    </ul>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="col-md-3">
                <div class="card shadow-sm rounded p-3">
                    <h6 class="mb-2">Quick Actions</h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-success btn-sm">Manage Users</a>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-success btn-sm">Manage Products</a>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-success btn-sm">Manage Sellers</a>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-success btn-sm">View Complaints</a>
                    </div>
                </div>
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
                datasets: [
                    {
                        label: "New Users",
                        data: [5, 8, 4, 6, 10, 3, 7],
                        borderColor: "#6A8F4E",
                        backgroundColor: "rgba(106,143,78,0.3)",
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: "New Products",
                        data: [2, 4, 3, 8, 5, 9, 6],
                        borderColor: "#A5B682",
                        backgroundColor: "rgba(165,182,130,0.3)",
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
@endsection