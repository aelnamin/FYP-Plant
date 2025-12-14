@extends('layouts.admin-main')

@section('title', 'User Reports')

@section('content')
    <div class="container mt-5">

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold">{{ $user->name }}</h2>
                <p class="text-muted mb-0">{{ $user->email }} | Role: <span class="text-capitalize">{{ $user->role }}</span>
                </p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Back to Users</a>
        </div>

        <div class="row g-4">
            <!-- Orders Card -->
            <div class="col-md-6">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-light fw-bold">Orders</div>
                    <div class="card-body p-3">
                        @if($reports['orders']->isEmpty())
                            <p class="text-muted small mb-0">No orders found for this user.</p>
                        @else
                            <ul class="list-group list-group-flush">
                                @foreach($reports['orders'] as $order)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="fw-medium">Order #{{ $order->id }}</span>
                                            <div class="small text-muted">{{ $order->created_at->format('d M Y, H:i') }}</div>
                                        </div>
                                        @if(isset($order->total_price))
                                            <span class="text-success fw-bold">RM {{ number_format($order->total_price, 2) }}</span>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Complaints Card -->
            <div class="col-md-6">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-warning fw-bold">Complaints</div>
                    <div class="card-body p-3">
                        @if($reports['complaints']->isEmpty())
                            <p class="text-muted small mb-0">No complaints found for this user.</p>
                        @else
                            <ul class="list-group list-group-flush">
                                @foreach($reports['complaints'] as $complaint)
                                    <li class="list-group-item">
                                        <div class="fw-medium">{{ $complaint->message ?? 'No message provided' }}</div>
                                        <div class="small text-muted mt-1">
                                            {{ $complaint->created_at->format('d M Y, H:i') }}
                                            @if(isset($complaint->status))
                                                | Status: <span class="text-capitalize">{{ $complaint->status }}</span>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Optional: Add More Cards Later -->
    </div>
@endsection