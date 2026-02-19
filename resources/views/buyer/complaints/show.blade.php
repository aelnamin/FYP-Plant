@extends('layouts.main')

@section('title', 'Complaint Details')

@section('content')
    <div class="container py-5">
        <!-- Back Button (matching submit page) -->
        <div class="mb-4">
            <a href="{{ route('complaints.index') }}" class="btn btn-outline-dark rounded-pill px-4">
                <i class="fas fa-arrow-left me-2"></i>Back to Complaints
            </a>
        </div>

        <!-- Main Card (matching submit page style) -->
        <div class="card border-light shadow-sm rounded-3 overflow-hidden">
            <!-- Card Header with icon -->
            <div class="card-header bg-light border-0 py-4 px-5">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="rounded-3 p-3 me-3" style="background-color: #e8f0e8;">
                            <i class="fas fa-exclamation-circle" style="color: #8a9c6a; font-size: 1.2rem;"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold text-dark mb-1">Complaint #{{ $complaint->complaint_id }}</h3>
                            <p class="text-secondary mb-0">Submitted on {{ $complaint->created_at->format('M d, Y H:i') }}
                            </p>
                        </div>
                    </div>
        @php
            $statusColors = [
                'pending' => ['bg' => 'bg-warning-soft', 'text' => 'text-warning-dark', 'icon' => 'fas fa-clock'],
                'in_progress' => ['bg' => 'bg-info-soft', 'text' => 'text-info-dark', 'icon' => 'fas fa-spinner'],
                'resolved' => ['bg' => 'bg-success-soft', 'text' => 'text-success-dark', 'icon' => 'fas fa-check-circle'],
                'closed' => ['bg' => 'bg-light', 'text' => 'text-secondary', 'icon' => 'fas fa-lock'],
                'rejected' => ['bg' => 'bg-danger-soft', 'text' => 'text-danger-dark', 'icon' => 'fas fa-times-circle']
            ];
            $status = $statusColors[$complaint->status] ?? ['bg' => 'bg-light', 'text' => 'text-secondary', 'icon' => 'fas fa-question-circle'];
        @endphp
                    <span class="badge {{ $status['bg'] }} {{ $status['text'] }} rounded-pill px-4 py-2 fs-6">
                        <i class="{{ $status['icon'] }} me-2"></i>
                        {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                    </span>
                </div>
            </div>

            <div class="card-body p-4 p-md-5">
                <!-- Two‑column information cards with green accents -->
                <div class="row g-4 mb-5">
                    <!-- Left: Complaint & Order Info -->
                    <div class="col-md-6">
                        <div class="card border-0 bg-light rounded-3 h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-2 p-2 me-2" style="background-color: #e8f0e8;">
                                        <i class="fas fa-exclamation-triangle" style="color: #8a9c6a;"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark mb-0">Complaint Information</h5>
                                </div>
                                <div class="ps-4">
                                    <div class="mb-3">
                                        <span class="text-secondary d-block">Problem Type</span>
                                        <span class="fw-semibold text-dark">{{ $complaint->problem_type_label }}</span>
                                    </div>
                                    @if($complaint->order)
                                        <div class="mb-3">
                                            <span class="text-secondary d-block">Order ID</span>
                                            <span class="fw-medium text-dark">
                                                #{{ $complaint->order->id }}
                                                @if($complaint->order->seller)
                                                    <span class="badge bg-white text-secondary ms-2 border">
                                                        Seller: {{ $complaint->order->seller->name }}
                                                    </span>
                                                @endif
                                            </span>
                                        </div>
                                        <div>
                                            <span class="text-secondary d-block">Order Date</span>
                                            <span
                                                class="fw-medium text-dark">{{ $complaint->order->created_at->format('M d, Y') }}</span>
                                        </div>
                                    @else
                                        <div class="alert alert-light border-0 bg-white rounded-3 p-3">
                                            <i class="fas fa-info-circle me-2 text-secondary"></i>
                                            Order information not available
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Seller & Timeline -->
                    <div class="col-md-6">
                        <div class="card border-0 bg-light rounded-3 h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-2 p-2 me-2" style="background-color: #e8f0e8;">
                                        <i class="fas fa-store-alt" style="color: #8a9c6a;"></i>
                                    </div>
                                    <h5 class="fw-bold text-dark mb-0">Seller & Timeline</h5>
                                </div>
                                <div class="ps-4">
                                    @if($complaint->order && $complaint->order->seller)
                                        <div class="mb-3">
                                            <span class="text-secondary d-block">Seller</span>
                                            <span class="fw-medium text-dark">
                                                {{ $complaint->order->seller->name }}
                                                @if($complaint->order->seller->email)
                                                    <br><small class="text-secondary">{{ $complaint->order->seller->email }}</small>
                                                @endif
                                            </span>
                                        </div>
                                    @endif

                                    @if($complaint->handler)
                                        <div class="mb-3">
                                            <span class="text-secondary d-block">Complaint Handler</span>
                                            <span class="fw-medium text-dark">
                                                {{ $complaint->handler->name }}
                                                <br><small class="text-secondary">Seller Representative</small>
                                            </span>
                                        </div>
                                    @endif

                                    <div class="mb-3">
                                        <span class="text-secondary d-block">Created</span>
                                        <span
                                            class="fw-medium text-dark">{{ $complaint->created_at->format('F d, Y H:i') }}</span>
                                    </div>

                                    @if($complaint->updated_at->gt($complaint->created_at))
                                        <div>
                                            <span class="text-secondary d-block">Last Updated</span>
                                            <span
                                                class="fw-medium text-dark">{{ $complaint->updated_at->format('F d, Y H:i') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Complaint Message (green accent on left border) -->
                <div class="mb-5">
                    <h5 class="fw-bold mb-3 d-flex align-items-center">
                        <div class="rounded-2 p-2 me-2" style="background-color: #e8f0e8;">
                            <i class="fas fa-comment-dots" style="color: #8a9c6a;"></i>
                        </div>
                        Your Complaint Message
                    </h5>
                    <div class="card border-start border-3 rounded-3"
                        style="border-left-color: #8a9c6a !important; background-color: #f8f9fa;">
                        <div class="card-body p-4">
                            <p class="mb-0 text-dark">{{ $complaint->complaint_message }}</p>
                        </div>
                    </div>
                </div>

                <!-- Seller Response Section -->
                @if($complaint->seller_response)
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3 d-flex align-items-center">
                            <div class="rounded-2 p-2 me-2" style="background-color: #e8f0e8;">
                                <i class="fas fa-store-alt" style="color: #8a9c6a;"></i>
                            </div>
                            Seller's Response
                        </h5>
                        <div class="card border-0 bg-light rounded-3">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-start">
                                    <div class="rounded-2 p-2 me-3" style="background-color: #e8f0e8;">
                                        <i class="fas fa-user-tie" style="color: #8a9c6a;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-3 text-dark">{{ $complaint->seller_response }}</p>
                                        <div class="d-flex justify-content-between align-items-center text-secondary">
                                            <small>
                                                <i class="fas fa-clock me-1"></i>
                                                @if($complaint->handler)
                                                    Responded by {{ $complaint->handler->name }} on
                                                @else
                                                    Responded on
                                                @endif
                                                {{ $complaint->updated_at->format('F d, Y H:i') }}
                                            </small>
                                            @if($complaint->status === 'resolved')
                                                <span class="badge bg-white text-dark rounded-pill px-3 py-1 border">
                                                    <i class="fas fa-check me-1" style="color: #8a9c6a;"></i>Resolved
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Status-specific placeholder messages with green icons -->
                    @php
                        $placeholders = [
                            'rejected' => ['icon' => 'fa-times-circle', 'title' => 'Complaint Rejected', 'msg' => 'The seller has reviewed your complaint and found it invalid.'],
                            'closed' => ['icon' => 'fa-lock', 'title' => 'Complaint Closed', 'msg' => 'This complaint has been closed. No further action is required.'],
                            'in_progress' => ['icon' => 'fa-spinner', 'title' => 'Under Investigation', 'msg' => 'The seller is currently investigating your complaint. You\'ll receive a response shortly.'],
                            'pending' => ['icon' => 'fa-clock', 'title' => 'Awaiting Seller Response', 'msg' => 'Your complaint has been submitted to the seller. They typically respond within 24-48 hours.'],
                        ];
                        $current = $placeholders[$complaint->status] ?? $placeholders['pending'];
                    @endphp
                    <div class="alert border-0 bg-light rounded-3 d-flex align-items-center p-4 mb-4">
                        <div class="rounded-2 p-2 me-3" style="background-color: #e8f0e8;">
                            <i class="fas {{ $current['icon'] }}" style="color: #8a9c6a;"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">{{ $current['title'] }}</h6>
                            <p class="text-secondary mb-0">{{ $current['msg'] }}</p>
                        </div>
                    </div>
                @endif

                <!-- Footer with actions (green button and subtle hint) -->
                <div class="d-flex flex-wrap justify-content-between align-items-center pt-4 border-top"
                    style="border-color: #e9ecef !important;">
                    <a href="{{ route('complaints.index') }}" class="btn btn-outline-dark rounded-pill px-4">
                        <i class="fas fa-list me-2"></i>View All Complaints
                    </a>

                    @if(in_array($complaint->status, ['pending', 'in_progress']))
                        <div class="text-end">
                            <small class="text-secondary d-block mb-1">
                                <i class="fas fa-info-circle me-1" style="color: #8a9c6a;"></i>
                                @if($complaint->status === 'in_progress')
                                    Seller is currently reviewing your complaint
                                @else
                                    Expected seller response within 24-48 hours
                                @endif
                            </small>
                            <span class="badge bg-white text-dark rounded-pill px-3 py-2 border">
                                <i class="fas fa-hourglass-half me-1" style="color: #8a9c6a;"></i>
                                @if($complaint->status === 'in_progress')
                                    Under Review
                                @else
                                    Waiting for Response
                                @endif
                            </span>
                        </div>
                    @elseif($complaint->status === 'resolved')
                        <div class="text-end">
                            <span class="badge bg-white text-dark rounded-pill px-3 py-2 border">
                                <i class="fas fa-check-circle me-1" style="color: #8a9c6a;"></i>Resolved Successfully
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Help info (matching submit page) -->
        <div class="alert border-0 rounded-3 mt-4 p-4" style="background-color: #f8f9fa;">
            <div class="d-flex align-items-center">
                <div class="rounded-2 p-2 me-3" style="background-color: #e8f0e8;">
                    <i class="fas fa-question-circle" style="color: #8a9c6a;"></i>
                </div>
                <div>
                    <h6 class="fw-bold text-dark mb-2">Need Help?</h6>
                    <p class="text-secondary mb-0">
                        If you have questions about this complaint, please contact our support team.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Minimal custom CSS for status soft backgrounds -->
    <style>
        /* Status badge soft backgrounds (using theme's light green for some) */
        .bg-warning-soft {
            background-color: #fff3cd;
        }

        .bg-info-soft {
            background-color: #d1ecf1;
        }

        .bg-success-soft {
            background-color: #d4edda;
        }

        .bg-danger-soft {
            background-color: #f8d7da;
        }

        .text-warning-dark {
            color: #856404 !important;
        }

        .text-info-dark {
            color: #0c5460 !important;
        }

        .text-success-dark {
            color: #155724 !important;
        }

        .text-danger-dark {
            color: #721c24 !important;
        }

        /* Override for the green accent */
        .btn-outline-dark {
            border: 2px solid #dee2e6 !important;
            color: #495057 !important;
            transition: all 0.2s ease;
        }

        .btn-outline-dark:hover {
            background-color: #e9ecef !important;
            border-color: #ced4da !important;
            color: #212529 !important;
        }

        .rounded-3 {
            border-radius: 0.5rem !important;
        }

        .rounded-2 {
            border-radius: 0.375rem !important;
        }

        .card {
            transition: box-shadow 0.2s ease;
        }

        .card:hover {
            box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.05) !important;
        }

        .badge {
            font-weight: 500;
            letter-spacing: 0.02em;
        }
    </style>
@endsection