@extends('layouts.sellers-main')

@section('title', 'Manage Complaints')

@section('content')
    <div class="container py-5">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 class="h2 fw-bold text-gray-900 mb-2">Customer Complaints</h1>
                <p class="text-primary-600 mb-0">Manage and respond to customer complaints</p>
            </div>
            <div class="text-end">
                <div class="badge bg-primary-100 text-primary-700 rounded-pill px-3 py-2 mb-2">
                    <i class="fas fa-chart-line me-2"></i>
                    <span id="complaint-stats">{{ $complaints->count() }} Total Complaints</span>
                </div>
                <div class="text-primary-600">
                    <small><i class="fas fa-info-circle me-1"></i> Respond within 48 hours</small>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="card border-primary-200 border-2 rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-warning-soft rounded-3 p-3 me-3">
                                <i class="fas fa-clock text-warning-800 fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-primary-700 mb-1">Pending</h6>
                                <h3 class="fw-bold text-gray-900 mb-0">
                                    {{ $complaints->where('status', 'pending')->count() }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-primary-200 border-2 rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-info-soft rounded-3 p-3 me-3">
                                <i class="fas fa-spinner text-info-800 fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-primary-700 mb-1">In Progress</h6>
                                <h3 class="fw-bold text-gray-900 mb-0">
                                    {{ $complaints->where('status', 'in_progress')->count() }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-primary-200 border-2 rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-success-soft rounded-3 p-3 me-3">
                                <i class="fas fa-check-circle text-success-800 fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-primary-700 mb-1">Resolved</h6>
                                <h3 class="fw-bold text-gray-900 mb-0">
                                    {{ $complaints->where('status', 'resolved')->count() }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-primary-200 border-2 rounded-4 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-error-soft rounded-3 p-3 me-3">
                                <i class="fas fa-times-circle text-error-800 fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-primary-700 mb-1">Rejected/Closed</h6>
                                <h3 class="fw-bold text-gray-900 mb-0">
                                    {{ $complaints->whereIn('status', ['rejected', 'closed'])->count() }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Complaints Table -->
        <div class="card border-primary-200 border-2 shadow-lg rounded-4 overflow-hidden">
            <div class="card-header bg-primary-50 border-0 py-4 px-5">
                <h5 class="fw-bold text-gray-900 mb-0">
                    <i class="fas fa-list me-2"></i>All Complaints
                </h5>
            </div>

            <div class="card-body p-0">
                @if($complaints->isEmpty())
                    <div class="text-center py-5">
                        <div class="bg-primary-50 rounded-circle d-inline-flex p-4 mb-3">
                            <i class="fas fa-check-circle text-primary-700 fs-1"></i>
                        </div>
                        <h4 class="fw-bold text-gray-900 mb-2">No Complaints Found</h4>
                        <p class="text-primary-600 mb-0">You're all caught up! No customer complaints at the moment.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border-0 ps-5 py-3">Complaint ID</th>
                                    <th class="border-0 py-3">Buyer</th>
                                    <th class="border-0 py-3">Order</th>
                                    <th class="border-0 py-3">Problem Type</th>
                                    <th class="border-0 py-3">Status</th>
                                    <th class="border-0 py-3">Submitted</th>
                                    <th class="border-0 pe-5 py-3 text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($complaints as $complaint)
                                    @php
                                        $statusColors = [
                                            'pending' => ['bg' => 'bg-warning-soft', 'text' => 'text-warning-800'],
                                            'in_progress' => ['bg' => 'bg-info-soft', 'text' => 'text-info-800'],
                                            'resolved' => ['bg' => 'bg-success-soft', 'text' => 'text-success-800'],
                                            'closed' => ['bg' => 'bg-gray-200', 'text' => 'text-gray-700'],
                                            'rejected' => ['bg' => 'bg-error-soft', 'text' => 'text-error-800']
                                        ];
                                        $status = $statusColors[$complaint->status] ?? ['bg' => 'bg-gray-200', 'text' => 'text-gray-700'];
                                    @endphp
                                    <tr class="border-top border-gray-200">
                                        <td class="ps-5 py-4">
                                            <div class="fw-semibold text-gray-900">#{{ $complaint->complaint_id }}</div>
                                            @if($complaint->handled_by == Auth::id())
                                                <small class="text-primary-600">
                                                    <i class="fas fa-user-check me-1"></i>Assigned to you
                                                </small>
                                            @endif
                                        </td>
                                        <td class="py-4">
                                            @if($complaint->buyer)
                                                <div class="fw-medium text-gray-900">{{ $complaint->buyer->name }}</div>
                                                <small class="text-primary-600">{{ $complaint->buyer->email }}</small>
                                            @else
                                                <span class="text-gray-500">N/A</span>
                                            @endif
                                        </td>
                                        <td class="py-4">
                                            @if($complaint->order)
                                                <div class="fw-medium text-gray-900">#{{ $complaint->order->id }}</div>
                                                <small
                                                    class="text-primary-600">{{ $complaint->order->created_at->format('M d, Y') }}</small>
                                            @else
                                                <span class="text-gray-500">N/A</span>
                                            @endif
                                        </td>
                                        <td class="py-4">
                                            <span class="fw-medium text-gray-900">{{ $complaint->problem_type_label }}</span>
                                        </td>
                                        <td class="py-4">
                                            <span class="badge {{ $status['bg'] }} {{ $status['text'] }} rounded-pill px-3 py-1">
                                                {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                                            </span>
                                        </td>
                                        <td class="py-4">
                                            <div class="text-gray-900">{{ $complaint->created_at->format('M d, Y') }}</div>
                                            <small class="text-primary-600">{{ $complaint->created_at->format('g:i A') }}</small>
                                        </td>
                                        <td class="pe-5 py-4 text-end">
                                            <div class="d-flex justify-content-end">
                                                <a href="{{ route('sellers.complaints.show', $complaint) }}"
                                                    class="btn btn-sm btn-outline-primary rounded-pill px-3 me-2">
                                                    <i class="fas fa-eye me-1"></i> View
                                                </a>
                                                @if($complaint->status == 'pending' || ($complaint->status == 'in_progress' && $complaint->handled_by == Auth::id()))
                                                    <a href="{{ route('sellers.complaints.show', $complaint) }}#respond-section"
                                                        class="btn btn-sm btn-primary rounded-pill px-3">
                                                        <i class="fas fa-reply me-1"></i> Respond
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            @if($complaints->hasPages())
                <div class="card-footer bg-transparent border-top border-gray-200 py-4 px-5">
                    {{ $complaints->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Custom Styles -->
    <style>
        :root {
            /* Base color: #8a9c6a */
            --color-primary-50: #f5f7f0;
            --color-primary-100: #e9edd9;
            --color-primary-200: #d4dcba;
            --color-primary-300: #b8c592;
            --color-primary-400: #9bad72;
            --color-primary-500: #8a9c6a;
            --color-primary-600: #6e8055;
            --color-primary-700: #566546;
            --color-primary-800: #465239;
            --color-primary-900: #3b4530;

            /* Harmonious status colors */
            --color-success-50: #f0f7f3;
            --color-success-100: #dcefe4;
            --color-success-soft: #e8f5eb;
            --color-success-800: #2d6b47;

            --color-warning-50: #fef9f0;
            --color-warning-100: #fef0d7;
            --color-warning-soft: #fff4e0;
            --color-warning-800: #9c6c1a;

            --color-info-50: #f0f7fc;
            --color-info-100: #dcedf9;
            --color-info-soft: #e3f2fd;
            --color-info-800: #1a6094;

            --color-error-50: #fdf2f2;
            --color-error-100: #fde8e8;
            --color-error-soft: #fdeaea;
            --color-error-800: #9b1c1c;

            /* Neutral colors */
            --color-gray-100: #f3f4f6;
            --color-gray-200: #e5e7eb;
            --color-gray-300: #d1d5db;
            --color-gray-400: #9ca3af;
            --color-gray-500: #6b7280;
            --color-gray-600: #4b5563;
            --color-gray-700: #374151;
            --color-gray-800: #1f2937;
            --color-gray-900: #111827;
        }

        /* Primary Color Utilities */
        .text-primary-50 {
            color: var(--color-primary-50) !important;
        }

        .text-primary-100 {
            color: var(--color-primary-100) !important;
        }

        .text-primary-200 {
            color: var(--color-primary-200) !important;
        }

        .text-primary-300 {
            color: var(--color-primary-300) !important;
        }

        .text-primary-400 {
            color: var(--color-primary-400) !important;
        }

        .text-primary-500 {
            color: var(--color-primary-500) !important;
        }

        .text-primary-600 {
            color: var(--color-primary-600) !important;
        }

        .text-primary-700 {
            color: var(--color-primary-700) !important;
        }

        .text-primary-800 {
            color: var(--color-primary-800) !important;
        }

        .text-primary-900 {
            color: var(--color-primary-900) !important;
        }

        .bg-primary-50 {
            background-color: var(--color-primary-50) !important;
        }

        .bg-primary-100 {
            background-color: var(--color-primary-100) !important;
        }

        .bg-primary-200 {
            background-color: var(--color-primary-200) !important;
        }

        .bg-primary-300 {
            background-color: var(--color-primary-300) !important;
        }

        .bg-primary-400 {
            background-color: var(--color-primary-400) !important;
        }

        .bg-primary-500 {
            background-color: var(--color-primary-500) !important;
        }

        .bg-primary-600 {
            background-color: var(--color-primary-600) !important;
        }

        .bg-primary-700 {
            background-color: var(--color-primary-700) !important;
        }

        .bg-primary-800 {
            background-color: var(--color-primary-800) !important;
        }

        .bg-primary-900 {
            background-color: var(--color-primary-900) !important;
        }

        .border-primary-50 {
            border-color: var(--color-primary-50) !important;
        }

        .border-primary-100 {
            border-color: var(--color-primary-100) !important;
        }

        .border-primary-200 {
            border-color: var(--color-primary-200) !important;
        }

        .border-primary-300 {
            border-color: var(--color-primary-300) !important;
        }

        .border-primary-400 {
            border-color: var(--color-primary-400) !important;
        }

        .border-primary-500 {
            border-color: var(--color-primary-500) !important;
        }

        .border-primary-600 {
            border-color: var(--color-primary-600) !important;
        }

        .border-primary-700 {
            border-color: var(--color-primary-700) !important;
        }

        .border-primary-800 {
            border-color: var(--color-primary-800) !important;
        }

        .border-primary-900 {
            border-color: var(--color-primary-900) !important;
        }

        /* Status Color Utilities */
        .bg-success-soft {
            background-color: var(--color-success-soft) !important;
        }

        .bg-warning-soft {
            background-color: var(--color-warning-soft) !important;
        }

        .bg-info-soft {
            background-color: var(--color-info-soft) !important;
        }

        .bg-error-soft {
            background-color: var(--color-error-soft) !important;
        }

        .text-success-800 {
            color: var(--color-success-800) !important;
        }

        .text-warning-800 {
            color: var(--color-warning-800) !important;
        }

        .text-info-800 {
            color: var(--color-info-800) !important;
        }

        .text-error-800 {
            color: var(--color-error-800) !important;
        }

        /* Table Styles */
        .table-hover tbody tr:hover {
            background-color: var(--color-primary-50) !important;
        }

        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            color: var(--color-primary-700);
        }

        /* Buttons */
        .btn-primary {
            background-color: var(--color-primary-600) !important;
            border-color: var(--color-primary-600) !important;
            color: white !important;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: var(--color-primary-700) !important;
            border-color: var(--color-primary-700) !important;
            color: white !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(110, 128, 85, 0.3);
        }

        .btn-outline-primary {
            background-color: transparent !important;
            border: 2px solid var(--color-primary-500) !important;
            color: var(--color-primary-700) !important;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background-color: var(--color-primary-600) !important;
            border-color: var(--color-primary-600) !important;
            color: white !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(110, 128, 85, 0.2);
        }

        .btn-sm {
            padding: 0.25rem 0.75rem !important;
            font-size: 0.875rem !important;
        }

        /* Cards & Effects */
        .hover-lift {
            transition: all 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(110, 128, 85, 0.15) !important;
        }

        /* Badges */
        .badge {
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        /* Border Radius */
        .rounded-3 {
            border-radius: 0.75rem !important;
        }

        .rounded-4 {
            border-radius: 1rem !important;
        }

        /* Shadows */
        .shadow-lg {
            box-shadow: 0 10px 25px rgba(110, 128, 85, 0.1) !important;
        }

        /* Animation */
        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(110, 128, 85, 0.12) !important;
        }
    </style>
@endsection