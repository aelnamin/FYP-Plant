@extends('layouts.admin-main')

@section('title', 'Manage Complaints')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Complaint Management</h1>
            <div>
                <!-- You can add buttons here if needed -->
            </div>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.complaints.index') }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="status" class="form-label">Filter by Status</label>
                            <select class="form-select" id="status" name="status">
                                @foreach($statuses as $key => $label)
                                    <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="problem_type" class="form-label">Filter by Problem Type</label>
                            <select class="form-select" id="problem_type" name="problem_type">
                                <option value="">All Problem Types</option>
                                @foreach($problemTypes as $key => $label)
                                    <option value="{{ $key }}" {{ request('problem_type') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" class="form-control" id="search" name="search"
                                placeholder="Search by buyer, seller, or message..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-12 mt-3">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-filter"></i> Apply Filters
                            </button>
                            <a href="{{ route('admin.complaints.index') }}" class="btn btn-secondary">
                                <i class="fas fa-redo"></i> Reset Filters
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Complaints Table -->
        <div class="card">
            <div class="card-body">
                @if($complaints->isEmpty())
                    <div class="alert alert-info">
                        No complaints found matching your criteria.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Buyer</th>
                                    <th>Problem Type</th>
                                    <th>Order</th>
                                    <th>Message Preview</th>
                                    <th>Status</th>
                                    <th>Assigned To</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($complaints as $complaint)
                                                <tr>
                                                    <td>#{{ $complaint->id }}</td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div>
                                                                <strong>{{ $complaint->buyer->name ?? 'N/A' }}</strong>
                                                                <div class="text-muted small">
                                                                    {{ $complaint->buyer->email ?? '' }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info text-dark">
                                                            {{ $complaint->getProblemTypeLabel() ?? 'Unknown' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if($complaint->order_id)
                                                            <a href="{{ route('admin.orders.show', $complaint->order_id) }}"
                                                                class="text-decoration-none" title="View Order">
                                                                Order #{{ $complaint->order_id }}
                                                            </a>
                                                            <div class="text-muted small">
                                                                @if($complaint->seller)
                                                                    Seller: {{ $complaint->seller->name }}
                                                                @else
                                                                    No seller
                                                                @endif
                                                            </div>
                                                        @else
                                                            <span class="text-muted">No Order</span>
                                                            <div class="text-muted small">
                                                                @if($complaint->seller)
                                                                    Seller: {{ $complaint->seller->name }}
                                                                @else
                                                                    General Complaint
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="message-preview">
                                                            @if($complaint->complaint_message)
                                                                {{ Str::limit($complaint->complaint_message, 50) }}
                                                            @else
                                                                <span class="text-muted">No message</span>
                                                            @endif
                                                        </div>
                                                        <div class="text-muted small mt-1">
                                                            <i class="fas fa-clock"></i>
                                                            {{ $complaint->created_at->diffForHumans() }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-{{ 
                                                                    $complaint->status == 'pending' ? 'warning' :
                                    ($complaint->status == 'resolved' ? 'success' :
                                        ($complaint->status == 'closed' ? 'secondary' : 'info')) 
                                                                }}">
                                                            {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if($complaint->admin)
                                                            <div class="d-flex align-items-center">
                                                                <div>
                                                                    <strong>{{ $complaint->admin->name }}</strong>
                                                                    <div class="text-muted small">
                                                                        ID: {{ $complaint->admin->id }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <span class="text-muted">Not Assigned</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $complaint->created_at->format('M d, Y') }}
                                                        <div class="text-muted small">
                                                            {{ $complaint->created_at->format('h:i A') }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <a href="{{ route('admin.complaints.show', $complaint) }}"
                                                                class="btn btn-sm btn-primary" title="Manage">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            @if($complaint->order_id)
                                                                <a href="{{ route('admin.orders.show', $complaint->order_id) }}"
                                                                    class="btn btn-sm btn-info" title="View Order">
                                                                    <i class="fas fa-shopping-cart"></i>
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Showing {{ $complaints->firstItem() }} to {{ $complaints->lastItem() }}
                            of {{ $complaints->total() }} complaints
                        </div>
                        <div>
                            {{ $complaints->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection