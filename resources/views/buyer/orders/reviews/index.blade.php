@extends('layouts.main')

@section('title', 'My Reviews')

@section('content')
    <div class="container py-5">
        <!-- Page Header -->
        <div class="text-center mb-5">
            <div class="d-flex justify-content-center align-items-center mb-3">
                <div class="p-3 rounded-circle" style="background: linear-gradient(135deg, #8a9c6a20, #d4e6b540);">
                    <i class="fas fa-star fa-2x" style="color: #8a9c6a;"></i>
                </div>
            </div>
            <h1 class="fw-bold mb-3" style="color: #2c3e50;">My Reviews</h1>
            <p class="text-secondary mb-0">Manage and update your product reviews</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert"
                style="background: linear-gradient(135deg, #e8f5e9, #c8e6c9); border-left: 4px solid #4caf50;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle me-2" style="color: #4caf50;"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($reviews->count() > 0)
            <div class="row g-4">
                @foreach($reviews as $review)
                    <div class="col-lg-6 col-xl-4">
                        <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
                            <!-- Product Image Section -->
                            @if($review->product && $review->product->images->first())
                                <div class="position-relative">
                                    <img src="{{ asset('images/' . $review->product->images->first()->image_path) }}"
                                        class="card-img-top" alt="{{ $review->product->product_name }}"
                                        style="height: 200px; object-fit: cover;">
                                    <div class="position-absolute top-0 end-0 m-3">
                                        <span class="badge rounded-pill px-3 py-2"
                                            style="background: rgba(255, 255, 255, 0.9); color: #8a9c6a;">
                                            <i class="fas fa-star me-1"></i>{{ $review->rating }}/5
                                        </span>
                                    </div>
                                </div>
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center"
                                    style="height: 200px; background: linear-gradient(135deg, #f5f5f5, #e8f0e8);">
                                    <i class="fas fa-image fa-3x" style="color: #8a9c6a40;"></i>
                                </div>
                            @endif

                            <!-- Card Body -->
                            <div class="card-body d-flex flex-column">
                                <div class="mb-3">
                                    <h5 class="fw-bold mb-2" style="color: #2c3e50;">
                                        {{ $review->product->product_name ?? 'Unknown Product' }}
                                    </h5>

                                    <!-- Rating Stars -->
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    <i class="fas fa-star me-1" style="color: #ffc107;"></i>
                                                @else
                                                    <i class="far fa-star me-1" style="color: #e0e0e0;"></i>
                                                @endif
                                            @endfor
                                            <span class="ms-2 text-muted small">{{ $review->rating }}.0</span>
                                        </div>
                                    </div>

                                    <!-- Review Comment -->
                                    <div class="mb-4">
                                        <p class="mb-0 text-secondary" style="line-height: 1.6;">
                                            {{ $review->comment ?? 'No comment provided.' }}
                                        </p>
                                    </div>

                                    <!-- Product Info -->
                                    @if($review->product && $review->product->seller)
                                        <div class="d-flex align-items-center mb-3 p-2 rounded" style="background-color: #f8f9fa;">
                                            <i class="fas fa-store me-2" style="color: #8a9c6a;"></i>
                                            <span class="small">
                                                {{ $review->product->seller->business_name ?? $review->product->seller->name }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Card Footer -->
                                <div class="mt-auto pt-3 border-top">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <small class="text-muted">
                                                <i class="far fa-calendar me-1"></i>
                                                {{ $review->created_at->format('M d, Y') }}
                                            </small>
                                            @if(!$review->updated_at->eq($review->created_at))
                                                <small class="text-muted ms-2">
                                                    <i class="fas fa-edit me-1"></i>Edited
                                                </small>
                                            @endif
                                        </div>
                                        <a href="{{ route('buyer.reviews.create', $review->product_id) }}"
                                            class="btn btn-sm px-3 rounded-pill border-0"
                                            style="background: linear-gradient(135deg, #8a9c6a, #7a8b5a); color: white;">
                                            <i class="fas fa-edit me-1"></i>Edit
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination or additional info -->
            <div class="mt-5 text-center">
                <p class="text-muted">
                    Showing {{ $reviews->count() }} {{ Str::plural('review', $reviews->count()) }}
                </p>
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-5 my-5">
                <div class="mb-4">
                    <div class="mx-auto mb-3" style="width: 100px; height: 100px; 
                                background: linear-gradient(135deg, #f8f9fa, #e8f0e8); 
                                border-radius: 50%; 
                                display: flex; 
                                align-items: center; 
                                justify-content: center;">
                        <i class="fas fa-star fa-3x" style="color: #8a9c6a40;"></i>
                    </div>
                </div>
                <h4 class="fw-bold mb-3" style="color: #2c3e50;">No Reviews Yet</h4>
                <p class="text-muted mb-4" style="max-width: 400px; margin: 0 auto;">
                    You haven't submitted any reviews for your purchases.
                    Share your experience to help other shoppers!
                </p>
                <a href="{{ route('buyer.orders') }}" class="btn px-4 rounded-pill border-0"
                    style="background: linear-gradient(135deg, #8a9c6a, #7a8b5a); color: white;">
                    <i class="fas fa-shopping-bag me-2"></i>View Your Orders
                </a>
            </div>
        @endif
    </div>

    <!-- Custom Styles -->
    <style>
        /* Smooth transitions */
        .card {
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(138, 156, 106, 0.15) !important;
            border-color: #8a9c6a40;
        }

        /* Custom button styles */
        .btn {
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(138, 156, 106, 0.3);
        }

        /* Rating stars animation */
        .fa-star {
            transition: transform 0.2s ease;
        }

        .fa-star:hover {
            transform: scale(1.2);
        }

        /* Gradient backgrounds */
        .gradient-bg {
            background: linear-gradient(135deg, #8a9c6a20, #d4e6b540);
        }

        /* Custom scrollbar for review text */
        .review-text {
            max-height: 100px;
            overflow-y: auto;
        }

        .review-text::-webkit-scrollbar {
            width: 4px;
        }

        .review-text::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .review-text::-webkit-scrollbar-thumb {
            background: #8a9c6a;
            border-radius: 10px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .container {
                padding-left: 20px;
                padding-right: 20px;
            }

            .card {
                margin-bottom: 20px;
            }
        }

        /* Color harmony variables */
        :root {
            --primary: #8a9c6a;
            --primary-dark: #7a8b5a;
            --primary-light: #d4e6b5;
            --primary-bg: #f8f9fa;
            --text-dark: #2c3e50;
            --text-light: #6c757d;
            --border-color: #e9ecef;
            --star-filled: #ffc107;
            --star-empty: #e0e0e0;
        }

        /* Applying color variables */
        .text-primary-custom {
            color: var(--primary) !important;
        }

        .bg-primary-custom {
            background-color: var(--primary) !important;
        }

        .border-primary-custom {
            border-color: var(--primary) !important;
        }
    </style>

    <!-- JavaScript for enhanced interactions -->
    <script>
        // Add animation to cards on load
        document.addEventListener('DOMContentLoaded', function () {
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });

        // Add hover effect to edit buttons
        const editButtons = document.querySelectorAll('.btn');
        editButtons.forEach(button => {
            button.addEventListener('mouseenter', function () {
                this.style.transform = 'translateY(-2px)';
            });

            button.addEventListener('mouseleave', function () {
                this.style.transform = 'translateY(0)';
            });
        });
    </script>
@endsection