@extends('layouts.sellers-main')

@section('title', 'Complaint Details')

@section('content')
    <div class="container py-5">
        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('sellers.complaints.index') }}" class="btn btn-outline-primary rounded-pill px-4">
                <i class="fas fa-arrow-left me-2"></i>Back to Complaints
            </a>
        </div>

        @php
    $sellerId = auth()->user()->seller?->id;
@endphp


        <!-- Main Card -->
        <div class="card border-primary-200 border-2 shadow-lg rounded-4 overflow-hidden">
            <!-- Header -->
            <div class="card-header bg-primary-50 border-0 py-4 px-5">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 fw-bold text-gray-900 mb-1">Complaint Details</h1>
                        <p class="text-primary-700 mb-0">Complaint ID: #{{ $complaint->complaint_id }}</p>
                        <small class="text-secondary">Submitted on
                            {{ $complaint->created_at->format('M d, Y H:i') }}</small>
                    </div>
                    @php
                        $statusColors = [
                            'pending' => ['bg' => 'bg-warning-soft', 'text' => 'text-warning-800', 'icon' => 'fas fa-clock'],
                            'in_progress' => ['bg' => 'bg-info-soft', 'text' => 'text-info-800', 'icon' => 'fas fa-spinner'],
                            'resolved' => ['bg' => 'bg-success-soft', 'text' => 'text-success-800', 'icon' => 'fas fa-check-circle'],
                            'closed' => ['bg' => 'bg-gray-200', 'text' => 'text-gray-700', 'icon' => 'fas fa-lock'],
                            'rejected' => ['bg' => 'bg-error-soft', 'text' => 'text-error-800', 'icon' => 'fas fa-times-circle']
                        ];
                        $status = $statusColors[$complaint->status] ?? ['bg' => 'bg-gray-200', 'text' => 'text-gray-700', 'icon' => 'fas fa-question-circle'];
                    @endphp
                    <span class="badge {{ $status['bg'] }} {{ $status['text'] }} rounded-pill px-4 py-2 fs-6">
                        <i class="{{ $status['icon'] }} me-2"></i>
                        {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                    </span>
                </div>
            </div>

            <div class="card-body p-4 p-md-5">
                <!-- Information Cards -->
                <div class="row g-4 mb-5">
                    <!-- Buyer & Order Information -->
                    <div class="col-md-6">
                        <div class="card border-primary-200 border-2 rounded-4 h-100 hover-lift">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary-100 rounded-3 p-3 me-3">
                                        <i class="fas fa-user text-primary-700 fs-5"></i>
                                    </div>
                                    <h5 class="fw-bold text-gray-900 mb-0">Buyer & Order Information</h5>
                                </div>
                                <div class="ps-5">
                                    @if($complaint->buyer)
                                        <div class="mb-3">
                                            <span class="text-primary-700 d-block">Buyer Name</span>
                                            <span class="fw-semibold fs-6 text-gray-900">{{ $complaint->buyer->name }}</span>
                                            <br>
                                            <small class="text-primary-600">{{ $complaint->buyer->email }}</small>
                                        </div>
                                    @endif

                                    @if($complaint->order)
                                        <div class="mb-3">
                                            <span class="text-primary-700 d-block">Order ID</span>
                                            <span class="fw-medium text-gray-800">#{{ $complaint->order->id }}</span>
                                        </div>
                                        <div class="mb-3">
                                            <span class="text-primary-700 d-block">Order Date</span>
                                            <span class="fw-medium text-gray-800">
                                                {{ $complaint->order->created_at->format('F d, Y') }}
                                            </span>
                                        </div>
                                        <div class="mb-3">
                                            <span class="text-primary-700 d-block">Problem Type</span>
                                            <span class="fw-semibold text-gray-900">{{ $complaint->problem_type_label }}</span>
                                        </div>
                                    @else
                                        <div class="alert alert-warning border-0 bg-warning-50 rounded-3 p-3">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Order information not available
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Complaint Timeline -->
                    <div class="col-md-6">
                        <div class="card border-primary-200 border-2 rounded-4 h-100 hover-lift">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary-100 rounded-3 p-3 me-3">
                                        <i class="fas fa-history text-primary-700 fs-5"></i>
                                    </div>
                                    <h5 class="fw-bold text-gray-900 mb-0">Complaint Timeline</h5>
                                </div>
                                <div class="ps-5">
                                    <div class="mb-3">
                                        <span class="text-primary-700 d-block">Submitted</span>
                                        <span class="fw-medium text-gray-800">
                                            {{ $complaint->created_at->format('F d, Y') }} at
                                            {{ $complaint->created_at->format('g:i A') }}
                                        </span>
                                    </div>

                                    @if($complaint->updated_at->gt($complaint->created_at))
                                        <div class="mb-3">
                                            <span class="text-primary-700 d-block">Last Updated</span>
                                            <span class="fw-medium text-gray-800">
                                                {{ $complaint->updated_at->format('F d, Y') }} at
                                                {{ $complaint->updated_at->format('g:i A') }}
                                            </span>
                                        </div>
                                    @endif

                                    @if($complaint->handler)
                                        <div class="mb-3">
                                            <span class="text-primary-700 d-block">Handled By</span>
                                            <span class="fw-medium text-gray-800">
                                                {{ $complaint->handler->name }}
                                                @if($complaint->handler->id == $sellerId)
                                                    <span class="badge bg-primary-100 text-primary-700 ms-2">You</span>
                                                @endif
                                            </span>
                                        </div>
                                    @endif

                                    @if($complaint->seller_response)
                                        <div>
                                            <span class="text-primary-700 d-block">Response Sent</span>
                                            <span class="fw-medium text-gray-800">
                                                {{ $complaint->updated_at->format('F d, Y') }} at
                                                {{ $complaint->updated_at->format('g:i A') }}
                                            </span>
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
                        Buyer's Complaint
                    </h5>
                    <div class="card border-start border-3 border-warning-400 rounded-end-4 bg-warning-50">
                        <div class="card-body p-4">
                            <p class="mb-0 fs-6 lh-lg text-gray-800">{{ $complaint->complaint_message }}</p>
                        </div>
                    </div>
                </div>

                <!-- Seller Response Section -->
                <div id="respond-section">
                @if($complaint->seller_response && $complaint->status === 'resolved')
                        <!-- Existing Response -->
                        <div class="mb-5">
                            <h5 class="fw-bold mb-3 d-flex align-items-center">
                                <i class="fas fa-reply text-success-600 me-2"></i>
                                Your Response
                            </h5>
                            <div class="card border-start border-3 border-success-400 rounded-end-4 bg-success-soft">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-primary-100 rounded-3 p-3 me-3">
                                            <i class="fas fa-user-tie text-primary-700 fs-5"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="mb-3 fs-6 text-gray-800">{{ $complaint->seller_response }}</p>
                                            <div class="d-flex justify-content-between align-items-center text-success-700">
                                                <small>
                                                    <i class="fas fa-clock me-1"></i>
                                                    Sent on {{ $complaint->updated_at->format('F d, Y') }} at
                                                    {{ $complaint->updated_at->format('g:i A') }}
                                                </small>
                                                <span class="badge bg-success-100 text-success-800 rounded-pill px-3 py-1">
                                                    <i class="fas fa-check me-1"></i>Resolved
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($complaint->status == 'pending' || ($complaint->status == 'in_progress' && (!$complaint->handled_by || $complaint->handled_by == $sellerId)))
                        <!-- Response Form -->
                        <div class="mb-5">
                            <h5 class="fw-bold mb-3 d-flex align-items-center">
                                <i class="fas fa-reply text-primary-600 me-2"></i>
                                Respond to Complaint
                            </h5>
                            <div class="card border-0 bg-primary-50 rounded-4">
                                <div class="card-body p-4">
                                    <form action="{{ route('sellers.complaints.respond', $complaint) }}" method="POST">
                                        @csrf
                                        @method('PATCH')

                                        <!-- Status Selection -->
                                        <div class="mb-4">
                                            <label class="form-label fw-semibold text-gray-900">Resolution Status</label>
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <div
                                                        class="form-check card border-primary-200 border-2 rounded-4 p-3 h-100">
                                                        <input class="form-check-input" type="radio" name="status"
                                                            value="resolved" id="resolved" checked>
                                                        <label class="form-check-label" for="resolved">
                                                            <div class="d-flex align-items-center mb-2">
                                                                <div class="bg-success-soft rounded-3 p-2 me-2">
                                                                    <i class="fas fa-check-circle text-success-800"></i>
                                                                </div>
                                                                <span class="fw-bold">Resolve Complaint</span>
                                                            </div>
                                                            <small class="text-primary-600">Mark as resolved with your
                                                                response</small>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div
                                                        class="form-check card border-primary-200 border-2 rounded-4 p-3 h-100">
                                                        <input class="form-check-input" type="radio" name="status"
                                                            value="in_progress" id="in_progress">
                                                        <label class="form-check-label" for="in_progress">
                                                            <div class="d-flex align-items-center mb-2">
                                                                <div class="bg-info-soft rounded-3 p-2 me-2">
                                                                    <i class="fas fa-spinner text-info-800"></i>
                                                                </div>
                                                                <span class="fw-bold">Set to In Progress</span>
                                                            </div>
                                                            <small class="text-primary-600">Acknowledge and continue
                                                                investigation</small>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div
                                                        class="form-check card border-primary-200 border-2 rounded-4 p-3 h-100">
                                                        <input class="form-check-input" type="radio" name="status"
                                                            value="rejected" id="rejected">
                                                        <label class="form-check-label" for="rejected">
                                                            <div class="d-flex align-items-center mb-2">
                                                                <div class="bg-error-soft rounded-3 p-2 me-2">
                                                                    <i class="fas fa-times-circle text-error-800"></i>
                                                                </div>
                                                                <span class="fw-bold">Reject Complaint</span>
                                                            </div>
                                                            <small class="text-primary-600">Reject if complaint is
                                                                invalid</small>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Response Textarea -->
                                        <div class="mb-4">
                                            <label for="seller_response" class="form-label fw-semibold text-gray-900">
                                                Your Response <span class="text-danger">*</span>
                                            </label>
                                            <textarea name="seller_response" id="seller_response"
                                                class="form-control border-primary-200 border-2 rounded-4" rows="5"
                                                placeholder="Type your response to the buyer here..." required></textarea>
                                            <div class="form-text text-primary-600">
                                                <i class="fas fa-lightbulb me-1"></i>
                                                Be professional and address the buyer's concerns directly. This response will be
                                                sent to the buyer.
                                            </div>
                                        </div>

                                        <!-- Form Actions -->
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <small class="text-primary-600">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    Your response will be recorded and visible to the buyer
                                                </small>
                                            </div>
                                            <div class="text-end">
                                                <a href="{{ route('sellers.complaints.index') }}"
                                                    class="btn btn-outline-primary rounded-pill px-4 me-2">
                                                    Cancel
                                                </a>
                                                <button type="submit" class="btn btn-primary rounded-pill px-4">
                                                    <i class="fas fa-paper-plane me-2"></i>Submit Response
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- JavaScript for status handling -->
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const resolutionRadios = document.querySelectorAll('input[name="status"]');

                                const responseTextarea = document.getElementById('seller_response');

                                function updateResponsePlaceholder() {
                                    const selectedValue = document.querySelector('input[name="resolution_type"]:checked').value;
                                    let placeholder = 'Type your response to the buyer here...';

                                    switch (selectedValue) {
                                        case 'resolved':
                                            placeholder = 'Explain how the issue has been resolved and any actions taken...';
                                            break;
                                        case 'in_progress':
                                            placeholder = 'Acknowledge the complaint and explain what steps you are taking to investigate...';
                                            break;
                                        case 'rejected':
                                            placeholder = 'Politely explain why the complaint cannot be accepted...';
                                            break;
                                    }

                                    responseTextarea.placeholder = placeholder;
                                }

                                resolutionRadios.forEach(radio => {
                                    radio.addEventListener('change', updateResponsePlaceholder);
                                });

                                // Initialize placeholder
                                updateResponsePlaceholder();
                            });
                        </script>
                  @elseif($complaint->status == 'in_progress' && $complaint->handled_by != $sellerId)
                        <!-- Already being handled by another seller -->
                        <div class="alert border-0 bg-info-soft rounded-4 d-flex align-items-center p-4 mb-4">
                            <div class="bg-primary-100 rounded-3 p-3 me-3">
                                <i class="fas fa-user-friends text-primary-700 fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-gray-900 mb-1">Being Handled by Another Seller</h6>
                                <p class="text-primary-700 mb-0">
                                    This complaint is currently being handled by {{ $complaint->handler->name }}.
                                </p>
                            </div>
                        </div>
                    @elseif($complaint->status == 'rejected')
                        <!-- Rejected Status -->
                        <div class="alert border-0 bg-error-soft rounded-4 d-flex align-items-center p-4 mb-4">
                            <div class="bg-primary-100 rounded-3 p-3 me-3">
                                <i class="fas fa-times-circle text-primary-700 fs-5"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold text-gray-900 mb-1">Complaint Rejected</h6>
                                <p class="text-primary-700 mb-0">This complaint has been rejected as invalid.</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-between align-items-center mt-5 pt-4 border-top border-primary-200">
                    <a href="{{ route('sellers.complaints.index') }}" class="btn btn-outline-primary rounded-pill px-4">
                        <i class="fas fa-list me-2"></i>View All Complaints
                    </a>

                    @if(!$complaint->seller_response && ($complaint->status == 'pending' || (!$complaint->handled_by && $complaint->status == 'in_progress')))
                        <div class="text-end">
                            <a href="#respond-section" class="btn btn-primary rounded-pill px-4">
                                <i class="fas fa-reply me-2"></i>Respond Now
                            </a>
                        </div>
                    @elseif($complaint->status == 'resolved')
                        <div class="text-end">
                            <span class="badge bg-success-soft text-success-800 rounded-pill px-3 py-2">
                                <i class="fas fa-check-circle me-1"></i>Successfully Resolved
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

        .text-success-600 {
            color: var(--color-success-800) !important;
        }

        .text-warning-600 {
            color: var(--color-warning-800) !important;
        }

        .text-info-600 {
            color: var(--color-info-800) !important;
        }

        .text-error-600 {
            color: var(--color-error-800) !important;
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

        /* Border Colors for Status */
        .border-warning-400 {
            border-color: var(--color-warning-800) !important;
        }

        .border-success-400 {
            border-color: var(--color-success-800) !important;
        }

        /* Form Check Cards */
        .form-check .card {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .form-check .card:hover {
            border-color: var(--color-primary-500) !important;
            background-color: var(--color-primary-50) !important;
        }

        .form-check-input:checked+.card {
            border-color: var(--color-primary-600) !important;
            background-color: var(--color-primary-100) !important;
            box-shadow: 0 0 0 2px var(--color-primary-100);
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

        /* Form Controls */
        .form-control:focus {
            border-color: var(--color-primary-500) !important;
            box-shadow: 0 0 0 0.25rem rgba(110, 128, 85, 0.25) !important;
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