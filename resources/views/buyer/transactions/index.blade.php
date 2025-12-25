@extends('layouts.main')

@section('title', 'My Transactions')

@section('content')
    <div class="container py-5">
        <h2 class="fw-bold mb-4 text-success">My Orders</h2>

        @forelse($orders as $order)
            <div class="card mb-3 shadow-sm rounded-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">Order #{{ $order->id }}</h5>
                            <small class="text-muted">
                                {{ $order->created_at->format('d M Y, h:i A') }}
                            </small>
                        </div>

                        <span class="badge 
                                @if($order->status === 'Pending') bg-warning
                                @elseif($order->status === 'Paid') bg-primary
                                @elseif($order->status === 'Shipped') bg-success
                                @endif
                            ">
                            {{ $order->status }}
                        </span>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <span>Total Amount</span>
                        <strong>RM {{ number_format($order->total_amount, 2) }}</strong>
                    </div>

                    <div class="mt-3 text-end">
                        <a href="{{ route('buyer.transactions.show', $order->id) }}"
                            class="btn btn-outline-success btn-sm rounded-pill">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-muted">You have no transactions yet.</p>
        @endforelse
    </div>
@endsection