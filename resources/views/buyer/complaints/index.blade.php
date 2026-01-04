@extends('layouts.main')

@section('title', 'My Complaints')

@section('content')
    <div class="container py-5">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 class="fw-bold text-dark mb-2">My Complaints</h1>
                <p class="text-matcha">Track and manage your submitted complaints</p>
            </div>
            <a href="{{ route('complaints.create') }}" class="btn btn-matcha btn-lg shadow-sm rounded-3">
                <i class="fas fa-plus me-2"></i>New Complaint
            </a>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-matcha alert-dismissible fade show rounded-3 shadow-sm mb-4 border-0" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- No Complaints State -->
        @if($complaints->isEmpty())
            <div class="card border-0 shadow-lg rounded-4 bg-matcha-light">
                <div class="card-body p-5 text-center">
                    <div class="mb-4">
                        <i class="fas fa-leaf text-matcha" style="font-size: 4rem;"></i>
                    </div>
                    <h3 class="text-dark mb-3">No Complaints Yet</h3>
                    <p class="text-matcha mb-4">You haven't submitted any complaints. Start by creating your first complaint.
                    </p>
                    <a href="{{ route('complaints.create') }}" class="btn btn-matcha px-4 rounded-3">
                        <i class="fas fa-plus me-2"></i>Create Complaint
                    </a>
                </div>
            </div>
        @else
            <!-- Stats Card -->
            <div class="card border-secondary-light shadow-lg rounded-4 mb-4 bg-light">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-matcha-light mb-2">Total Complaints</h6>
                            <h2 class="fw-bold text-dark mb-0">{{ $complaints->total() }}</h2>
                        </div>
                        <div class="bg-matcha-light rounded-3 p-3">
                            <i class="fas fa-file-alt text-matcha fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Complaints List -->
            <div class="row g-4">
                @foreach($complaints as $complaint)
                    <div class="col-12">
                        <div class="card border-secondary-light border-2 shadow-sm rounded-4 hover-lift">
                            <div class="card-body p-4">
                                <div class="row align-items-center">
                                    <!-- Complaint Info -->
                                    <div class="col-lg-8">
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="bg-matcha-light rounded-3 p-3 me-3">
                                                <i class="fas fa-leaf text-matcha"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                                                    <h5 class="fw-bold text-dark mb-0">#{{ $complaint->complaint_id }}</h5>
                                                    @php
                                                        $statusConfig = [
                                                            'pending' => ['color' => 'warning', 'bg' => 'bg-warning', 'icon' => 'clock'],
                                                            'resolved' => ['color' => 'success', 'bg' => 'bg-matcha-success', 'icon' => 'check-circle'],
                                                            'in_progress' => ['color' => 'info', 'bg' => 'bg-matcha-info', 'icon' => 'spinner']
                                                        ];
                                                        $config = $statusConfig[$complaint->status] ?? ['color' => 'secondary', 'bg' => 'bg-secondary', 'icon' => 'question-circle'];
                                                    @endphp
                                                    <span class="badge {{ $config['bg'] }} text-white rounded-pill px-3 py-2">
                                                        <i class="fas fa-{{ $config['icon'] }} me-1"></i>
                                                        {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                                                    </span>
                                                </div>

                                                <div class="mb-3">
                                                    <p class="text-matcha mb-2">
                                                        <i class="fas fa-shopping-basket me-2"></i>
                                                        Order #{{ $complaint->order_id ?? 'N/A' }}
                                                    </p>
                                                    <p class="text-dark mb-0">
                                                        {{ Str::limit($complaint->complaint_message, 80) }}
                                                    </p>
                                                </div>

                                                <div class="text-matcha small">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    Submitted {{ $complaint->created_at->format('M d, Y') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="col-lg-4">
                                        <div class="d-flex justify-content-lg-end gap-2">
                                            <a href="{{ route('complaints.show', $complaint) }}"
                                                class="btn btn-matcha-outline rounded-3 px-4">
                                                <i class="fas fa-eye me-2"></i>View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Progress Bar for in_progress -->
                            @if($complaint->status == 'in_progress')
                                <div class="card-footer bg-matcha-light border-0 pt-0 px-4 pb-3">
                                    <small class="text-dark mt-2 d-block">
                                        Your complaint is being processed
                                    </small>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($complaints->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    <nav aria-label="Page navigation">
                        {{ $complaints->onEachSide(1)->links('vendor.pagination.bootstrap-5') }}
                    </nav>
                </div>
            @endif
        @endif
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

        .bg-matcha {
            background-color: var(--matcha) !important;
        }

        .bg-matcha-light {
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

        .btn-matcha {
            background-color: var(--matcha) !important;
            border-color: var(--matcha) !important;
            color: white !important;
        }

        .btn-matcha:hover {
            background-color: var(--matcha-dark) !important;
            border-color: var(--matcha-dark) !important;
            color: white !important;
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

        .alert-matcha {
            background-color: var(--matcha-lighter) !important;
            border-color: var(--matcha-light) !important;
            color: var(--matcha-dark) !important;
        }

        .border-matcha-light {
            border-color: var(--matcha-light) !important;
        }

        .bg-matcha-gradient {
            background: linear-gradient(135deg, var(--matcha) 0%, var(--matcha-dark) 100%) !important;
        }

        .hover-lift {
            transition: all 0.3s ease;
            border-left: 4px solid var(--matcha-light);
        }

        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(139, 195, 74, 0.15) !important;
            border-left-color: var(--matcha);
        }

        .progress-bar {
            border-radius: 10px;
        }

        .progress-bar-animated {
            animation: progress-animation 1.5s ease infinite;
        }

        @keyframes progress-animation {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        .pagination .page-link {
            color: var(--matcha-dark);
            border-color: var(--matcha-light);
        }

        .pagination .page-link:hover {
            background-color: var(--matcha-light);
            border-color: var(--matcha);
        }

        .pagination .page-item.active .page-link {
            background-color: var(--matcha);
            border-color: var(--matcha);
            color: white;
        }
    </style>
@endsection