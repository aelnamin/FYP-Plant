<style>
    .seller-group {
        margin-bottom: 1.5rem;
    }

    .seller-header {
        background: #f8f9ff;
        padding: 0.6rem 1rem;
        border-radius: 10px;
        margin-bottom: 0.8rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .seller-info {
        display: flex;
        align-items: center;
        color: #4bae7f;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .seller-item-count {
        background: #4bae7f;
        color: white;
        padding: 0.1rem 0.5rem;
        border-radius: 10px;
        font-size: 0.75rem;
    }

    .product-card {
        background: white;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 0.8rem;
        box-shadow: 0 8px 20px rgba(75, 174, 127, 0.1);
        border: 1px solid #f0f7ff;
        margin-left: 0.5rem;
    }

    .product-img {
        width: 90px;
        height: 90px;
        border-radius: 12px;
        object-fit: cover;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .product-price {
        font-weight: 700;
        font-size: 0.9rem;
        white-space: nowrap;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        padding-top: 0.8rem;
        margin-top: 0.8rem;
        border-top: 1px dashed #e9ecef;
        font-weight: 600;
    }

    .quantity-control {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }

    .qty-btn {
        width: 25px;
        height: 25px;
        border-radius: 6px;
        border: 1px solid #dee2e6;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        color: #4bae7f;
        padding: 0;
    }
</style>

@if($cartItems->count() > 0)

    @php
        $subtotal = 0;
        // Group items by seller
        $groupedItems = $cartItems->groupBy(function ($item) {
            return $item->product->seller->id ?? 'unknown';
        });
    @endphp

    <!-- Cart Items Grouped by Seller -->
    @foreach($groupedItems as $sellerId => $sellerItems)
        @php
            $seller = $sellerItems->first()->product->seller ?? null;
        @endphp

        <div class="seller-group">
            <!-- Seller Header (appears once per seller) -->
            <div class="seller-header">
                <div class="seller-info">
                    <i class="bi bi-shop me-2"></i>
                    {{ $seller->business_name ?? 'Unknown Seller' }}
                </div>
                <div class="seller-item-count">
                    {{ $sellerItems->count() }} item{{ $sellerItems->count() > 1 ? 's' : '' }}
                </div>
            </div>

            <!-- Products from this seller -->
            @foreach($sellerItems as $item)
                @if($item->product)
                    @php
                        $subtotal += $item->product->price * $item->quantity;
                    @endphp

                    <div class="product-card">
                        <div class="d-flex align-items-center">
                            <img src="{{ $item->product->images->first()
                                    ? asset('images/' . $item->product->images->first()->image_path)
                                    : asset('images/default.jpg') }}" class="product-img me-3"
                                alt="{{ $item->product->product_name }}">

                            <div class="flex-grow-1">
                                <div class="fw-semibold mb-1">
                                    {{ $item->product->product_name }}
                                </div>

                                <!-- Quantity Controls -->
                                <div class="quantity-control">
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST"
                                        class="d-flex align-items-center">
                                        @csrf
                                        @method('PUT') <!-- Change PATCH to PUT -->
                                        <button type="submit" name="quantity" value="{{ $item->quantity - 1 }}" class="qty-btn" {{ $item->quantity <= 1 ? 'disabled' : '' }}>
                                            -
                                        </button>

                                        <span class="mx-2">{{ $item->quantity }}</span>

                                        <button type="submit" name="quantity" value="{{ $item->quantity + 1 }}" class="qty-btn">
                                            +
                                        </button>
                                    </form>


                                    <small class="text-muted ms-2">
                                        x RM{{ number_format($item->product->price, 2) }}
                                    </small>
                                </div>
                            </div>

                            <div class="text-end">
                                <div class="product-price mb-2">
                                    RM {{ number_format($item->product->price * $item->quantity, 2) }}
                                </div>

                                <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-dark btn-sm">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endforeach

    <!-- Subtotal -->
    <div class="summary-item">
        <span>Subtotal</span>
        <span>RM {{ number_format($subtotal, 2) }}</span>
    </div>

@else
    <div class="text-center text-muted py-3">
        Your cart is empty.
    </div>
@endif