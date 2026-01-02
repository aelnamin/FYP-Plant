@extends('layouts.main')

@section('title', 'Leave Review - ' . $product->product_name)

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <!-- Header -->
                <div class="text-center mb-4">
                    <h2 class="fw-bold">Leave a Review</h2>
                    <p class="text-muted">Share your experience with {{ $product->product_name }}</p>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if($existingReview)
                    <div class="alert alert-info">
                        You already reviewed this product. Your rating: {{ $existingReview->rating }}/5
                    </div>
                @else
                    <!-- Product Card -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <img src="{{ $product->images->first() ? asset('images/' . $product->images->first()->image_path) : asset('images/default.png') }}"
                                    alt="{{ $product->product_name }}" class="rounded me-3" width="80" height="80"
                                    style="object-fit: cover;">
                                <div>
                                    <h5 class="fw-bold mb-1">{{ $product->product_name }}</h5>
                                    <p class="text-muted small mb-0">Please share your honest feedback</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Review Form -->
                    <form action="{{ route('buyer.reviews.store', $product->id) }}" method="POST" id="reviewForm">
                        @csrf

                        <!-- Star Rating Section -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <label class="form-label fw-bold mb-3 d-block">Your Rating</label>

                                <!-- Hidden input for form submission -->
                                <input type="hidden" name="rating" id="selectedRating" value="" required>

                                <!-- Clickable Stars -->
                                <div class="star-rating mb-3">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="star" data-value="{{ $i }}">
                                            <i class="bi bi-star-fill"></i>
                                        </span>
                                    @endfor
                                </div>

                                <!-- Rating Text -->
                                <div class="rating-feedback">
                                    <span id="ratingText" class="text-muted">Click on a star to rate</span>
                                    <span id="ratingValue" class="ms-2 fw-bold d-none">/5</span>
                                </div>
                            </div>
                        </div>

                        <!-- Comment Section -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <label for="comment" class="form-label fw-bold mb-2">Your Review (Optional)</label>
                                <textarea name="comment" id="comment" rows="4" class="form-control"
                                    placeholder="Tell us more about your experience with this product..."></textarea>
                                <div class="form-text">
                                    <i class="bi bi-info-circle"></i> What did you like or dislike? Would you recommend this
                                    product?
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary btn-lg px-4">
                                Submit Review
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <style>
        .star-rating {
            display: flex;
            justify-content: center;
            gap: 5px;
            font-size: 2.3rem;
            cursor: pointer;
        }

        .star {
            color: #e4e5e9;
            transition: all 0.2s ease;
            display: inline-block;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .star:hover {
            transform: scale(1.1);
        }

        .star.active {
            color: #ffc107;
        }

        .star.hover {
            color: #ffdb70;
        }

        .rating-feedback {
            min-height: 1.5rem;
        }

        #selectedRating:invalid~.star-rating {
            border-color: #dc3545;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const stars = document.querySelectorAll('.star');
            const selectedRatingInput = document.getElementById('selectedRating');
            const ratingText = document.getElementById('ratingText');
            const ratingValue = document.getElementById('ratingValue');
            const reviewForm = document.getElementById('reviewForm');

            // Rating descriptions
            const ratingDescriptions = {
                1: 'Poor - Not satisfied',
                2: 'Fair - Could be better',
                3: 'Good - Met expectations',
                4: 'Very Good - Exceeded expectations',
                5: 'Excellent - Absolutely perfect!'
            };

            let currentRating = 0;
            let hoverRating = 0;

            // Initialize stars
            stars.forEach(star => {
                star.addEventListener('click', function () {
                    const rating = parseInt(this.getAttribute('data-value'));
                    setRating(rating);
                });

                star.addEventListener('mouseenter', function () {
                    const rating = parseInt(this.getAttribute('data-value'));
                    hoverRating = rating;
                    updateStarDisplay(true);
                });

                star.addEventListener('mouseleave', function () {
                    hoverRating = 0;
                    updateStarDisplay(false);
                });
            });

            // Function to set rating
            function setRating(rating) {
                currentRating = rating;
                selectedRatingInput.value = rating;

                // Update text
                ratingText.textContent = ratingDescriptions[rating];
                ratingValue.textContent = `(${rating}/5)`;
                ratingValue.classList.remove('d-none');

                // Update star display
                updateStarDisplay(false);

                // Remove any previous error styling
                selectedRatingInput.classList.remove('is-invalid');
            }

            // Function to update star display
            function updateStarDisplay(isHovering) {
                const ratingToShow = isHovering ? hoverRating : currentRating;

                stars.forEach(star => {
                    const starValue = parseInt(star.getAttribute('data-value'));

                    star.classList.remove('active', 'hover');

                    if (starValue <= ratingToShow) {
                        if (isHovering) {
                            star.classList.add('hover');
                        } else {
                            star.classList.add('active');
                        }
                    }
                });

                // Update text for hover
                if (isHovering && hoverRating > 0) {
                    ratingText.textContent = ratingDescriptions[hoverRating];
                    ratingValue.textContent = `(${hoverRating}/5)`;
                    ratingValue.classList.remove('d-none');
                } else if (!isHovering && currentRating > 0) {
                    ratingText.textContent = ratingDescriptions[currentRating];
                    ratingValue.textContent = `(${currentRating}/5)`;
                    ratingValue.classList.remove('d-none');
                } else if (!isHovering && currentRating === 0) {
                    ratingText.textContent = 'Click on a star to rate';
                    ratingValue.classList.add('d-none');
                }
            }

            // Form validation
            reviewForm.addEventListener('submit', function (e) {
                if (!selectedRatingInput.value || selectedRatingInput.value < 1 || selectedRatingInput.value > 5) {
                    e.preventDefault();

                    // Add error styling
                    selectedRatingInput.classList.add('is-invalid');
                    ratingText.textContent = 'Please select a rating';
                    ratingText.classList.add('text-danger');

                    // Highlight the star container
                    document.querySelector('.star-rating').style.border = '2px solid #dc3545';
                    document.querySelector('.star-rating').style.borderRadius = '5px';
                    document.querySelector('.star-rating').style.padding = '5px';

                    return false;
                }
            });
        });
    </script>
@endsection