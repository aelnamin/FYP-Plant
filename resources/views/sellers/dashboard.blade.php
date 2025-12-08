@extends('layouts.sellers-main')

@section('content')
<div class="container mt-4">
    <h2>Seller Dashboard</h2>

    <div class="row mt-3">
        <div class="col-md-4">
            <div class="card p-3">
                <h4>Total Products</h4>
                <p>{{ $total_products }}</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card p-3">
                <h4>Total Orders</h4>
                <p>{{ $total_orders }}</p>
            </div>
        </div>
    </div>
</div>
@endsection