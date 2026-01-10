@extends('layouts.main')
@section('title', 'Chat with ' . (optional($seller->seller)->business_name ?? 'Seller'))

@section('content')
    <div class="container py-4">

        <style>
            .chat-box {
                max-height: 400px;
                overflow-y: auto;
                background-color: #f8f9fa;
                border-radius: 10px;
            }

            .chat-bubble {
                padding: 0.5rem 1rem;
                border-radius: 15px;
                max-width: 70%;
                word-wrap: break-word;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            }

            .chat-bubble-right {
                background-color: rgb(148, 181, 116);
                color: white;
                border-bottom-right-radius: 0;
            }

            .chat-bubble-left {
                background-color: #e9ecef;
                color: #212529;
                border-bottom-left-radius: 0;
            }

            #chatMessages::-webkit-scrollbar {
                width: 6px;
            }

            #chatMessages::-webkit-scrollbar-thumb {
                background-color: rgba(0, 0, 0, 0.2);
                border-radius: 3px;
            }
        </style>

        <h4 class="mb-4">Chat with {{ optional($seller->seller)->business_name ?? 'Seller' }}</h4>



        <!-- Chat box -->
        <div class="card shadow-sm mb-3 chat-box" id="chatMessages">
            <div class="card-body p-3">
                @forelse($messages as $msg)
                    <div class="mb-2 {{ $msg->sender_id === auth()->id() ? 'text-end' : 'text-start' }}">
                        <!-- Sender name badge -->
                        <span class="badge bg-{{ $msg->sender_id === auth()->id() ? 'success' : 'secondary' }}">
                            {{ $msg->sender->name }}
                        </span>

                        <!-- Chat bubble -->
                        @if($msg->sender_id == auth()->id())
                            <div class="d-flex justify-content-end mt-1">
                                <div class="chat-bubble chat-bubble-right">
                                    {{ $msg->message }}
                                    <small class="d-block text-end text-muted mt-1">
    {{ $msg->created_at->timezone('Asia/Kuala_Lumpur')->format('d/m/Y g:i A') }}
</small>



                                </div>
                            </div>
                        @else
                            <div class="d-flex justify-content-start mt-1">
                                <div class="chat-bubble chat-bubble-left">
                                    {{ $msg->message }}
                                    <small class="d-block text-start text-muted mt-1">
    {{ $msg->created_at->timezone('Asia/Kuala_Lumpur')->format('d/m/Y g:i A') }}
</small>



                                </div>
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-center text-muted mt-3">No messages yet. Say hello!</p>
                @endforelse

            </div>
        </div>

        <!-- Message input -->
        <form action="{{ route('buyer.chats.send', $seller->id) }}" method="POST">
            @csrf
            <div class="input-group mb-3">
                <input type="text" name="message" class="form-control" placeholder="Type your message..." required>
                <button class="btn btn-success" type="submit"><i class="bi bi-send-fill"></i> Send</button>
            </div>
        </form>
    </div>

    <script>
        // Auto-scroll to bottom
        const chatBox = document.getElementById('chatMessages');
        chatBox.scrollTop = chatBox.scrollHeight;

    </script>

    <script>
        function updateChatTimes() {
            document.querySelectorAll('.chat-time').forEach(el => {
                const time = new Date(el.dataset.time);
                const now = new Date();
                const diff = Math.floor((now - time) / 1000); // in seconds

                if (diff < 60) el.textContent = diff + 's ago';
                else if (diff < 3600) el.textContent = Math.floor(diff / 60) + 'm ago';
                else if (diff < 86400) el.textContent = Math.floor(diff / 3600) + 'h ago';
                else el.textContent = time.toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true, day: '2-digit', month: 'short' });
            });
        }
        setInterval(updateChatTimes, 60000); // update every minute
        updateChatTimes();
    </script>
@endsection