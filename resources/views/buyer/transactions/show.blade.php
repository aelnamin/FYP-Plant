@extends('layouts.main')

@section('title', 'Transaction Details')

@section('content')
    <div class="container py-5">

        {{-- Transaction Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
            <div>
                <h1 class="fw-bold text-gradient-success mb-2">Transaction Details</h1>
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">
                        #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
                    </span>
                    <span class="text-muted">
                        <i class="bi bi-calendar3 me-1"></i>
                        {{ $order->created_at->format('F d, Y') }}
                    </span>
                </div>
            </div>
            <div class="text-end mt-2 mt-md-0">
                <button onclick="window.print()" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    <i class="bi bi-printer me-1"></i> Print
                </button>
            </div>
        </div>

 {{-- Order Status Timeline --}}
<div class="card shadow-sm rounded-4 mb-4 border-0">
    <div class="card-body">
        <h5 class="fw-bold mb-4">Order Status</h5>

        <div class="d-flex justify-content-between align-items-center position-relative timeline-container">

            @php
                $statusStages = [
                    'placed' => 'check-lg',
                    'paid' => 'credit-card',
                    'shipped' => 'truck',
                    'delivered' => 'box-seam',
                    'completed' => 'check2-circle',
                ];

                $itemStatuses = $items->pluck('seller_status')
                    ->filter()
                    ->map(fn($s) => strtolower(trim($s)));

                if ($itemStatuses->contains('completed')) {
                    $currentStatus = 'completed';
                } elseif ($itemStatuses->contains('delivered')) {
                    $currentStatus = 'delivered';
                } elseif ($itemStatuses->contains('shipped')) {
                    $currentStatus = 'shipped';
                } elseif ($itemStatuses->contains('paid')) {
                    $currentStatus = 'paid';
                } else {
                    $currentStatus = 'placed';
                }
            @endphp

            @foreach($statusStages as $stage => $icon)
                @php
                    $active = match ($stage) {
                        'placed' => true,
                        'paid' => in_array($currentStatus, ['paid', 'shipped', 'delivered', 'completed']),
                        'shipped' => in_array($currentStatus, ['shipped', 'delivered', 'completed']),
                        'delivered' => in_array($currentStatus, ['delivered', 'completed']),
                        'completed' => $currentStatus === 'completed',
                        default => false,
                    };

                    $stageTime = match ($stage) {
                        'placed' => $order->created_at,
                        'paid' => $items->filter(fn($i) => strtolower($i->seller_status) === 'paid')
                            ->max('updated_at'),
                        'shipped' => $items->filter(fn($i) => strtolower($i->seller_status) === 'shipped')
                            ->max('updated_at'),
                        'delivered' => $items->filter(fn($i) => strtolower($i->seller_status) === 'delivered')
                            ->max('updated_at'),
                        'completed' => $items->filter(fn($i) => strtolower($i->seller_status) === 'completed')
                            ->max('updated_at'),
                        default => null,
                    };
                @endphp

                <div class="d-flex flex-column align-items-center timeline-stage">
                    <div class="timeline-circle {{ $active ? 'active' : '' }}">
                        <i class="bi bi-{{ $icon }} fs-5"></i>
                    </div>
                    <h6 class="fw-bold mt-2 text-capitalize">{{ $stage }}</h6>
                    <small class="text-muted text-center">
                        @if($stage === 'placed')
                            {{ $order->created_at->timezone('Asia/Kuala_Lumpur')->format('d M, h:i A') }}
                        @elseif($stageTime)
                            {{ \Carbon\Carbon::parse($stageTime)->timezone('Asia/Kuala_Lumpur')->format('d M, h:i A') }}
                        @else
                            {{ $active ? 'Completed' : 'Pending' }}
                        @endif
                    </small>
                </div>
            @endforeach

        </div>
    </div>
</div>

        {{-- Transaction Items --}}
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm rounded-4 border-0 h-100">
                    <div class="card-header bg-light border-0 py-3">
                        <h5 class="fw-bold mb-0">
                            <i class="bi bi-cart3 text-me-2" style="color: #8a9c6a;"></i>
                            Products from {{ $items->first()?->product?->seller->business_name ?? 'Seller' }}
                            ({{ $items->count() }})
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            @foreach($items as $item)
                                @if($item->product)
                                    <div class="d-flex align-items-start mb-3 pb-3 border-bottom"
                                        style="border-color: #e9ecef !important;">
                                        <img src="{{ $item->product->images->first() ? asset('images/' . $item->product->images->first()->image_path) : asset('images/default.jpg') }}"
                                            class="rounded-3 me-3"
                                            style="width: 120px; height: 120px; object-fit: cover; border: 1px solid #e9ecef;">
                                        <div class="flex-grow-1">
                                            <h6 class="fw-semibold text-dark mb-1" style="font-size: 0.95rem;">
                                                {{ $item->product->product_name }}
                                            </h6>
                                            <p class="text-secondary small mb-1">Qty: {{ $item->quantity }}</p>
                                            <div class="text-secondary small mb-1">
                                                <i class="fas fa-tag me-1"></i>
                                                {{ $item->variant && $item->variant !== '' ? $item->variant : 'Standard' }}
                                            </div>
                                            <p class="fw-bold mb-0" style="color: #8a9c6a; font-size: 0.95rem;">
                                                RM {{ number_format($item->price * $item->quantity, 2) }}
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Transaction Summary --}}
            <div class="col-lg-4">
                <div class="card shadow-sm rounded-4 border-0 sticky-top" style="top:20px;">
                    <div class="card-header bg-light border-0 py-3">
                        <h5 class="fw-bold mb-0">Transaction Summary</h5>
                    </div>
                    <div class="card-body">

                        {{-- Calculate subtotal, shipping, and total --}}
                        @php
    // Subtotal of displayed items (per seller)
    $subtotal = $items->sum(fn($item) => $item->price * $item->quantity);

    // Delivery fee based on **entire order**, not just filtered items
    $orderSubtotal = $order->items->sum(fn($item) => $item->price * $item->quantity);
    $delivery = $orderSubtotal >= 150 ? 0 : 10.60;

    // Total for display
    $total = $subtotal + $delivery;
