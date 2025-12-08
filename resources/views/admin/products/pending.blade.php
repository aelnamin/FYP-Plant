@extends('layouts.admin-main')

@section('title', 'Content Approval')

@section('content')
<h4>Pending Product Listings</h4>

@foreach ($products as $p)
<div class="card shadow-sm mb-3">
    <div class="card-body">
        <h5>{{ $p->name }}</h5>
        <p>Seller: <strong>{{ $p->seller->name }}</strong></p>

        <p>Status:
            <span class="badge bg-warning">Pending</span>
        </p>

        <form action="/admin/listings/approve/{{ $p->id }}" method="POST">
            @csrf
            <button class="btn btn-success btn-sm">Approve</button>
        </form>
    </div>
</div>
@endforeach

@if ($products->count() == 0)
<div class="alert alert-info mt-3">No pending items.</div>
@endif

@endsection