@extends('layouts.main')

@section('title', 'My Reviews')

@section('content')
    <div class="container py-5">
        <h2 class="mb-4">My Reviews</h2>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($reviews->count() > 0)
            <div class="list-group">
                @foreach($reviews as $review)
                    <div class="list-group-item mb-3 p-4 shadow-sm rounded">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="mb-0">{{ $review->product->product_name ?? 'Unknown Product' }}</h5>
                            <small class="text-muted">{{ $review->created_at->format('d M Y H:i') }}</small>
                        </div>

                        <div class="mb-2">
                            <span class="badge bg-warning text-dark">Rating: {{ $review->rating }} / 5</span>
                        </div>

                        <p class="mb-2">{{ $review->comment ?? 'No comment provided.' }}</p>

                        <div class="text-end">
                            @if(!$review->updated_at->eq($review->created_at))
                                <span class="text-muted small">Edited</span>
                            @endif
                            <a href="{{ route('buyer.reviews.create', $review->product_id) }}" class="btn btn-sm btn-primary">
                                Edit / Update
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-star fs-1 text-muted"></i>
                <h5 class="mt-3">No reviews submitted yet</h5>
                <p class="text-muted">You haven't submitted any reviews for your purchases.</p>
            </div>
        @endif
    </div>
@endsection