@endphp


                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-secondary">Subtotal</span>
                            <span class="fw-semibold text-dark">RM {{ number_format($subtotal, 2) }}</span>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-secondary">Delivery (inc. 6% SST)</span>
                            <span class="fw-semibold text-dark">RM {{ number_format($delivery, 2) }}</span>
                        </div>

                        <hr class="my-3" style="border-color: #e9ecef;">

                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold text-dark mb-1">Total</h6>
                                <small class="text-secondary">Including delivery</small>
                            </div>
                            <h4 class="fw-bold mb-0" style="color: #8a9c6a;">
                                RM {{ number_format($total, 2) }}
                            </h4>
                        </div>

                        {{-- Payment Info --}}
                        <div class="mt-4">
                            <h6 class="fw-bold mb-3">Payment Information</h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-secondary">Payment Method</span>
                                <span>{{ $transaction->payment_method_label }}</span>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-secondary">Status</span>
                                <span class="badge bg-success">{{ $transaction->status ?? 'Pending' }}</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <style>
            .text-gradient-success {
                background: linear-gradient(45deg, #28a745, #20c997);
                background-clip: text;
            }

            .sticky-top {
                z-index: 1020;
            }

            .card:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
                transition: all 0.2s ease;
            }

            .seller-section {
                border: 1px solid #e9ecef;
                border-radius: 0.75rem;
                padding: 1rem;
                margin-bottom: 1rem;
                background-color: #fff;
            }

            .seller-section .seller-header {
                background-color: #f8f9fa;
                padding: 0.5rem 1rem;
                border-radius: 0.5rem;
                margin-bottom: 0.5rem;
            }

            /* Timeline container and connecting lines */
.timeline-container {
    gap: 0; /* spacing controlled by flex */
}

.timeline-stage {
    position: relative;
    flex: 1; /* spread evenly */
}

.timeline-stage:not(:last-child)::after {
    content: '';
    position: absolute;
    top: 25px; /* half of circle height */
    right: -50%;
    width: 100%;
    height: 4px;
    background-color: #e9ecef;
    z-index: 0;
}

.timeline-stage .timeline-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background-color: #f8f9fa;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1;
    border: 2px solid #e9ecef;
}

.timeline-stage .timeline-circle.active {
    background-color:rgb(224, 221, 120);
    color: #fff;
    border-color:rgb(225, 223, 111);
}
        </style>

    </div>
@endsection