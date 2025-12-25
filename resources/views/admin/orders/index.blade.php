@extends('layouts.admin-main')

@section('title', 'Admin Orders')

@section('content')
    <div class="container py-5">
        <h2 class="fw-bold mb-4 text-success">All Orders (Admin)</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @forelse($orders as $order)
            <div class="card mb-3 shadow-sm rounded-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Order #{{ $order->id }}</h5>
                        <small class="text-muted">
                            Buyer ID: {{ $order->buyer_id }} | Placed: {{ $order->created_at->format('d/m/Y H:i') }}
                        </small>
                    </div>

                    <div class="text-end">
                        <span class="badge 
                                        @if($order->status === 'Pending') bg-warning
                                        @elseif($order->status === 'Paid') bg-primary
                                        @elseif($order->status === 'Shipped') bg-success
                                        @else bg-secondary
                                        @endif">
                            {{ $order->status }}
                        </span>

                        @if($order->status === 'Pending')
                            <form action="{{ route('admin.orders.markPaid', $order) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success ms-2">Mark as Paid</button>
                            </form>
                        @endif

                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary ms-2">View</a>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">No orders yet.</p>
        @endforelse
    </div>
@endsection