@extends('layouts.main')

@section('title', 'My Profile')

@section('content')

    <style>
        .profile-container {
            background: linear-gradient(135deg, rgb(255, 255, 255) 0%, rgb(255, 255, 255) 100%);
            min-height: 100vh;
            padding: 40px 0;
        }

        .profile-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(92, 127, 81, 0.15);
            border: none;
            overflow: hidden;
        }

        .profile-header {
            background: linear-gradient(135deg, #5C7F51 0%, #A5B682 100%);
            padding: 40px 0;
            position: relative;
        }

        .profile-pic-wrapper {
            position: relative;
            width: 160px;
            height: 160px;
            margin: 0 auto;
            border: 6px solid white;
            border-radius: 50%;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            background: white;
        }

        .profile-pic-wrapper:hover .profile-pic-overlay {
            opacity: 1;
        }

        .profile-pic {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .profile-pic-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(92, 127, 81, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            border-radius: 50%;
            cursor: pointer;
        }

        .profile-pic-overlay i {
            color: white;
            font-size: 24px;
        }

        .file-input {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .user-name {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .user-email {
            color: #5C7F51;
            font-size: 14px;
            font-weight: 500;
        }

        .form-section {
            padding: 40px;
        }

        .section-title {
            color: #5C7F51;
            font-weight: 600;
            font-size: 18px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e9f5e9;
            position: relative;
        }

        .section-title:after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 60px;
            height: 2px;
            background: #5C7F51;
        }

        .form-label {
            color: #495057;
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #5C7F51;
            box-shadow: 0 0 0 3px rgba(92, 127, 81, 0.1);
        }

        .form-control[readonly] {
            background-color: #f8f9fa;
            border-color: #e9ecef;
            color: #6c757d;
        }

        .btn-save {
            background: linear-gradient(135deg, #5C7F51 0%, #A5B682 100%);
            color: white;
            padding: 12px 36px;
            border-radius: 50px;
            border: none;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(92, 127, 81, 0.2);
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(92, 127, 81, 0.3);
        }

        .btn-logout {
            color: #dc3545;
            border: 2px solid #dc3545;
            padding: 10px 30px;
            border-radius: 50px;
            font-weight: 500;
            transition: all 0.3s ease;
            background: white;
        }

        .btn-logout:hover {
            background: #dc3545;
            color: white;
            transform: translateY(-2px);
        }

        .member-since {
            color: rgb(26, 85, 137);
            font-size: 13px;
            font-weight: 500;
        }

        .purchase-history-section {
            padding: 40px;
            border-top: 1px solid #e9f5e9;
        }

        .purchase-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .purchase-card:hover {
            border-color: #5C7F51;
            box-shadow: 0 5px 20px rgba(92, 127, 81, 0.1);
        }

        .seller-group {
            background: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            margin-bottom: 20px;
            padding: 20px;
        }

        .product-item {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            transition: transform 0.2s ease;
        }

        .seller-icon {
            color: #5C7F51;
        }

        .seller-name {
            color: #2c3e50;
            font-size: 16px;
            font-weight: 600;
        }

        .product-name {
            color: #2c3e50;
            font-size: 15px;
            font-weight: 500;
        }

        .product-meta {
            font-size: 14px;
            color: #6c757d;
        }

        .btn-outline-primary {
            border-color: #5C7F51;
            color: #5C7F51;
            padding: 6px 15px;
            font-size: 14px;
            border-radius: 8px;
        }

        .btn-outline-primary:hover {
            background-color: #5C7F51;
            color: white;
        }

        .btn-primary {
            background-color: #5C7F51;
            border-color: #5C7F51;
            padding: 7px 18px;
            font-size: 14px;
            border-radius: 8px;
        }

        .btn-primary:hover {
            background-color: #4a6b42;
            border-color: #4a6b42;
        }

        .purchase-status {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-completed {
            background: #d4edda;
            color: #155724;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-processing {
            background: #cce5ff;
            color: #004085;
        }

        .purchase-total {
            font-weight: 700;
            color: #2c3e50;
            font-size: 18px;
        }

        .no-purchases {
            text-align: center;
            padding: 40px 20px;
            color: #6c757d;
        }

        /* Fixed container */
        .product-image-wrapper {
            width: 130px;
            height: 130px;
            flex-shrink: 0; /* prevents resizing */
        }

        /* Image always fills container */
        .order-product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;      /* 👈 MOST IMPORTANT */
            border-radius: 12px;
            border: 1px solid #e9ecef;
            background-color: #fff;
        }

        .order-info {
            background: #e9f5e9;
            border-radius: 6px;
            padding: 8px 12px;
            margin-bottom: 10px;
            font-size: 13px;
        }

        .order-id-badge {
            color:rgb(0, 0, 0);
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 400;
        }

        .group-total {
            min-width: 150px;
        }

        .total-amount {
            color: #5C7F51;
        }
    </style>

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

                        <!-- Purchase History Section -->
                        <div class="purchase-history-section">
                            <h4 class="section-title">Purchase History</h4>

                            @if(isset($orders) && $orders->count() > 0)
                                <!-- Group items by seller and order time -->
                                @php
                                    $groupedOrders = [];
                                    foreach ($orders as $order) {
                                        foreach ($order->items as $item) {
                                            $sellerId = $item->product->seller_id ?? 0;
                                            $sellerName = $item->product->seller->business_name ?? 'Unknown Seller';
                                            $orderTime = $order->created_at->format('Y-m-d H:i');

                                            // Group by seller and order time
                                            $key = $sellerId . '-' . $orderTime;

                                            if (!isset($groupedOrders[$key])) {
                                                $groupedOrders[$key] = [
                                                    'seller_id' => $sellerId,
                                                    'seller_name' => $sellerName,
                                                    'order_id' => $order->id,
                                                    'order_date' => $order->created_at,
                                                    'order_time' => $orderTime,
                                                    'items' => [],
                                                    'total_amount' => 0,
                                                    'status' => $order->status ?? 'Pending'
                                                ];
                                            }

                                            $groupedOrders[$key]['items'][] = [
                                                'product' => $item->product,
                                                'item' => $item,
                                            ];
                                            $groupedOrders[$key]['total_amount'] += ($item->price * $item->quantity);
                                        }
                                    }
                                @endphp

                                <!-- Display grouped orders -->
                                @foreach($groupedOrders as $key => $group)
                                    <div class="seller-group mb-4">
                                        <!-- Seller Header -->
                                        <div class="d-flex align-items-center mb-3 pb-2 border-bottom">
                                            <div class="seller-icon me-3">
                                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                                    style="width: 40px; height: 40px; border: 2px solid #5C7F51;">
                                                    <i class="bi bi-shop text-success"></i>
                                                </div>
                                            </div>
                                            <div class="seller-info flex-grow-1">
                                                <div class="seller-name">{{ $group['seller_name'] }}</div>
                                            </div>
                                        </div>

                                        <!-- Single Product Card for Combined Items -->
                                        <div class="product-item mb-3">
                                            <!-- Order Info inside product card - Single order ID/date for combined products -->
                                            <div class="order-info d-flex justify-content-between align-items-center mb-2">
                                                <div>
                                                    <span class="order-id-badge">
                                                        Order ID {{ str_pad($group['order_id'], 10, '0', STR_PAD_LEFT) }}
                                                    </span>
                                                </div>

                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="order-date-text">
                                                        <i class="bi bi-calendar-event"></i>
                                                        {{ $group['order_date']->format('d/m/Y') }}
                                                    </div>

                                                    @php
                                                        $status = $group['status'];
                                                        $statusClass = match($status) {
                                                            'Pending' => 'status-pending',
                                                            'Paid' => 'status-processing',
                                                            'Shipped' => 'status-completed',
                                                            default => 'status-pending',
                                                        };
                                                    @endphp
                                                    <span class="purchase-status {{ $statusClass }}">
                                                        {{ strtoupper($status) }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Combined Products Display -->
                                            <div class="combined-products">
                                                @foreach($group['items'] as $productData)
                                                    @php
                                                        $p = $productData['product'];
                                                        $item = $productData['item'];
                                                    @endphp
                                                    <div class="d-flex align-items-center mb-3 {{ !$loop->last ? 'border-bottom pb-3' : '' }}">
                                                        <!-- Product Image - Keeping original 130px size -->
                                                        <div class="product-image me-3">
                                                            <div class="product-image-wrapper me-3">
                                                                <img src="{{ $p && $p->images->first()
                                                                    ? asset('images/' . $p->images->first()->image_path)
                                                                    : asset('images/default.png') }}" alt="{{ $p->product_name ?? 'Product' }}" class="order-product-image">
                                                            </div>
                                                        </div>

                                                        <!-- Product Info -->
                                                        <div class="product-details flex-grow-1">
                                                            <div class="product-name mb-1">{{ $p->product_name ?? 'Unknown Product' }}</div>
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div class="product-meta">
                                                                    <span class="quantity me-3">
                                                                        <i class="bi bi-x-square me-1"></i>Qty: {{ $item->quantity }}
                                                                    </span>
                                                                    <span class="product-price text-success fw-semibold">
                                                                        RM {{ number_format($item->price, 2) }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <!-- Group Footer with Total -->
                                        <div class="group-footer d-flex justify-content-between align-items-center pt-3 mt-3 border-top">
                                            <div class="text-muted small">
                                                {{ count($group['items']) }} item(s) in this order
                                            </div>
                                            <div class="group-total text-end">
                                                <div class="total-label text-muted small">Order Total:</div>
                                                <div class="total-amount fw-bold text-dark fs-5">
                                                    RM {{ number_format($group['total_amount'], 2) }}
                                                </div>
                                            </div>
                                        </div>

                                        <!-- View Order Details Button -->
                                        <div class="text-end mt-3">
                                            <button class="btn btn-outline-primary btn-sm view-order-btn"
                                                data-seller-id="{{ $group['seller_id'] }}" data-order-id="{{ $group['order_id'] }}">
                                                View Order
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="no-purchases">
                                    <i class="bi bi-cart-x fs-1"></i>
                                    <h5>No purchases yet</h5>
                                    <p>You haven't made any purchases.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

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

            // View seller details button
            document.querySelectorAll('.view-order-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const sellerId = this.getAttribute('data-seller-id');
                    const orderId = this.getAttribute('data-order-id');
                    alert(`Viewing details for order #${orderId} from seller ${sellerId}`);
                });
            });
        });
    </script>

@endsection