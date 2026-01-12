@extends('layouts.main')
@section('title', 'Messages')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <!-- Sidebar with conversations -->
            <div class="col-md-4 col-lg-4">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-white border-0 py-4 px-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="mb-0 fw-bold text-dark">Messages</h4>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($sellers as $seller)
                                @php
                                    // Count unread messages but hide badge if this seller is the active chat
                                    $unreadCount = \App\Models\Message::where('sender_id', $seller->id)
        ->where('receiver_id', auth()->id())
        ->whereNull('read_at')
        ->count();

    // Last message in conversation
    $lastMessage = \App\Models\Message::where(function ($query) use ($seller) {
            $query->where('sender_id', $seller->id)
                  ->where('receiver_id', auth()->id());
        })
        ->orWhere(function ($query) use ($seller) {
            $query->where('sender_id', auth()->id())
                  ->where('receiver_id', $seller->id);
        })
        ->latest()
        ->first();
                                @endphp

                                <a href="{{ route('buyer.chats.show', $seller->id) }}" class="list-group-item list-group-item-action border-0 py-3 px-4 position-relative
                                       {{ optional($activeSeller)->id === $seller->id ? 'active-chat' : '' }}">
                                    <div class="d-flex align-items-start">
                                        <!-- Avatar -->
                                        <div class="position-relative me-3">
                                            <img src="{{ $seller->profile_picture ? asset($seller->profile_picture) : asset('images/default.png') }}"
                                                alt="{{ optional($seller->seller)->business_name ?? 'Seller' }}"
                                                class="rounded-circle sidebar-avatar">
                                        </div>

                                        <!-- Conversation details -->
                                        <div class="flex-grow-1 me-2 overflow-hidden">
                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                                <h6 class="mb-0 fw-semibold text-truncate">
                                                    {{ optional($seller->seller)->business_name ?? 'Seller' }}
                                                </h6>
                                              

                                            </div>

                                            @if($lastMessage)
                                                <p class="text-muted small mb-0 text-truncate">
                                                    {{ Str::limit($lastMessage->message ?? $lastMessage->content, 40) }}
                                                </p>
                                            @else
                                                <p class="text-muted small mb-0">No messages yet</p>
                                            @endif
                                        </div>

                                        @if($unreadCount > 0)
    <span class="badge bg-primary rounded-pill position-absolute end-0 me-3"
          style="top: 50%; transform: translateY(-50%);">
        {{ $unreadCount }}
    </span>
@endif

                                    </div>
                                </a>
                            @empty
                                <div class="text-center py-5 px-4">
                                    <div class="mb-4">
                                        <i class="fas fa-comments fa-4x text-light"></i>
                                    </div>
                                    <h5 class="text-muted mb-3">No conversations yet</h5>
                                    <p class="text-muted mb-4">Start a conversation with a seller to see messages here.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main chat area -->
            <div class="col-md-8 col-lg-8">
                <div class="card shadow-sm border-0 rounded-3 h-100 d-flex flex-column">

                    @if($activeSeller)
                        <!-- Chat header -->
                        <div class="card-header bg-white border-bottom d-flex align-items-center">
                            <img src="{{ $activeSeller->profile_picture ? asset($activeSeller->profile_picture) : asset('images/default.png') }}"
                                class="rounded-circle chat-header-avatar">
                            <div class="ms-3">
                                <h6 class="mb-0 fw-bold">{{ optional($activeSeller->seller)->business_name ?? 'Seller' }}</h6>
                                <small class="text-muted"></small>
                            </div>
                        </div>

                        <!-- Messages -->
                        <div class="card-body overflow-auto flex-grow-1 chat-box" id="chatMessages">
                            @forelse($messages as $message)
                                <div
                                    class="mb-3 d-flex {{ $message->sender_id === auth()->id() ? 'justify-content-end' : 'justify-content-start' }}">
                                    <div
                                        class="chat-bubble {{ $message->sender_id === auth()->id() ? 'chat-bubble-right' : 'chat-bubble-left' }}">
                                        {{ $message->message }}
                                        <div class="text-end small text-muted mt-1">
                                        {{ $message->created_at->timezone('Asia/Kuala_Lumpur')->format('g:i A') }}
</div>

                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-muted mt-5">No messages yet</p>
                            @endforelse
                        </div>

                        <!-- Send message -->
                        <div class="card-footer bg-white border-top">
                            <form method="POST" action="{{ route('buyer.chats.send', $activeSeller->id) }}">
                                @csrf
                                <div class="input-group">
                                    <input type="text" name="message" class="form-control" placeholder="Type your message..."
                                        required>
                                    <button class="btn btn-primary"><i class="fas fa-paper-plane"></i></button>
                                </div>
                            </form>
                        </div>
                    @else
                        <!-- Empty state -->
                        <div class="card-body d-flex flex-column justify-content-center align-items-center text-center py-5">
                            <i class="fas fa-comment-alt fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Select a conversation</h5>
                            <p class="text-muted mb-0">Choose a chat from the sidebar</p>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>

    <!-- New Message Modal -->
    <div class="modal fade" id="newMessageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">New Message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Select Seller</label>
                        <select class="form-select" id="sellerSelect">
                            <option selected disabled>Choose a seller...</option>
                            @foreach($allSellers ?? [] as $seller)
                                <option value="{{ $seller->id }}">
                                    {{ optional($seller->seller)->business_name ?? $seller->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Message</label>
                        <textarea class="form-control" rows="3" placeholder="Type your message here..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Send Message</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Highlight active conversation
                const conversationLinks = document.querySelectorAll('.list-group-item');
                const currentId = "{{ optional($activeSeller)->id }}";
                conversationLinks.forEach(link => {
                    if (link.href.includes(currentId)) link.classList.add('active-chat');
                });


                // Auto-scroll chat to bottom
                const chatBox = document.getElementById('chatMessages');
                if (chatBox) chatBox.scrollTop = chatBox.scrollHeight;
            });
        </script>
    @endpush

    <style>
        /* Sidebar */
        .list-group-item {
            transition: all 0.2s ease;
            border-bottom: 1px solid #f0f0f0 !important;
        }

        .list-group-item:hover {
            background-color: #f8f9fa !important;
            transform: translateX(4px);
        }

        .list-group-item.active-chat {
            background-color: #e8f4ff !important;
            border-left: 4px solid rgb(178, 204, 244) !important;
        }

        .sidebar-avatar {
            width: 56px;
            height: 56px;
            object-fit: cover;
            border-radius: 50%;
        }

        
        .chat-header-avatar {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border-radius: 50%;
        }

        
        .chat-box {
            max-height: 450px;
            overflow-y: auto;
            padding: 1rem;
            background: #f8f9fa;
        }

        .chat-bubble {
            padding: 0.5rem 1rem;
            border-radius: 15px;
            max-width: 70%;
            word-wrap: break-word;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .chat-bubble-right {
            background-color:#e9ecef;
            color: #212529;
            border-bottom-right-radius: 0;
        }

        .chat-bubble-left {
            background-color: #e9ecef;
            color: #212529;
            border-bottom-left-radius: 0;
        }

    
        .chat-box::-webkit-scrollbar {
            width: 6px;
        }

        .chat-box::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.2);
            border-radius: 3px;
        }

        .chat-box::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .card {
            border-radius: 12px !important;
        }
    </style>
@endsection