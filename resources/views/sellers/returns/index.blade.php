@extends('layouts.sellers-main')

@section('title', 'Return Requests')

@section('content')
    <div class="container py-5">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 class="fw-bold text-gray-900 mb-2">Return Requests</h1>
                <p class="text-primary-600">Manage buyer return & refund requests</p>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-primary-50 alert-dismissible fade show rounded-3 shadow-sm mb-4 border-0">
                <i class="fas fa-check-circle me-2 text-primary-700"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($returns->isEmpty())
            <div class="card border-0 shadow-lg rounded-4 bg-primary-50">
                <div class="card-body p-5 text-center">
                    <i class="fas fa-inbox text-primary-600" style="font-size: 4rem;"></i>
                    <h3 class="text-gray-900 mt-3">No Return Requests</h3>
                    <p class="text-primary-700">You don’t have any return requests yet.</p>
                </div>
            </div>
        @else
            <div class="row g-4">
                @foreach($returns as $return)
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4 hover-lift">
                            <div class="card-body p-4">
                                <div class="row align-items-center">

                                    <!-- Return Info -->
                                    <div class="col-lg-8">
                                        <div class="d-flex align-items-start">
                                            <div class="bg-primary-100 rounded-3 p-3 me-3">
                                                <i class="fas fa-undo text-primary-700"></i>
                                            </div>
                                            <div>
                                                <h5 class="fw-bold mb-1">Return #{{ $return->id }}</h5>
                                                <span class="badge rounded-pill px-3 py-2
                                                    {{ match ($return->status) {
                        'pending' => 'bg-warning-soft text-warning-800',
                        'processing' => 'bg-info-soft text-info-800',
                        'approved' => 'bg-success-soft text-success-800',
                        'rejected' => 'bg-danger text-white',
                        default => 'bg-secondary'
                    } }}">
                                                    {{ ucfirst($return->status) }}
                                                </span>

                                                <p class="mb-1"><strong>Buyer:</strong> {{ $return->buyer->name }}</p>
                                                <p class="mb-1"><strong>Reason:</strong> {{ $return->reason }}</p>
                                                <p class="mb-1"><strong>Request Type:</strong> {{ ucfirst($return->request_type) }}
                                                </p>

                                                @if($return->request_type === 'replacement' && $return->status !== 'approved')
                                                    <!-- Form to approve replacement with note -->
                                                    <form method="POST"
                                                        action="{{ route('sellers.returns.updateStatus', $return->id) }}">
                                                        @csrf
                                                        <input type="hidden" name="status" value="approved">
                                                        <textarea name="seller_note" class="form-control mb-2"
                                                            placeholder="Note to buyer">{{ old('seller_note', $return->seller_note) }}</textarea>
                                                        <button class="btn btn-success btn-sm">Approve</button>
                                                    </form>

                                                @endif

                                                <small class="text-primary-600">
                                                    Requested on {{ $return->created_at->format('M d, Y') }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="col-lg-4 mt-3 mt-lg-0 text-lg-end">
                                        @if($return->image)
                                        <a href="{{ asset($return->image) }}" target="_blank" class="btn btn-outline-primary rounded-3 mb-2">
    <i class="fas fa-image me-1"></i> View Proof
</a>

                                        @endif

                                        @if($return->request_type !== 'replacement' || $return->status === 'pending')
                                            <!-- Generic status update dropdown -->
                                            <form action="{{ route('sellers.returns.updateStatus', $return->id) }}" method="POST">
                                                @csrf
                                                <select name="status" class="form-select" onchange="this.form.submit()">
                                                    <option disabled selected>Update Status</option>
                                                    <option value="processing">Processing</option>
                                                    <option value="approved">Approve</option>
                                                    <option value="rejected">Reject</option>
                                                </select>
                                            </form>

                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection