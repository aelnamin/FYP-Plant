@extends('layouts.main')

@section('title', 'Submit Complaint')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-7">
                <!-- Header -->
                <div class="text-center mb-5">
                    <a href="{{ route('complaints.index') }}" class="btn btn-outline-dark rounded-pill mb-4 px-4">
                        <i class="fas fa-arrow-left me-2"></i>Back to Complaints
                    </a>
                    <h1 class="fw-bold text-dark mb-3">Submit New Complaint</h1>
                    <p class="text-secondary">Describe your issue and we'll help resolve it promptly</p>
                </div>

                <!-- Form Card -->
                <div class="card border-light shadow-sm rounded-3 overflow-hidden">
                    <div class="card-header bg-light border-0 py-4 px-5">
                        <div class="d-flex align-items-center">
                            <div class="rounded-3 p-3 me-3" style="background-color: #e8f0e8;">
                                <i class="fas fa-exclamation-circle" style="color: #8a9c6a; font-size: 1.2rem;"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold text-dark mb-1">Complaint Form</h4>
                                <p class="text-secondary mb-0">All fields marked with * are required</p>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('complaints.store') }}" method="POST">
                            @csrf

                            <!-- Order ID Field -->
                            <div class="mb-5">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-2 p-2 me-2" style="background-color: #e8f0e8;">
                                        <i class="fas fa-shopping-basket" style="color: #8a9c6a;"></i>
                                    </div>
                                    <label for="order_id" class="form-label fw-bold text-dark mb-0">Related Order
                                        (Optional)</label>
                                </div>
                                <select class="form-select form-select-lg rounded-3 @error('order_id') is-invalid @enderror"
                                    id="order_id" name="order_id" style="border-color: #e9ecef !important;">
                                    <option value="">Select an order (optional)</option>
                                    @foreach($orders as $order)
                                        <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>
                                            Order #{{ $order->id }} • {{ $order->created_at->format('M d, Y') }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text text-secondary mt-2">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Select if your complaint is about a specific purchase
                                </div>
                                @error('order_id')
                                    <div class="invalid-feedback d-flex align-items-center mt-2">
                                        <i class="fas fa-exclamation-triangle me-2"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Problem Type Field -->
                            <div class="mb-5">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-2 p-2 me-2" style="background-color: #e8f0e8;">
                                        <i class="fas fa-tag" style="color: #8a9c6a;"></i>
                                    </div>
                                    <label for="problem_type" class="form-label fw-bold text-dark mb-0">Problem Type
                                        *</label>
                                </div>
                                <select
                                    class="form-select form-select-lg rounded-3 @error('problem_type') is-invalid @enderror"
                                    id="problem_type" name="problem_type" required
                                    style="border-color: #e9ecef !important;">
                                    <option value="">Select the type of issue</option>
                                    @foreach($problemTypes as $key => $label)
                                        <option value="{{ $key }}" {{ old('problem_type') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text text-secondary mt-2">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Choose the category that best describes your issue
                                </div>
                                @error('problem_type')
                                    <div class="invalid-feedback d-flex align-items-center mt-2">
                                        <i class="fas fa-exclamation-triangle me-2"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Complaint Details Field -->
                            <div class="mb-5">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-2 p-2 me-2" style="background-color: #e8f0e8;">
                                        <i class="fas fa-edit" style="color: #8a9c6a;"></i>
                                    </div>
                                    <label for="complaint_message" class="form-label fw-bold text-dark mb-0">Complaint
                                        Details *</label>
                                </div>
                                <div class="position-relative">
                                    <textarea
                                        class="form-control rounded-3 @error('complaint_message') is-invalid @enderror"
                                        id="complaint_message" name="complaint_message" rows="6"
                                        placeholder="Describe your issue in detail. Include relevant information such as dates, times, and any attempts to resolve the issue..."
                                        required
                                        style="border-color: #e9ecef !important;">{{ old('complaint_message') }}</textarea>
                                    <div class="form-text text-secondary mt-2">
                                        <i class="fas fa-lightbulb me-1"></i>
                                        Be specific for faster resolution. Minimum 50 characters recommended.
                                    </div>
                                    <div class="text-end mt-2">
                                        <small class="text-secondary" id="charCount">0 characters</small>
                                    </div>
                                </div>
                                @error('complaint_message')
                                    <div class="invalid-feedback d-flex align-items-center mt-2">
                                        <i class="fas fa-exclamation-triangle me-2"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center pt-4 border-top"
                                style="border-color: #e9ecef !important;">
                                <a href="{{ route('complaints.index') }}"
                                    class="btn btn-outline-dark rounded-pill px-4 mb-3 mb-md-0 w-100 w-md-auto">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                                <button type="submit" class="btn rounded-pill px-4 mb-3 mb-md-0 w-100 w-md-auto border-0"
                                    style="background-color: #8a9c6a; color: white;">
                                    <i class="fas fa-paper-plane me-2"></i>Submit Complaint
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Help Information -->
                <div class="alert border-0 rounded-3 mt-4 p-4" style="background-color: #f8f9fa;">
                    <div class="d-flex align-items-center">
                        <div class="rounded-2 p-2 me-3" style="background-color: #e8f0e8;">
                            <i class="fas fa-question-circle" style="color: #8a9c6a;"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-2">Need Help?</h6>
                            <p class="text-secondary mb-0">
                                Our support team typically responds within 24-48 hours. For urgent matters,
                                please include "URGENT" in your complaint message.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Styles -->
    <style>
        .card {
            transition: all 0.3s ease;
            border: 1px solid #e9ecef !important;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        }

        .rounded-3 {
            border-radius: 0.75rem !important;
        }

        .btn:hover {
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }

        .btn[style*="background-color: #8a9c6a"]:hover {
            background-color: #7a8b5a !important;
            box-shadow: 0 4px 12px rgba(138, 156, 106, 0.2);
        }

        .btn-outline-dark:hover {
            background-color: #212529;
            color: white;
        }

        .form-select-lg,
        .form-control {
            border: 1px solid #e9ecef !important;
            transition: all 0.3s ease;
        }

        .form-select-lg:focus,
        .form-control:focus {
            border-color: #8a9c6a !important;
            box-shadow: 0 0 0 0.25rem rgba(138, 156, 106, 0.25);
        }

        /* Better text contrast */
        .text-dark {
            color: #212529 !important;
        }

        .text-secondary {
            color: #6c757d !important;
        }

        /* Background colors */
        .bg-light {
            background-color: #f8f9fa !important;
        }
    </style>

    <!-- Character Counter Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const textarea = document.getElementById('complaint_message');
            const charCount = document.getElementById('charCount');

            textarea.addEventListener('input', function () {
                const length = this.value.length;
                charCount.textContent = length + ' characters';

                if (length < 50) {
                    charCount.style.color = '#dc3545';
                } else if (length < 200) {
                    charCount.style.color = '#8a9c6a';
                } else {
                    charCount.style.color = '#6c757d';
                }
            });

            // Trigger on load for existing content
            textarea.dispatchEvent(new Event('input'));
        });
    </script>
@endsection