@extends('layouts.main')
@section('title', 'Messages')

@section('content')
    <div class="container py-4">
        <h4 class="mb-4">Your Messages</h4>

        @forelse($sellers as $seller)
            @php
                $unreadCount = \App\Models\Message::where('sender_id', $seller->id)
                    ->where('receiver_id', auth()->id())
                    ->whereNull('deleted_at')
                    ->count();
            @endphp

            <a href="{{ route('buyer.chats.show', $seller->id) }}" class="text-decoration-none">
                <div class="card mb-3 shadow-sm border-0">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            {{-- Placeholder avatar --}}
                            <div class="rounded-circle bg-success text-white d-flex justify-content-center align-items-center me-3"
                                style="width: 45px; height: 45px;">
                                {{ strtoupper(substr(optional($seller->seller)->business_name ?? 'S', 0, 1)) }}
                            </div>
                            <div>
                                <h6 class="mb-0 text-dark">{{ optional($seller->seller)->business_name ?? 'Seller' }}</h6>
                                <small class="text-muted">Click to chat</small>
                            </div>
                        </div>

                        @if($unreadCount > 0)
                            <span class="badge bg-danger rounded-pill">{{ $unreadCount }}</span>
                        @endif
                    </div>
                </div>
            </a>
        @empty
            <div class="card shadow-sm border-0">
                <div class="card-body text-center text-muted py-4">
                    No messages yet. Start a conversation!
                </div>
            </div>
        @endforelse
    </div>

    <style>
        .card:hover {
            transform: translateY(-2px);
            transition: all 0.2s ease-in-out;
            cursor: pointer;
        }
    </style>
@endsection