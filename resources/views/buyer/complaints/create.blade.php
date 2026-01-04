@extends('layouts.main')

@section('title', 'Submit Complaint')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-7">
                <!-- Header -->
                <div class="text-center mb-5">
                    <a href="{{ route('complaints.index') }}" class="btn btn-matcha-outline rounded-pill mb-4 px-4">
                        <i class="fas fa-arrow-left me-2"></i>Back to Complaints
                    </a>
                    <h1 class="fw-bold text-dark mb-3">Submit New Complaint</h1>
                    <p class="text-matcha lead mb-0">Describe your issue and we'll help resolve it promptly</p>
                </div>

                <!-- Form Card -->
                <div class="card border-matcha-light border-2 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-header bg-matcha-light border-0 py-4 px-5">
                        <div class="d-flex align-items-center">
                            <div class="bg-matcha rounded-3 p-3 me-3">
                                <i class="fas fa-exclamation-circle text-white fs-5"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-1">Complaint Form</h4>
                                <p class="text-matcha mb-0">All fields marked with * are required</p>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('complaints.store') }}" method="POST">
                            @csrf

                            <!-- Order ID Field -->
                            <div class="mb-5">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-matcha-light rounded-3 p-2 me-2">
                                        <i class="fas fa-shopping-basket text-matcha"></i>
                                    </div>
                                    <label for="order_id" class="form-label fw-bold fs-6 mb-0">Related Order
                                        (Optional)</label>
                                </div>
                                <select
                                    class="form-select form-select-lg rounded-3 border-matcha-light @error('order_id') is-invalid @enderror"
                                    id="order_id" name="order_id">
                                    <option value="">Select an order (optional)</option>
                                    @foreach($orders as $order)
                                        <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>
                                            Order #{{ $order->id }} • {{ $order->created_at->format('M d, Y') }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text text-matcha mt-2">
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
                                    <div class="bg-matcha-light rounded-3 p-2 me-2">
                                        <i class="fas fa-tag text-matcha"></i>
                                    </div>
                                    <label for="problem_type" class="form-label fw-bold fs-6 mb-0">Problem Type *</label>
                                </div>
                                <select
                                    class="form-select form-select-lg rounded-3 border-matcha-light @error('problem_type') is-invalid @enderror"
                                    id="problem_type" name="problem_type" required>
                                    <option value="">Select the type of issue</option>
                                    @foreach($problemTypes as $key => $label)
                                        <option value="{{ $key }}" {{ old('problem_type') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text text-matcha mt-2">
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
                                    <div class="bg-matcha-light rounded-3 p-2 me-2">
                                        <i class="fas fa-edit text-matcha"></i>
                                    </div>
                                    <label for="complaint_message" class="form-label fw-bold fs-6 mb-0">Complaint Details
                                        *</label>
                                </div>
                                <div class="position-relative">
                                    <textarea
                                        class="form-control rounded-3 border-matcha-light @error('complaint_message') is-invalid @enderror"
                                        id="complaint_message" name="complaint_message" rows="6"
                                        placeholder="Describe your issue in detail. Include relevant information such as dates, times, and any attempts to resolve the issue..."
                                        required>{{ old('complaint_message') }}</textarea>
                                    <div class="form-text text-matcha mt-2">
                                        <i class="fas fa-lightbulb me-1"></i>
                                        Be specific for faster resolution. Minimum 50 characters recommended.
                                    </div>
                                    <div class="text-end mt-2">
                                        <small class="text-matcha" id="charCount">0 characters</small>
                                    </div>
                                </div>
                                @error('complaint_message')
                                    <div class="invalid-feedback d-flex align-items-center mt-2">
                                        <i class="fas fa-exclamation-triangle me-2"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Action Buttons -->
                            <div
                                class="d-flex flex-column flex-md-row justify-content-between align-items-center pt-4 border-top border-matcha-light">
                                <a href="{{ route('complaints.index') }}"
                                    class="btn btn-matcha-outline rounded-pill px-4 mb-3 mb-md-0 w-100 w-md-auto">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                                <button type="submit"
                                    class="btn btn-matcha-outline rounded-pill px-4 mb-3 mb-md-0 w-100 w-md-auto">
                                    <i class="fas fa-paper-plane me-2"></i>Submit Complaint
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Help Information -->
                <div class="alert border-0 bg-matcha-light rounded-4 mt-4 p-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-matcha rounded-3 p-2 me-3">
                            <i class="fas fa-question-circle text-white"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-2">Need Help?</h6>
                            <p class="text-matcha mb-0">
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
        :root {
            --matcha: rgb(138, 166, 106);
            --matcha-dark: #689F38;
            --matcha-light: #DCEDC8;
            --matcha-lighter: #F1F8E9;
        }

        .text-matcha {
            color: var(--matcha-dark) !important;
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

        .border-matcha-light {
            border-color: var(--matcha-light) !important;
        }

        .btn-matcha-outline {
            background-color: transparent !important;
            border: 2px solid var(--matcha) !important;
            color: var(--matcha-dark) !important;
            transition: all 0.3s ease;
        }

        .btn-matcha-outline:hover {
            background-color: var(--matcha) !important;
            color: white !important;
            transform: translateY(-2px);
        }

        .btn-matcha-gradient {
            background: linear-gradient(135deg, var(--matcha) 0%, var(--matcha-dark) 100%) !important;
            border: none !important;
            color: white !important;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(139, 195, 74, 0.3);
        }

        .btn-matcha-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(139, 195, 74, 0.4);
        }

        .form-select-lg,
        .form-control {
            border: 2px solid var(--matcha-light) !important;
            transition: all 0.3s ease;
        }

        .form-select-lg:focus,
        .form-control:focus {
            border-color: var(--matcha) !important;
            box-shadow: 0 0 0 0.25rem rgba(139, 195, 74, 0.25);
        }

        .rounded-3 {
            border-radius: 0.75rem !important;
        }

        .rounded-4 {
            border-radius: 1rem !important;
        }

        textarea {
            resize: none;
        }

        .lead {
            font-size: 1.1rem;
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
                    charCount.className = 'text-matcha';
                } else if (length < 200) {
                    charCount.className = 'text-success';
                } else {
                    charCount.className = 'text-matcha-dark';
                }
            });

            // Trigger on load for existing content
            textarea.dispatchEvent(new Event('input'));
        });
    </script>
@endsection