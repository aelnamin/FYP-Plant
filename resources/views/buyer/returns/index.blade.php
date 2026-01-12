@extends('layouts.main')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 fw-bold mb-0">My Return Requests</h2>
            <span class="badge bg-light text-dark">{{ count($returns) }}
                request{{ count($returns) !== 1 ? 's' : '' }}</span>
        </div>

        @if($returns->isEmpty())
            <div class="text-center py-5">
                <div class="mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" class="text-muted"
                        viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                        <path
                            d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z" />
                    </svg>
                </div>
                <h5 class="text-muted">No return requests found</h5>
                <p class="text-muted">You haven't submitted any return requests yet.</p>
            </div>
        @else
            <div class="row g-4">
                @foreach($returns as $return)
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
                                <div>
                                    <h6 class="mb-0 fw-semibold">Order #{{ $return->order_id }}</h6>
                                    <small class="text-muted">Requested on {{ $return->created_at->format('M d, Y') }}</small>
                                </div>
                                <span
                                    class="badge rounded-pill 
                                                                                                                                        @if($return->status == 'pending') bg-warning text-dark 
                                                                                                                                        @elseif($return->status == 'processing') bg-info text-dark
                                                                                                                                        @elseif($return->status == 'approved') bg-success text-dark
                                                                                                                                        @elseif($return->status == 'rejected') bg-danger text-dark
                                                                                                                                        @endif">
                                    {{ ucfirst($return->status) }}
                                </span>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label class="form-label text-muted small mb-1">Reason for Return</label>
                                            <p class="mb-0">{{ $return->reason }}</p>
                                        </div>

                                        @if($return->seller_note)
                                            <div class="mt-4 pt-3 border-top">
                                                <label class="form-label text-muted small mb-1">Seller's Response</label>
                                                <div class="alert alert-light border">
                                                    <div class="d-flex align-items-start">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                            fill="currentColor" class="bi bi-info-circle text-info mt-1 me-2"
                                                            viewBox="0 0 16 16">
                                                            <path
                                                                d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                                            <path
                                                                d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z" />
                                                        </svg>
                                                        <div>
                                                            <strong class="d-block mb-1">Note from Seller:</strong>
                                                            {{ $return->seller_note }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="col-md-4">
                                        @if($return->image)
                                            <div class="border rounded p-3 bg-light">
                                                <label class="form-label text-muted small mb-2">Proof Submitted</label>
                                                <a href="{{ asset($return->image) }}" data-fancybox="gallery"
                                                    data-caption="Return proof for Order #{{ $return->order_id }}">
                                                    <img src="{{ asset($return->image) }}" class="img-fluid rounded" alt="Return Proof"
                                                        style="max-height: 200px; object-fit: cover;">
                                                </a>
                                                <div class="mt-2 text-center">
                                                    <small class="text-muted"><i class="bi bi-cursor me-2"></i>Click to enlarge</small>
                                                </div>
                                            </div>
                                        @else
                                            <div class="border rounded p-4 text-center bg-light">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor"
                                                    class="bi bi-image text-muted mb-2" viewBox="0 0 16 16">
                                                    <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z" />
                                                    <path
                                                        d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z" />
                                                </svg>
                                                <p class="small text-muted mb-0">No proof image provided</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    @if(count($returns) > 0)
        <style>
            .card {
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .card:hover {
                transform: translateY(-2px);
                box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .08) !important;
            }
        </style>
    @endif
@endsection