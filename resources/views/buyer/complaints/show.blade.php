@extends('layouts.main')

@section('title', 'Complaint Details')

@section('content')
    <div class="container py-5">
        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('complaints.index') }}" class="btn btn-matcha-outline rounded-pill px-4">
                <i class="fas fa-arrow-left me-2"></i>Back to Complaints
            </a>
        </div>

        <!-- Main Card -->
        <div class="card border-0 border-2 shadow-lg rounded-4 overflow-hidden">
            <!-- Header -->
            <div class="card-header bg-matcha-light border-0 py-4 px-5">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 fw-bold text-dark mb-1">Complaint Details</h1>
                        <p class="text-dark mb-0">ID: #{{ $complaint->complaint_id }}</p>
                    </div>
                    @php
                        $statusColors = [
                            'pending' => ['bg' => 'bg-warning', 'text' => 'dark'],
                            'resolved' => ['bg' => 'bg-matcha-success', 'text' => 'white'],
                            'in_progress' => ['bg' => 'bg-matcha-info', 'text' => 'white']
                        ];
                        $status = $statusColors[$complaint->status] ?? ['bg' => 'bg-secondary', 'text' => 'white'];
                    @endphp
                    <span class="badge {{ $status['bg'] }} text-{{ $status['text'] }} rounded-pill px-4 py-2 fs-6">
                        {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                    </span>
                </div>
            </div>

            <div class="card-body p-4 p-md-5">
                <!-- Information Cards -->
                <div class="row g-4 mb-5">
                    <!-- Order Information -->
                    <div class="col-md-6">
                        <div class="card border-0 bg-light rounded-4 h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-matcha-light rounded-3 p-3 me-3">
                                        <i class="fas fa-shopping-basket text-matcha fs-5"></i>
                                    </div>
                                    <h5 class="fw-bold mb-0">Order Information</h5>
                                </div>
                                <div class="ps-5">
                                    <div class="mb-3">
                                        <span class="text-matcha d-block">Order ID</span>
                                        <span class="fw-semibold fs-5 text-dark">#{{ $complaint->order_id }}</span>
                                    </div>
                                    <div>
                                        <span class="text-matcha d-block">Submitted Date</span>
                                        <span class="fw-medium">{{ $complaint->created_at->format('F d, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Complaint Details -->
                    <div class="col-md-6">
                        <div class="card border-0 bg-light rounded-4 h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-matcha-light rounded-3 p-3 me-3">
                                        <i class="fas fa-leaf text-matcha fs-5"></i>
                                    </div>
                                    <h5 class="fw-bold mb-0">Timeline</h5>
                                </div>
                                <div class="ps-5">
                                    <div class="mb-3">
                                        <span class="text-matcha d-block">Created</span>
                                        <span class="fw-medium">{{ $complaint->created_at->format('M d, Y h:i A') }}</span>
                                    </div>
                                    <div class="mb-3">
                                        <span class="text-matcha d-block">Last Updated</span>
                                        <span class="fw-medium">{{ $complaint->updated_at->format('M d, Y h:i A') }}</span>
                                    </div>
                                    @if($complaint->admin)
                                        <div>
                                            <span class="text-matcha d-block">Assigned Admin</span>
                                            <span class="fw-medium">{{ $complaint->admin->name }}</span>
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
                        <i class="fas fa-comment-dots text-matcha me-2"></i>
                        Your Complaint Message
                    </h5>
                    <div class="card border-start border-3 border-matcha rounded-end-4 bg-matcha-lighter">
                        <div class="card-body p-4">
                            <p class="mb-0 fs-6 lh-lg text-dark">{{ $complaint->complaint_message }}</p>
                        </div>
                    </div>
                </div>

                <!-- Admin Response -->
                @if($complaint->admin_response)
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3 d-flex align-items-center">
                            <i class="fas fa-headset text-matcha-success me-2"></i>
                            Admin Response
                        </h5>
                        <div class="card border-0 bg-dark bg-opacity-10 rounded-4">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-start">
                                    <div class="bg-dark bg-opacity-25 rounded-3 p-3 me-3">
                                        <i class="fas fa-user-shield text-dark fs-5"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-3 fs-6 text-dark">{{ $complaint->admin_response }}</p>
                                        <div class="d-flex justify-content-between align-items-center text-matcha">
                                            <small>
                                                <i class="fas fa-clock me-1"></i>
                                                Responded on {{ $complaint->updated_at->format('M d, Y h:i A') }}
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
                    <div class="alert border-0 bg-warning bg-opacity-10 rounded-4 d-flex align-items-center p-4">
                        <div class="bg-warning bg-opacity-25 rounded-3 p-3 me-3">
                            <i class="fas fa-clock text-matcha-warning fs-5"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Under Review</h6>
                            <p class="text-dark mb-0">Your complaint is being reviewed. An admin will respond shortly.</p>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="d-flex justify-content-between align-items-center mt-5 pt-4 border-top border-matcha-light">
                    <a href="{{ route('complaints.index') }}" class="btn btn-matcha-outline rounded-pill px-4">
                        <i class="fas fa-list me-2"></i>View All Complaints
                    </a>
                    @if($complaint->status !== 'resolved')
                        <div class="text-end">
                            <small class="text-matcha d-block mb-1">Expected response within 24-48 hours</small>
                            <span class="badge bg-matcha-light text-dark rounded-pill px-3 py-2">
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
            --matcha: rgb(138, 166, 106);
            --matcha-dark: #689F38;
            --matcha-light: #DCEDC8;
            --matcha-lighter: #F1F8E9;
            --matcha-warning: #FFB74D;
            --matcha-success: #81C784;
            --matcha-info: #4FC3F7;
        }

        .text-matcha {
            color: var(--matcha-dark) !important;
        }

        .text-matcha-success {
            color: var(--matcha-success) !important;
        }

        .text-matcha-warning {
            color: var(--matcha-warning) !important;
        }

        .bg-matcha {
            background-color: var(--matcha) !important;
        }

        .bg-matcha-light {
            background-color: var(--matcha-light) !important;
        }

        .bg-matcha-lighter {
            background-color: var(--matcha-lighter) !important;
        }

        .bg-matcha-warning {
            background-color: var(--matcha-warning) !important;
        }

        .bg-matcha-success {
            background-color: var(--matcha-success) !important;
        }

        .bg-matcha-info {
            background-color: var(--matcha-info) !important;
        }

        .border-matcha {
            border-color: var(--matcha) !important;
        }

        .border-matcha-light {
            border-color: var(--matcha-light) !important;
        }

        .btn-matcha-outline {
            background-color: transparent !important;
            border-color: var(--matcha) !important;
            color: var(--matcha-dark) !important;
        }

        .btn-matcha-outline:hover {
            background-color: var(--matcha) !important;
            color: white !important;
        }

        .badge {
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        .border-start {
            border-left-width: 4px !important;
        }

        .rounded-end-4 {
            border-top-right-radius: 1rem !important;
            border-bottom-right-radius: 1rem !important;
        }

        .bg-opacity-10 {
            --bs-bg-opacity: 0.1;
        }

        .bg-opacity-25 {
            --bs-bg-opacity: 0.25;
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(139, 195, 74, 0.1) !important;
        }

        .fs-6 {
            font-size: 1.05rem !important;
        }

        .lh-lg {
            line-height: 1.7 !important;
        }
    </style>
@endsection