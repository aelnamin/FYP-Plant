@extends('layouts.sellers-main')
@section('title', 'Buyer Messages')

@section('content')
    <div class="container py-4">
        <h4>Buyer Messages</h4>

        <ul class="list-group">
            @forelse($buyers as $buyer)
                <li class="list-group-item d-flex justify-content-between">
                    <span>{{ $buyer->name }}</span>
                    <a href="{{ route('sellers.chats.show', $buyer->id) }}" class="btn btn-sm btn-outline-primary">
                        Open Chat
                    </a>
                </li>
            @empty
                <li class="list-group-item text-muted">
                    No messages yet
                </li>
            @endforelse
        </ul>
    </div>
@endsection