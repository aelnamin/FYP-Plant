@extends('layouts.main')

@section('title', 'My Profile')

@section('content')
    <div class="profile-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-lg-12">
                    <div class="profile-card">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="profile-header text-center">
                            <div class="position-relative">
                                <div class="profile-pic-wrapper">
                                    <img src="{{ Auth::user()->profile_picture ? asset(Auth::user()->profile_picture) : asset('images/default.png') }}"
                                        class="profile-pic" id="profilePicPreview">
                                    <div class="profile-pic-overlay">
                                        <i class="bi bi-camera"></i>
                                    </div>
                                </div>
                            </div>

                            <h1 class="user-name mt-4">{{ Auth::user()->name }}</h1>
                            <p class="user-email">
                                <i class="bi bi-envelope me-2"></i>{{ Auth::user()->email }}
                            </p>
                            <p class="member-since mt-1">
                                <i class="bi bi-calendar-event me-1"></i>
                                Member since {{ Auth::user()->created_at->format('M Y') }}
                            </p>
                        </div>

                        <div class="form-section">
                            <form action="{{ route('buyer.profile.update') }}" method="POST" enctype="multipart/form-data"
                                id="profileForm">
                                @csrf
                                @method('PUT')

                                <h4 class="section-title">Personal Information</h4>

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="bi bi-person me-2"></i>Full Name
                                        </label>
                                        <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}"
                                            required placeholder="Enter your full name">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="bi bi-envelope me-2"></i>Email Address
                                        </label>
                                        <input type="email" class="form-control" value="{{ Auth::user()->email }}" readonly
                                            placeholder="Your email">
                                        <small class="text-muted mt-1 d-block">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Contact admin to change email
                                        </small>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="bi bi-phone me-2"></i>Phone Number
                                        </label>
                                        <input type="text" name="phone" class="form-control"
                                            value="{{ Auth::user()->phone }}" placeholder="Enter phone number">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="bi bi-image me-2"></i>Profile Picture
                                        </label>
                                        <input type="file" name="profile_picture" class="form-control"
                                            id="profilePictureInput" accept="image/*">
                                        <small class="text-muted mt-1 d-block">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Max 2MB • JPG, PNG, GIF, WEBP
                                        </small>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">
                                            <i class="bi bi-house me-2"></i>Delivery Address
                                        </label>
                                        <textarea name="address" rows="3" class="form-control"
                                            placeholder="Enter your complete address">{{ Auth::user()->address }}</textarea>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-5 pt-4 border-top">
                                    <div>
                                        <button type="button" class="btn-logout"
                                            onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                                        </button>
                                    </div>

                                    <div>
                                        <button type="submit" class="btn-save">
                                            <i class="bi bi-check-circle me-2"></i>Save Changes
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Purchase History Section - FIXED -->
                        <div class="purchase-history-section mt-5">
                            <h4 class="section-title mb-4">Purchase History</h4>

                            @if($groupedOrders->count() > 0)
                                @foreach($groupedOrders as $group)
                                    <div class="seller-group mb-4 p-3 border rounded">
                                        <!-- Seller Header -->
                                        <div class="d-flex align-items-center mb-3 pb-2 border-bottom">
                                            <div class="seller-icon me-3">
                                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                                    style="width: 40px; height: 40px; border: 2px solid #5C7F51;">
                                                    <i class="bi bi-shop text-success"></i>
                                                </div>
                                            </div>
                                            <div class="seller-info flex-grow-1">
                                                <h6 class="seller-name mb-0">{{ $group['seller_name'] }}</h6>
                                                <small class="text-muted">
                                                    Order #{{ str_pad($group['order_id'], 8, '0', STR_PAD_LEFT) }}
                                                </small>
                                            </div>
                                            @php
                                                $status = $group['status'];
                                                $statusClass = match (strtoupper($status)) {
                                                    'PENDING' => 'badge bg-warning text-dark',
                                                    'PAID' => 'badge bg-info text-white',
                                                    'SHIPPED' => 'badge bg-primary text-white',
                                                    'DELIVERED', 'COMPLETED' => 'badge bg-success text-white',
                                                    'CANCELLED' => 'badge bg-danger text-white',
                                                    default => 'badge bg-secondary text-white',
                                                };
                                            @endphp
                                            <span class="{{ $statusClass }}">{{ strtoupper($status) }}</span>
                                        </div>

                                        <!-- Order Date -->
                                        <div class="mb-2">
                                            <small class="text-muted">
                                                <i class="bi bi-calendar me-1"></i>
                                                {{ $group['order_date']->format('d M Y, h:i A') }}
                                            </small>
                                        </div>

                                        <!-- Items List -->
                                        <div class="items-list mb-3">
                                            @foreach($group['items'] as $item)
                                                @php 
                                                    $product = $item->product; 
                                                    $itemTotal = $item->price * $item->quantity;
                                                @endphp
                                                <div class="d-flex align-items-center mb-2 {{ !$loop->last ? 'border-bottom pb-2' : '' }}">
                                                    <div class="product-image me-3">
                                                        <img src="{{ $product->images->first() 
                                                            ? asset('images/' . $product->images->first()->image_path)
                                                            : asset('images/default.png') }}"
                                                            alt="{{ $product->product_name }}"
                                                            class="rounded"
                                                            style="width: 120px; height: 120px; object-fit: cover;">
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="fw-semibold">{{ $product->product_name }}</div>
                                                        <div class="small text-muted">
                                                            <span class="me-3">
                                                                <i class="bi bi-tag"></i> 
                                                                {{ $item->variant ?: 'Standard' }}
                                                            </span>
                                                            <span class="me-3">
                                                                <i class="bi bi-x-square"></i> 
                                                                Qty: {{ $item->quantity }}
                                                            </span>
                                                            <span class="text-dark">
                                                                RM {{ number_format($item->price, 2) }} each
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="text-end">
                                                        <div class="fw-bold text-dark">
                                                            RM {{ number_format($itemTotal, 2) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <!-- Order Summary -->
                                        <div class="order-summary border-top pt-3">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="small text-muted">
                                                        <i class="bi bi-box me-1"></i>
                                                        {{ $group['item_count'] }} item(s)
                                                    </div>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <div class="mb-1">
                                                        <span class="text-muted small">Subtotal:</span>
                                                        <span class="fw-semibold ms-2">RM {{ number_format($group['subtotal'], 2) }}</span>
                                                    </div>
                                                    <div class="mb-1">
                                                        <span class="text-muted small">Delivery:</span>
                                                        <span class="fw-semibold ms-2">RM {{ number_format($group['delivery_fee'], 2) }}</span>
                                                    </div>
                                                    <div class="border-top pt-2 mt-2">
                                                        <span class="text-dark fw-bold">Total:</span>
                                                        <span class="fw-bold text-dark ms-2">
                                                            RM {{ number_format($group['total'], 2) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Action Buttons - FIXED -->
                                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                            <div class="text-muted small">
                                                Items from {{ $group['seller_name'] }} 
                                            </div>
                                            <div class="d-flex gap-2">
                                                <!-- View Order button - FIXED ROUTE -->
                                              <!-- Change this line in your profile.blade.php -->
                                              <a href="{{ route('buyer.order-details', ['order' => $group['order_id'], 'seller' => $group['seller_id']]) }}" 
   class="btn btn-outline-primary btn-sm">
    <i class="bi bi-eye me-1"></i> View Order
</a>


                                                @if(in_array(strtoupper($group['status']), ['DELIVERED', 'COMPLETED']))
                                                    <a href="{{ route('buyer.order-details', $group['order_id']) }}?seller={{ $group['seller_id'] }}"
                                                       class="btn btn-primary btn-sm">
                                                        <i class="bi bi-star me-1"></i> Review
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="no-purchases text-center py-5 border rounded">
                                    <i class="bi bi-cart-x fs-1 text-muted mb-3"></i>
                                    <h5 class="text-muted mb-2">No purchases yet</h5>
                                    <p class="text-muted mb-4">Start shopping to see your purchase history here.</p>
                                    <a href="{{ route('products.browse') }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-bag me-1"></i> Browse Products
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const profilePicInput = document.getElementById('profilePictureInput');
            const profilePicPreview = document.getElementById('profilePicPreview');
            const profilePicOverlay = document.querySelector('.profile-pic-overlay');

            profilePicOverlay.addEventListener('click', function () {
                profilePicInput.click();
            });

            profilePicInput.addEventListener('change', function (e) {
                if (e.target.files && e.target.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        profilePicPreview.src = e.target.result;
                    }
                    reader.readAsDataURL(e.target.files[0]);
                }
            });

            const profilePicWrapper = document.querySelector('.profile-pic-wrapper');
            profilePicWrapper.addEventListener('mouseenter', function () {
                profilePicPreview.style.transform = 'scale(1.1)';
            });

            profilePicWrapper.addEventListener('mouseleave', function () {
                profilePicPreview.style.transform = 'scale(1)';
            });
        });
    </script>
@endsection