@extends('layouts.admin-main')

@section('title', 'Manage Complaint #' . $complaint->id)

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Complaint #{{ $complaint->id }}</h4>
                        <span
                            class="badge bg-{{ $complaint->status == 'pending' ? 'warning' : ($complaint->status == 'resolved' ? 'success' : 'secondary') }}">
                            {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                        </span>
                    </div>
                    <div class="card-body">
                        <!-- Complaint Information -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5>Buyer Information</h5>
                                <p><strong>Name:</strong> {{ $complaint->buyer->name ?? 'N/A' }}</p>
                                <p><strong>Email:</strong> {{ $complaint->buyer->email ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5>Order Information</h5>
                            <p><strong>Order ID:</strong> #{{ $complaint->order_id }}</p>
                            <p><strong>Complaint Submitted:</strong> {{ $complaint->created_at->format('F d, Y h:i A') }}
                            </p>
                        </div>

                        <!-- Original Complaint -->
                        <div class="mb-4">
                            <h5>Original Complaint Message</h5>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <p class="mb-0">{{ $complaint->complaint_message }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Admin Response Form -->
                        <div class="mb-4">
                            <h5>Admin Response</h5>
                            <form action="{{ route('admin.complaints.update', $complaint) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <textarea class="form-control @error('admin_response') is-invalid @enderror"
                                        id="admin_response" name="admin_response" rows="4"
                                        placeholder="Type your response to the buyer here...">{{ old('admin_response', $complaint->admin_response) }}</textarea>
                                    @error('admin_response')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="status" class="form-label">Update Status</label>
                                        <select class="form-select @error('status') is-invalid @enderror" id="status"
                                            name="status">
                                            <option value="pending" {{ $complaint->status == 'pending' ? 'selected' : '' }}>
                                                Pending</option>
                                            <option value="in_progress" {{ $complaint->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="resolved" {{ $complaint->status == 'resolved' ? 'selected' : '' }}>
                                                Resolved</option>
                                            <option value="closed" {{ $complaint->status == 'closed' ? 'selected' : '' }}>
                                                Closed</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-save"></i> Update Complaint
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Previous Responses (if any) -->
                        @if($complaint->admin_response && $complaint->updated_at != $complaint->created_at)
                            <div class="alert alert-info">
                                <p><strong>Last Response:</strong> {{ $complaint->admin_response }}</p>
                                <small class="text-muted">
                                    Last updated: {{ $complaint->updated_at->format('F d, Y h:i A') }}
                                    @if($complaint->admin)
                                        by {{ $complaint->admin->name }}
                                    @endif
                                </small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar Actions -->
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <!-- Status Quick Buttons -->
                        <div class="d-grid gap-2 mb-3">
                            <form action="{{ route('admin.complaints.update', $complaint) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="in_progress">
                                <button type="submit" class="btn btn-info w-100">
                                    <i class="fas fa-spinner"></i> Mark as In Progress
                                </button>
                            </form>

                            <form action="{{ route('admin.complaints.update', $complaint) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="resolved">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-check-circle"></i> Mark as Resolved
                                </button>
                            </form>

                            <form action="{{ route('admin.complaints.update', $complaint) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="closed">
                                <button type="submit" class="btn btn-warning w-100">
                                    <i class="fas fa-times-circle"></i> Close Complaint
                                </button>
                            </form>
                        </div>

                        <!-- Back Button -->
                        <a href="{{ route('admin.complaints.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

                <!-- Complaint Timeline -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Complaint Timeline</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <i class="fas fa-plus-circle text-primary me-2"></i>
                                <strong>Created:</strong> {{ $complaint->created_at->format('M d, Y h:i A') }}
                            </li>
                            @if($complaint->admin_response)
                                <li class="mb-3">
                                    <i class="fas fa-comment text-success me-2"></i>
                                    <strong>Last Response:</strong> {{ $complaint->updated_at->format('M d, Y h:i A') }}
                                </li>
                            @endif
                            <li>
                                <i class="fas fa-info-circle text-info me-2"></i>
                                <strong>Current Status:</strong> {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection