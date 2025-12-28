{{-- show.blade.php --}}
@extends('layouts.admin-main')

@section('title', 'Order Details')

@section('content')
    <style>
        .order-details-page {
            min-height: 100vh;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9f5e9 100%);
            padding: 40px 0;
        }

        .back-button {
            background: transparent;
            border: 2px solid #5C7F51;
            color: #5C7F51;
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 25px;
        }

        .back-button:hover {
            background: #5C7F51;
            color: white;
            transform: translateX(-3px);
        }

        .order-header-card {
            background: linear-gradient(135deg, #5C7F51 0%, #A5B682 100%);
            border-radius: 16px;
            padding: 30px;
            color: white;
            margin-bottom: 25px;
            box-shadow: 0 5px 20px rgba(92, 127, 81, 0.2);
        }

        .order-id-display {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .order-status-large {
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .info-card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 25px;
            border: 1px solid rgba(92, 127, 81, 0.1);
            box-shadow: 0 5px 20px rgba(92, 127, 81, 0.08);
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e9f5e9;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .info-item {
            padding: 10px 0;
        }

        .info-label {
            color: #6c757d;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .info-value {
            font-weight: 600;
            color: #2c3e50;
            font-size: 16px;
        }

        .total-amount-display {
            font-size: 28px;
            font-weight: 700;
            color: #5C7F51;
            text-align: right;
            padding: 20px 0;
            border-top: 2px solid #e9f5e9;
            margin-top: 20px;
        }

        .items-card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            border: 1px solid rgba(92, 127, 81, 0.1);
            box-shadow: 0 5px 20px rgba(92, 127, 81, 0.08);
        }

        .item-row {
            display: flex;
            align-items: center;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
        }

        .item-row:hover {
            background: white;
            border-color: #5C7F51;
            transform: translateX(5px);
        }

        .item-image {
            width: 80px;
            height: 80px;
            border-radius: 10px;
            object-fit: cover;
            border: 2px solid white;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            margin-right: 20px;
        }

        .item-info {
            flex: 1;
        }

        .item-name {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .item-details {
            font-size: 13px;
            color: #6c757d;
            display: flex;
            gap: 15px;
        }

        .item-price {
            font-weight: 700;
            color: #5C7F51;
            font-size: 16px;
            text-align: right;
            min-width: 120px;
        }

        .item-subtotal {
            font-size: 13px;
            color: #6c757d;
            text-align: right;
        }

        .admin-actions {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }

        .btn-status {
            background: linear-gradient(135deg, #5C7F51 0%, #A5B682 100%);
            color: white;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-status:hover {
            background: linear-gradient(135deg, #4a6b42 0%, #5C7F51 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(92, 127, 81, 0.2);
        }
    </style>

    <div class="order-details-page">
        <div class="container">
            <!-- Back Button -->
            <a href="{{ route('admin.orders.index') }}" class="back-button">
                <i class="bi bi-arrow-left"></i> Back to Orders
            </a>

            <!-- Order Header -->
            <div class="order-header-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="order-id-display">Order ID #{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}</div>
                        <div class="text-white-50">
                            <i class="bi bi-calendar me-1"></i>
                            {{ $order->created_at->format('d M Y, H:i') }}
                        </div>
                    </div>

                    <span class="order-status-large">
                        <i class="bi 
                                                            @if($order->status === 'Pending') bi-clock
                                                            @elseif($order->status === 'Paid') bi-credit-card
                                                            @elseif($order->status === 'Shipped') bi-truck
                                                            @endif"></i>
                        {{ $order->status }}
                    </span>
                </div>
            </div>

            <!-- Order Information -->
            <div class="info-card">
                <h3 class="section-title">
                    <i class="bi bi-info-circle"></i> Order Information
                </h3>

                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Buyer ID</div>
                        <div class="info-value">{{ $order->buyer_id }}</div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Order Status</div>
                        <div class="info-value">
                            <span class="status-badge 
                                                                @if($order->status === 'Pending') status-pending
                                                                @elseif($order->status === 'Paid') status-paid
                                                                @elseif($order->status === 'Shipped') status-shipped
                                                                @endif">
                                {{ $order->status }}
                            </span>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Date Placed</div>
                        <div class="info-value">{{ $order->created_at->format('d M Y, H:i') }}</div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Order Total</div>
                        <div class="info-value">RM {{ number_format($order->total_amount, 2) }}</div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="items-card">
                <h3 class="section-title">
                    <i class="bi bi-box-seam"></i> Order Items
                </h3>

                @foreach($order->items as $item)
                    <div class="item-row">
                        <!-- Product Image -->
                        @if($item->product && $item->product->images->first())
                            <img src="{{ asset('images/' . $item->product->images->first()->image_path) }}" class="item-image"
                                alt="{{ $item->product->product_name }}">
                        @else
                            <div class="item-image"
                                style="background: #e9f5e9; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-image text-muted"></i>
                            </div>
                        @endif

                        <!-- Product Info -->
                        <div class="item-info">
                            <div class="item-name">
                                {{ $item->product->product_name ?? 'Unknown Product' }}
                            </div>
                            <div class="item-details">
                                <span>
                                    <i class="bi bi-x-square me-1"></i>
                                    Quantity: {{ $item->quantity }}
                                </span>
                                <span>
                                    <i class="bi bi-tag me-1"></i>
                                    Unit Price: RM {{ number_format($item->price, 2) }}
                                </span>
                            </div>
                        </div>

                        <!-- Price -->
                        <div>
                            <div class="item-price">
                                RM {{ number_format($item->price * $item->quantity, 2) }}
                            </div>
                            <div class="item-subtotal">
                                RM {{ number_format($item->price, 2) }} x {{ $item->quantity }}
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Order Total -->
                <div class="total-amount-display">
                    Order Total: RM {{ number_format($order->total_amount, 2) }}
                </div>
            </div>

            <!-- Admin Actions -->
            <div class="admin-actions">
                @if($order->status === 'Pending')
                    <form action="{{ route('admin.orders.markPaid', $order) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn-status">
                            <i class="bi bi-check-circle"></i>
                            Mark as Paid
                        </button>
                    </form>
                @endif

                @if($order->status === 'Paid')
                    <button type="button" class="btn-status">
                        <i class="bi bi-truck"></i>
                        Mark as Shipped
                    </button>
                @endif

                <button type="button" class="btn-status"
                    style="background: linear-gradient(135deg, #6c757d 0%, #868e96 100%);">
                    <i class="bi bi-printer"></i>
                    Print Invoice
                </button>
            </div>
        </div>
    </div>

    <script>
        // Add hover effects
        document.querySelectorAll('.item-row').forEach(item => {
            item.addEventListener('mouseenter', function () {
                this.style.boxShadow = '0 5px 15px rgba(92, 127, 81, 0.1)';
            });
            item.addEventListener('mouseleave', function () {
                this.style.boxShadow = 'none';
            });
        });
    </script>
@endsection