@extends('layouts.main')

@section('title', $seller->business_name . ' - Shop')

@section('content')

    <style>
        :root {
            --primary-green: #5C7F51;
            --light-green: #7da06c;
            --pale-green: #f1efda;
            --feature-green: #cfc983;
            --text-dark: #212529;
            --text-muted: #6c757d;
        }

        .seller-header {
            background: linear-gradient(135deg, #f9f9f9 0%, var(--pale-green) 100%);
            border-radius: 20px;
            padding: 2.5rem;
            margin-bottom: 2.5rem;
            border: 1px solid rgba(0, 0, 0, 0.08);
            box-shadow: 0 10px 30px rgba(92, 127, 81, 0.1);
        }

        .product-card {
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.08);
            box-shadow: 0 10px 25px rgba(92, 127, 81, 0.1);
            background: white;
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(92, 127, 81, 0.15);
        }

        .product-card .card-img-top {
            height: 250px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-card:hover .card-img-top {
            transform: scale(1.03);
        }

        .product-card .card-footer {
            background: #fff;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }

        .btn-outline-success {
            border-color: var(--primary-green);
            color: var(--primary-green);
            border-radius: 50px;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 8px 20px;
        }

        .btn-outline-success:hover {
            background: var(--primary-green);
            color: white;
            transform: translateY(-2px);
        }

        .btn-chat {
            background: #e9ecef;
            color: #212529;
            border-radius: 50px;
            font-weight: 500;
            padding: 10px 24px;
            border: 2px solid rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .btn-chat:hover {
            background: #dee2e6;
            color: #212529;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-color: rgba(0, 0, 0, 0.2);
        }

        .section-title {
            font-size: 1.9rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 10px;
            text-align: center;
        }

        .section-title:after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: 0;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-green), var(--light-green));
            border-radius: 2px;
        }

        .seller-avatar {
    width: 220px;
    height: 220px;
    object-fit: cover;
    border-radius: 50%;
    border: 6px solid white;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}


        .stat-badge {
            background: var(--pale-green);
            color: var(--primary-green);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .location-text {
            color: var(--text-muted);
            font-size: 1.1rem;
        }

        .price-tag {
            color: var(--primary-green);
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 0;
        }

        .product-meta {
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .product-meta i {
            color: var(--primary-green);
            margin-right: 5px;
        }

        .seller-features {
            background: linear-gradient(135deg, #f9f9f9 0%, var(--feature-green) 100%);
            border-radius: 16px;
            padding: 2rem;
            margin: 2rem 0;
        }

        .feature-icon {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: var(--primary-green);
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--text-muted);
        }

        .empty-state i {
            font-size: 3rem;
            color: var(--light-green);
            margin-bottom: 1rem;
        }

        .contact-section {
            margin-top: 1.5rem;
        }
        .seller-rating {
    font-size: 1.15rem; /* slightly bigger */
}

.seller-rating .bi {
    font-size: 1.25rem; /* stars more visible */
}

.seller-rating .rating-number {
    font-size: 1.15rem;
}

.seller-rating .rating-count {
    font-size: 1rem;
}

    </style>

    <div class="container py-4">

        <!-- Seller Header -->
        <div class="seller-header">
            <div class="row align-items-center">
                <div class="col-md-3 text-center">
                    <img src="{{ $seller->user->profile_picture ? asset($seller->user->profile_picture) : asset('images/default.png') }}"
                        class="seller-avatar rounded-circle">
                </div>
                <div class="col-md-9">
                    <h1 class="display-6 fw-bold mb-3" style="color: var(--primary-green);">
                        {{ $seller->business_name }}
                        @if($seller->products_count > 50)
                            <i class="bi bi-patch-check-fill ms-2" style="color: var(--primary-green);"></i>
                        @endif

                        {{-- Seller Rating --}}
                        @if($totalReviews > 0)
    <div class="seller-rating d-flex align-items-center mt-2 mb-2">
        <div class="text-warning me-2">
            @for ($i = 1; $i <= 5; $i++)
                @if ($i <= floor($averageRating))
                    <i class="bi bi-star-fill"></i>
                @elseif ($i - $averageRating < 1)
                    <i class="bi bi-star-half"></i>
                @else
                    <i class="bi bi-star"></i>
                @endif
            @endfor
        </div>

        <span class="fw-semibold rating-number">{{ $averageRating }}</span>
        <span class="text-muted ms-2 rating-count">({{ $totalReviews }} reviews)</span>
    </div>
@else
    <p class="text-muted mb-2 seller-rating">No reviews yet</p>
@endif


                    </h1>

                    <p class="location-text mb-4">
                        <i class="bi bi-geo-alt-fill me-2"></i>{{ $seller->business_address }}
                    </p>

                    <div class="d-flex gap-3 align-items-center">
                        <span class="stat-badge">
                            <i class="bi bi-box-seam"></i>
                            {{ $seller->products_count }} Products
                        </span>
                    </div>

                    <!-- Chat Button -->
                    <div class="contact-section">
                        @auth
                            @if(auth()->user()->role === 'buyer')
                                <a href="{{ route('buyer.chats.show', $seller->user->id) }}" class="btn btn-chat">
                                    <i class="bi bi-chat-left-text me-2"></i>Chat with Seller
                                </a>

                            @endif
                        @else
                            <a href="{{ route('auth.login') }}" class="btn btn-chat">
                                <i class="bi bi-chat-left-text me-2"></i>Login to Chat
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <!-- Best Sellers Section -->
        @if($bestSellers->count() > 0)
            <div class="mb-5">
                <h2 class="section-title"> Best Selling Products</h2>
                <div class="row g-4">
                    @foreach($bestSellers as $p)
                        <div class="col-6 col-md-3">
                            <div class="card product-card border-0 h-100">
                                <div class="position-relative">
                                    <img src="{{ $p->images->first() ? asset('images/' . $p->images->first()->image_path) : asset('images/default.jpg') }}"
                                        class="card-img-top rounded-top-4" alt="{{ $p->product_name }}">
                                    @if($p->total_sold > 20)
                                        <span class="badge bg-danger position-absolute top-0 start-0 m-3 rounded-pill">
                                            <i class="bi bi-fire me-1"></i> Hot
                                        </span>
                                    @endif
                                </div>

                                <div class="card-body">
                                    <h6 class="fw-bold mb-2">{{ $p->product_name }}</h6>

                                    <div class="product-meta mb-3">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span><i class="bi bi-cart-check"></i> {{ $p->total_sold ?? 0 }} sold</span>
                                            <span><i class="bi bi-box-seam"></i> {{ $p->stock_quantity }} left</span>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mt-auto">
                                        <p class="price-tag mb-0">RM {{ number_format($p->price, 2) }}</p>
                                    </div>
                                </div>

                                <div class="card-footer bg-white rounded-bottom-4">
                                    <a href="{{ route('products.show', $p->id) }}"
                                        class="btn btn-outline-success w-100 rounded-pill">
                                        View Details<i class="bi bi-arrow-right ms-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- All Products -->
        <div class="mt-5">
            <h2 class="section-title"> All Products</h2>

            @if($seller->products->count())
                <div class="row g-4">
                    @foreach($seller->products as $p)
                        <div class="col-6 col-md-3">
                            <div class="card product-card border-0 h-100">
                                <img src="{{ $p->images->first() ? asset('images/' . $p->images->first()->image_path) : asset('images/default.jpg') }}"
                                    class="card-img-top rounded-top-4" alt="{{ $p->product_name }}">

                                <div class="card-body">
                                    <h6 class="fw-bold mb-2">{{ $p->product_name }}</h6>

                                    <div class="product-meta mb-3">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span><i class="bi bi-cart-check"></i> {{ $p->total_sold ?? 0 }} sold</span>
                                            <span><i class="bi bi-box-seam"></i> {{ $p->stock_quantity }} left</span>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mt-auto">
                                        <p class="price-tag mb-0">RM {{ number_format($p->price, 2) }}</p>
                                    </div>
                                </div>

                                <div class="card-footer bg-white rounded-bottom-4">
                                    <a href="{{ route('products.show', $p->id) }}"
                                        class="btn btn-outline-success w-100 rounded-pill">
                                        View Details<i class="bi bi-arrow-right ms-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="bi bi-box-seam"></i>
                    <h4 class="mb-2">No Products Available</h4>
                    <p class="text-muted">This seller hasn't added any products yet.</p>
                </div>
            @endif
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.product-card').forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                card.style.transitionDelay = (index * 0.1) + 's';
                observer.observe(card);
            });

            const features = document.querySelector('.seller-features');
            features.addEventListener('mouseenter', function () {
                this.style.transform = 'translateY(-5px)';
                this.style.transition = 'transform 0.3s ease';
            });

            features.addEventListener('mouseleave', function () {
                this.style.transform = 'translateY(0)';
            });
        });
    </script>
@endsection