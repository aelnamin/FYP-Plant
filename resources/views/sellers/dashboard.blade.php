@extends('layouts.sellers-main')

@section('title', 'Seller Dashboard')

@section('content')

    <h2 class="fw-bold text-success mb-4">Dashboard</h2>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm rounded p-3">
                <div class="text-muted small">Total Products</div>
                <div class="h3 fw-bold">{{ $total_products }}</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm rounded p-3">
                <div class="text-muted small">Total Orders</div>
                <div class="h3 fw-bold">{{ $total_orders }}</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm rounded p-3 mb-3">
                <h5 class="mb-3">Recent Products</h5>

                <div class="row g-3">
                    @foreach($recentProducts as $p)
                        <div class="col-6 col-md-4">
                            <div class="d-flex">
                                <img src="{{ $p->images->first() ? asset('images/' . $p->images->first()->image_path) : asset('images/default.jpg') }}"
                                    class="rounded" style="width:64px; height:74px; object-fit:cover;">
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

                <a href="{{ route('sellers.inventory.index') }}" class="btn btn-outline-success w-100 mb-2">
                    Manage Products
                </a>

                <a href="{{ route('sellers.inventory.create') }}" class="btn btn-outline-primary w-100 mb-2">
                    Add New Product
                </a>

                <a href="{{ route('sellers.inventory.index') }}" class="btn btn-outline-warning w-100 mb-2">
                    Manage Orders
                </a>
            </div>
        </div>

    </div>

@endsection