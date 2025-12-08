@extends('layouts.sellers-main')

@section('title', 'Customer Inquiries')

@section('content')
<h4>Customer Inquiries</h4>

@foreach ($inquiries as $i)
<div class="card shadow-sm mb-3">
    <div class="card-body">
        <p><strong>{{ $i->buyer->name }}:</strong> {{ $i->message }}</p>

        <form method="POST" action="{{ route('inquiries.update', $i->id) }}">
            @csrf
            @method('PUT')
            <textarea name="reply" class="form-control mb-2" rows="2" placeholder="Write a reply..."></textarea>
            <button class="btn btn-primary btn-sm">Send Reply</button>
        </form>

    </div>
</div>
@endforeach
@endsection