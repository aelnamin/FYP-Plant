@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                    <h1 class="fw-bold text-dark mb-3">My Complaint</h1>
                        <p class="mb-0">Track and manage your complaint history</p>
                    </div>
                    <a href="{{ route('complaints.create') }}" class="btn btn-success">
                        <i class="fas fa-plus-circle me-2"></i>New Complaint
                    </a>
                </div>
            </div>
        </div>

        @if($complaints->count() > 0)
            <!-- Status Filter Tabs -->
            <div class="row mb-4">
                <div class="col">
                    <div class="d-flex flex-wrap gap-2">
                        @php
                            $statusCounts = [
                                'pending' => $complaints->where('status', 'pending')->count(),
                                'investigating' => $complaints->where('status', 'investigating')->count(),
                                'resolved' => $complaints->where('status', 'resolved')->count(),
                                'rejected' => $complaints->where('status', 'rejected')->count(),
                            ];
                        @endphp
                    </div>
                </div>
            </div>

            <!-- Complaints Grid -->
            <div class="row" id="complaints-grid">
                @foreach($complaints as $c)
                    @php
                        $statusColors = [
                            'pending' => ['bg' => 'warning', 'text' => 'warning'],
                            'resolved' => ['bg' => 'success', 'text' => 'success'],
                            'investigating' => ['bg' => 'info', 'text' => 'info'],
                            'rejected' => ['bg' => 'danger', 'text' => 'danger']
                        ];
                        $color = $statusColors[strtolower($c->status)] ?? ['bg' => 'secondary', 'text' => 'secondary'];
                    @endphp

                    <div class="col-12 mb-4 complaint-card" data-status="{{ strtolower($c->status) }}">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body p-4">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="me-3">
                                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center"
                                                    style="width: 48px; height: 48px;">
                                                    <i class="fas fa-exclamation-triangle text-primary fs-5"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <h5 class="card-title mb-1">
                                                    Complaint #{{ str_pad($c->complaint_id, 6, '0', STR_PAD_LEFT) }}
                                                </h5>
                                                <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                                                    <span class="badge bg-light text-dark border">
                                                        <i class="fas fa-shopping-bag me-1"></i>
                                                        Order #{{ $c->order_id }}
                                                    </span>
                                                    <span class="text-muted">
                                                        <i class="far fa-calendar me-1"></i>
                                                        {{ $c->created_at->format('M d, Y') }}
                                                    </span>
                                                </div>
                                                @if($c->title)
                                                    <p class="card-text text-muted mb-0">{{ Str::limit($c->title, 120) }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="me-3">
                                                <span
                                                    class="badge bg-{{ $color['bg'] }}-subtle text-{{ $color['text'] }} border border-{{ $color['text'] }}-subtle px-3 py-2">
                                                    <i class="fas fa-circle me-1" style="font-size: 8px"></i>
                                                    {{ ucfirst($c->status) }}
                                                </span>
                                            </div>
                                            <div>
                                                <a href="{{ route('complaints.show', $c) }}" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye me-1"></i>View
                                                </a>
                                            </div>
                                        </div>
                                        @if($c->updated_at && $c->status !== 'pending')
                                            <div class="text-muted small mt-2 text-end">
                                                Updated {{ $c->updated_at->diffForHumans() }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Additional Info Row -->
                                <div class="row mt-3 pt-3 border-top">
                                    <div class="col">
                                        <div class="d-flex justify-content-between">
                                            <div class="text-muted small">
                                                <i class="far fa-clock me-1"></i>
                                                Created {{ $c->created_at->diffForHumans() }}
                                            </div>
                                            <div>
                                                <a href="{{ route('complaints.show', $c) }}" class="text-decoration-none small">
                                                    View Details <i class="fas fa-chevron-right ms-1"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($complaints->hasPages())
                <div class="row mt-4">
                    <div class="col">
                        <div class="card shadow-sm border-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted small">
                                        Showing {{ $complaints->firstItem() ?? 0 }} to {{ $complaints->lastItem() ?? 0 }} of
                                        {{ $complaints->total() }} complaints
                                    </div>
                                    <div>
                                        {{ $complaints->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-5 text-center">
                            <div class="mb-4">
                                <i class="fas fa-clipboard-list fa-4x text-muted mb-4"></i>
                                <h4 class="text-muted mb-2">No complaints yet</h4>
                                <p class="text-muted mb-4">You haven't submitted any complaints. Start by submitting your first
                                    complaint.</p>
                                <a href="{{ route('complaints.create') }}" class="btn btn-primary btn-lg">
                                    <i class="fas fa-plus-circle me-2"></i>Submit Your First Complaint
                                </a>
                            </div>
                            <div class="mt-4 text-muted small">
                                <p class="mb-1">Having issues with an order? We're here to help.</p>
                                <p class="mb-0">Submit a complaint and our support team will assist you.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('styles')
    <style>
        .complaint-card {
            transition: transform 0.2s ease-in-out;
        }

        .complaint-card:hover {
            transform: translateY(-2px);
        }

        .card {
            border-radius: 12px;
            border: 1px solid #e9ecef;
        }

        .badge {
            font-weight: 500;
            padding: 0.35em 0.65em;
        }

        .status-filter .btn {
            border-radius: 20px;
            padding: 0.375rem 1rem;
        }

        .status-filter .btn.active {
            background-color: #0d6efd;
            color: white;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Filter functionality
            const filterButtons = document.querySelectorAll('[data-filter]');
            const complaintCards = document.querySelectorAll('.complaint-card');

            filterButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const filter = this.getAttribute('data-filter');

                    // Update active button
                    filterButtons.forEach(btn => {
                        btn.classList.remove('active');
                        btn.classList.add('btn-outline-secondary');
                        btn.classList.remove(`btn-outline-${getStatusColor(btn.getAttribute('data-filter'))}`);
                    });

                    this.classList.add('active');
                    this.classList.remove('btn-outline-secondary');

                    if (filter !== 'all') {
                        this.classList.add(`btn-outline-${getStatusColor(filter)}`);
                    } else {
                        this.classList.remove('btn-outline-secondary');
                    }

                    // Filter cards
                    complaintCards.forEach(card => {
                        if (filter === 'all' || card.getAttribute('data-status') === filter) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });

            function getStatusColor(status) {
                const colors = {
                    'pending': 'warning',
                    'investigating': 'info',
                    'resolved': 'success',
                    'rejected': 'danger'
                };
                return colors[status] || 'secondary';
            }
        });
    </script>
@endpush