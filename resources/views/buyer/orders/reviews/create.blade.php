@extends('layouts.main')

@section('title', 'Leave Review')

@section('content')
    <div class="container mt-5">
        <h3>Leave Review for {{ $product->product_name }}</h3>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if($existingReview)
            <div class="alert alert-info">
                You already reviewed this product. Your rating: {{ $existingReview->rating }}/5
            </div>
        @else
            <form action="{{ route('buyer.reviews.store', $product->id) }}" method="POST">
                @csrf
                <div class="card mb-3 p-3 d-flex flex-row align-items-center">
                    <img src="{{ $product->images->first() ? asset('images/' . $product->images->first()->image_path) : asset('images/default.png') }}"
                        alt="{{ $product->product_name }}" width="100" class="me-3">

                    <div class="flex-grow-1">
                        <label>Rating:</label>
                        <select name="rating" class="form-select mb-2" required>
                            <option value="">Select rating</option>
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}">{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                            @endfor
                        </select>

                        <label>Comment:</label>
                        <textarea name="comment" rows="3" class="form-control"
                            placeholder="Write your review (optional)"></textarea>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Submit Review</button>
            </form>
        @endif
    </div>
@endsection