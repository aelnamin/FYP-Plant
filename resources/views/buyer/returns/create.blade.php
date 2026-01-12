@extends('layouts.main')

@section('title', 'Request Return / Refund')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-7">

                <!-- Header -->
                <div class="text-center mb-5">
                    <a href="{{ route('buyer.returns.index') }}" class="btn btn-outline-dark rounded-pill mb-4 px-4">
                        <i class="fas fa-arrow-left me-2"></i>Back to Returns
                    </a>

                    <h1 class="fw-bold text-dark mb-3">Request Return / Refund</h1>
                    <p class="text-secondary">
                        Submit a return request for an order you’ve purchased
                    </p>
                </div>

                @if($existingReturn)
                <!-- Buyer already submitted return/refund -->
                <div class="alert alert-info text-center p-4">
                    <h5>You have already submitted a return/refund for this order.</h5>
                    <p>Status: <strong>{{ ucfirst($existingReturn->status) }}</strong></p>
                    <a href="{{ route('buyer.returns.index') }}" class="btn btn-primary mt-3 rounded-pill">
                        View All Returns
                    </a>
                </div>
            @else

                <!-- Form Card -->
                <div class="card border-light shadow-sm rounded-3 overflow-hidden">
                    <div class="card-header bg-light border-0 py-4 px-5">
                        <div class="d-flex align-items-center">
                            <div class="rounded-3 p-3 me-3" style="background-color:#e8f0e8;">
                                <i class="fas fa-undo" style="color:#8a9c6a;"></i>
                            </div>
                            <div>
                                <h4 class="fw-bold mb-1">Return Request Form</h4>
                                <p class="text-secondary mb-0">Please provide accurate details</p>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('buyer.returns.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Hidden order -->
                            <input type="hidden" name="order_id" value="{{ $order->id }}">

                            <!-- Order Info -->
                            <div class="mb-4">
                                <strong>Order:</strong> #{{ $order->id }} <br>
                                <small class="text-muted">
                                    Purchased on {{ $order->created_at->format('M d, Y') }}
                                </small>
                            </div>

                            <!-- ✅ REQUEST TYPE (FIXED) -->
                            <div class="mb-5">
                                <label class="fw-bold mb-3 d-block">Request Type *</label>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="request_type" id="refund"
                                        value="refund" {{ old('request_type') == 'refund' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="refund">
                                        Refund (Get money back)
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="request_type" id="replacement"
                                        value="replacement" {{ old('request_type') == 'replacement' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="replacement">
                                        Return & get a new item
                                    </label>
                                </div>

                                @error('request_type')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Reason -->
                            <div class="mb-5">
                                <label class="fw-bold mb-2">Reason for Return *</label>
                                <textarea name="reason" rows="5"
                                    class="form-control rounded-3 @error('reason') is-invalid @enderror"
                                    placeholder="Explain why you want to return this item..."
                                    required>{{ old('reason') }}</textarea>
                                @error('reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Proof Image -->
                            <div class="mb-5">
                                <label class="fw-bold mb-2">Upload Proof (Optional)</label>
                                <input type="file" name="image" class="form-control rounded-3" accept="image/*">
                                <div class="form-text text-secondary">
                                    You may upload photos if the item is damaged or incorrect
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="d-flex justify-content-between border-top pt-4">
                                <a href="{{ route('buyer.returns.index') }}" class="btn btn-outline-dark rounded-pill px-4">
                                    Cancel
                                </a>

                                <button type="submit" class="btn rounded-pill px-4 border-0"
                                    style="background-color:#8a9c6a;color:white;">
                                    <i class="fas fa-paper-plane me-2"></i>Submit Request
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="alert mt-4 p-4 border-0 rounded-3" style="background:#f8f9fa;">
                    <strong>Note:</strong>
                    Sellers will review your request. You will be notified once it’s processed.
                </div>
                @endif

            </div>
        </div>
    </div>
@endsection