@extends('layouts.main')

@section('title', 'Complaint Details')

@section('content')
    <div class="container py-5">
        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('complaints.index') }}" class="btn btn-outline-primary rounded-pill px-4">
                <i class="fas fa-arrow-left me-2"></i>Back to Complaints
            </a>
        </div>

        <!-- Main Card -->
        <div class="card border-primary-200 border-2 shadow-lg rounded-4 overflow-hidden">
            <!-- Header -->
            <div class="card-header bg-primary-50 border-0 py-4 px-5">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 fw-bold text-gray-900 mb-1">Complaint Details</h1>
                        <p class="text-primary-700 mb-0">ID: #{{ $complaint->complaint_id }}</p>
                    </div>
                    @php
                        $statusColors = [
                            'pending' => ['bg' => 'bg-warning-soft', 'text' => 'text-warning-800'],
                            'resolved' => ['bg' => 'bg-success-soft', 'text' => 'text-success-800'],
                            'in_progress' => ['bg' => 'bg-info-soft', 'text' => 'text-info-800'],
                            'cancelled' => ['bg' => 'bg-error-soft', 'text' => 'text-error-800']
                        ];
                        $status = $statusColors[$complaint->status] ?? ['bg' => 'bg-gray-200', 'text' => 'text-gray-700'];
                    @endphp
                    <span class="badge {{ $status['bg'] }} {{ $status['text'] }} rounded-pill px-4 py-2 fs-6">
                        {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                    </span>
                </div>
            </div>

            <div class="card-body p-4 p-md-5">
                <!-- Information Cards -->
                <div class="row g-4 mb-5">
                    <!-- Order Information -->
                    <div class="col-md-6">
                        <div class="card border-primary-200 border-2 rounded-4 h-100 hover-lift">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary-100 rounded-3 p-3 me-3">
                                        <i class="fas fa-shopping-basket text-primary-700 fs-5"></i>
                                    </div>
                                    <h5 class="fw-bold text-gray-900 mb-0">Order Information</h5>
                                </div>
                                <div class="ps-5">
                                    <div class="mb-3">
                                        <span class="text-primary-700 d-block">Order ID</span>
                                        <span class="fw-semibold fs-5 text-gray-900">#{{ $complaint->order_id }}</span>
                                    </div>
                                    <div>
                                        <span class="text-primary-700 d-block">Submitted Date</span>
                                        <span
                                            class="fw-medium text-gray-800">{{ $complaint->created_at->format('F d, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Complaint Details -->
                    <div class="col-md-6">
                        <div class="card border-primary-200 border-2 rounded-4 h-100 hover-lift">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary-100 rounded-3 p-3 me-3">
                                        <i class="fas fa-clock text-primary-700 fs-5"></i>
                                    </div>
                                    <h5 class="fw-bold text-gray-900 mb-0">Timeline</h5>
                                </div>
                                <div class="ps-5">
                                    <div class="mb-3">
                                        <span class="text-primary-700 d-block">Created</span>
                                        <span class="fw-medium text-gray-800">{{ $complaint->created_at->format('F d, Y') }}
                                            at {{ $complaint->created_at->format('g:i A') }}</span>
                                    </div>
                                    <div class="mb-3">
                                        <span class="text-primary-700 d-block">Last Updated</span>
                                        <span class="fw-medium text-gray-800">{{ $complaint->updated_at->format('F d, Y') }}
                                            at {{ $complaint->updated_at->format('g:i A') }}</span>
                                    </div>
                                    @if($complaint->admin)
                                        <div>
                                            <span class="text-primary-700 d-block">Assigned Admin</span>
                                            <span class="fw-medium text-gray-800">{{ $complaint->admin->name }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Complaint Message -->
                <div class="mb-5">
                    <h5 class="fw-bold mb-3 d-flex align-items-center">
                        <i class="fas fa-comment-dots text-primary-600 me-2"></i>
                        Your Complaint Message
                    </h5>
                    <div class="card border-start border-3 border-primary-400 rounded-end-4 bg-primary-50">
                        <div class="card-body p-4">
                            <p class="mb-0 fs-6 lh-lg text-gray-800">{{ $complaint->complaint_message }}</p>
                        </div>
                    </div>
                </div>

                <!-- Admin Response -->
                @if($complaint->admin_response)
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3 d-flex align-items-center">
                            <i class="fas fa-headset text-primary-600 me-2"></i>
                            Admin Response
                        </h5>
                        <div class="card border-0 bg-success-soft rounded-4">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-start">
                                    <div class="bg-primary-100 rounded-3 p-3 me-3">
                                        <i class="fas fa-user-shield text-primary-700 fs-5"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-3 fs-6 text-gray-800">{{ $complaint->admin_response }}</p>
                                        <div class="d-flex justify-content-between align-items-center text-primary-600">
                                            <small>
                                                <i class="fas fa-clock me-1"></i>
                                                Responded on {{ $complaint->updated_at->format('F d, Y') }} at
                                                {{ $complaint->updated_at->format('g:i A') }}
                                            </small>
                                            @if($complaint->admin)
                                                <small class="fw-medium">
                                                    <i class="fas fa-user-tie me-1"></i>
                                                    {{ $complaint->admin->name }}
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Pending Status -->
                    <div class="alert border-0 bg-warning-soft rounded-4 d-flex align-items-center p-4">
                        <div class="bg-primary-100 rounded-3 p-3 me-3">
                            <i class="fas fa-clock text-primary-700 fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-gray-900 mb-1">Under Review</h6>
                            <p class="text-primary-700 mb-0">Your complaint is being reviewed. An admin will respond shortly.
                            </p>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="d-flex justify-content-between align-items-center mt-5 pt-4 border-top border-primary-200">
                    <a href="{{ route('complaints.index') }}" class="btn btn-outline-primary rounded-pill px-4">
                        <i class="fas fa-list me-2"></i>View All Complaints
                    </a>
                    @if($complaint->status !== 'resolved')
                        <div class="text-end">
                            <small class="text-primary-600 d-block mb-1">Expected response within 24-48 hours</small>
                            <span class="badge bg-primary-100 text-primary-700 rounded-pill px-3 py-2">
                                <i class="fas fa-hourglass-half me-1"></i>Processing
                            </span>
                        </div>
                    @endif
                </div>
            </div>
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

        /* Neutral Color Utilities */
        .text-gray-100 {
            color: var(--color-gray-100) !important;
        }

        .text-gray-200 {
            color: var(--color-gray-200) !important;
        }

        .text-gray-300 {
            color: var(--color-gray-300) !important;
        }

        .text-gray-400 {
            color: var(--color-gray-400) !important;
        }

        .text-gray-500 {
            color: var(--color-gray-500) !important;
        }

        .text-gray-600 {
            color: var(--color-gray-600) !important;
        }

        .text-gray-700 {
            color: var(--color-gray-700) !important;
        }

        .text-gray-800 {
            color: var(--color-gray-800) !important;
        }

        .text-gray-900 {
            color: var(--color-gray-900) !important;
        }

        .bg-gray-100 {
            background-color: var(--color-gray-100) !important;
        }

        .bg-gray-200 {
            background-color: var(--color-gray-200) !important;
        }

        .bg-gray-300 {
            background-color: var(--color-gray-300) !important;
        }

        .bg-gray-400 {
            background-color: var(--color-gray-400) !important;
        }

        .bg-gray-500 {
            background-color: var(--color-gray-500) !important;
        }

        .bg-gray-600 {
            background-color: var(--color-gray-600) !important;
        }

        .bg-gray-700 {
            background-color: var(--color-gray-700) !important;
        }

        .bg-gray-800 {
            background-color: var(--color-gray-800) !important;
        }

        .bg-gray-900 {
            background-color: var(--color-gray-900) !important;
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

        /* Cards & Effects */
        .hover-lift {
            transition: all 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(110, 128, 85, 0.15) !important;
        }

        /* Alerts */
        .alert {
            border-width: 1px;
        }

        /* Badges */
        .badge {
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        /* Borders */
        .border-start {
            border-left-width: 4px !important;
        }

        /* Border Radius */
        .rounded-end-4 {
            border-top-right-radius: 1rem !important;
            border-bottom-right-radius: 1rem !important;
        }

        .rounded-3 {
            border-radius: 0.75rem !important;
        }

        .rounded-4 {
            border-radius: 1rem !important;
        }

        /* Typography */
        .fs-6 {
            font-size: 1.05rem !important;
        }

        .lh-lg {
            line-height: 1.7 !important;
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