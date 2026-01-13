@extends('layouts.main')

@section('title', 'My Complaints')

@section('content')
    <div class="container py-5">

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-primary-50 alert-dismissible fade show rounded-3 shadow-sm mb-4 border-0" role="alert">
                <i class="fas fa-check-circle me-2 text-primary-700"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- No Complaints State -->
        @if($complaints->isEmpty())
            <div class="card border-0 shadow-lg rounded-4 bg-primary-50">
                <div class="card-body p-5 text-center">
                    <div class="mb-4">
                        <i class="fas fa-inbox text-primary-600" style="font-size: 4rem;"></i>
                    </div>
                    <h3 class="text-gray-900 mb-3">No Complaints Yet</h3>
                    <p class="text-primary-700 mb-4">You haven't submitted any complaints. Start by creating your first
                        complaint.</p>
                    <a href="{{ route('complaints.create') }}" class="btn btn-primary px-4 rounded-3">
                        <i class="fas fa-plus me-2"></i>Create Complaint
                    </a>
                </div>
            </div>
        @else
            <!-- Combined Stats & Button Container -->
            <div class="card border-primary-200 shadow-sm rounded-4 mb-5 ms-auto" style="max-width: 400px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Stats Section -->
                        <div class="d-flex align-items-center">
                            <div class="bg-primary-100 rounded-3 p-3 me-3">
                                <i class="fas fa-file-alt text-primary-700 fs-4"></i>
                            </div>
                            <div>
                                <h6 class="text-primary-700 mb-1">Total Complaints</h6>
                                <h2 class="fw-bold text-gray-900 mb-0">{{ $complaints->total() }}</h2>
                            </div>
                        </div>

                        <!-- Button Section -->
                        <a href="{{ route('complaints.create') }}" class="btn btn-primary px-4 rounded-3">
                            <i class="fas fa-plus me-2"></i>New Complaint
                        </a>
                    </div>
                </div>
            </div>

            <!-- Complaints List -->
            <div class="row g-4">
                @foreach($complaints as $complaint)
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4 hover-lift">
                            <div class="card-body p-4">
                                <div class="row align-items-center">
                                    <!-- Complaint Info -->
                                    <div class="col-lg-8">
                                        <div class="d-flex align-items-start">
                                            <div class="bg-primary-100 rounded-3 p-3 me-3">
                                                <i class="fas fa-exclamation-circle text-primary-700"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center flex-wrap gap-2 mb-2">
                                                    <h5 class="fw-bold text-gray-900 mb-0">#{{ $complaint->complaint_id }}</h5>
                                                    @php
                                                        $statusConfig = [
                                                            'pending' => ['bg' => 'bg-warning-soft', 'icon' => 'clock', 'text' => 'text-warning-800'],
                                                            'resolved' => ['bg' => 'bg-success-soft', 'icon' => 'check-circle', 'text' => 'text-success-800'],
                                                            'in_progress' => ['bg' => 'bg-info-soft', 'icon' => 'spinner', 'text' => 'text-info-800']
                                                        ];
                                                        $config = $statusConfig[$complaint->status] ?? ['bg' => 'bg-gray-200', 'icon' => 'question-circle', 'text' => 'text-gray-700'];
                                                    @endphp
                                                    <span
                                                        class="badge {{ $config['bg'] }} {{ $config['text'] }} rounded-pill px-3 py-2">
                                                        <i class="fas fa-{{ $config['icon'] }} me-1"></i>
                                                        {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                                                    </span>
                                                </div>

                                                <div class="mb-3">
                                                    <p class="text-primary-700 mb-2">
                                                        <i class="fas fa-shopping-bag me-2"></i>
                                                        Order #{{ $complaint->order_id ?? 'N/A' }}
                                                    </p>
                                                    <p class="text-gray-800 mb-0">
                                                        {{ Str::limit($complaint->complaint_message, 80) }}
                                                    </p>
                                                </div>

                                                <div class="text-primary-600 small">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    Submitted {{ $complaint->created_at->format('M d, Y') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="col-lg-4">
                                        <div class="d-flex justify-content-lg-end gap-2 mt-3 mt-lg-0">
                                            <a href="{{ route('complaints.show', $complaint) }}"
                                                class="btn btn-outline-primary rounded-3 px-4">
                                                <i class="fas fa-eye me-2"></i>View Details
                                            </a>
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
                <div class="d-flex justify-content-center mt-5">
                    <nav aria-label="Page navigation">
                        {{ $complaints->onEachSide(1)->links('vendor.pagination.bootstrap-5') }}
                    </nav>
                </div>
            @endif
        @endif
    </div>

    <!-- Custom Styles -->
    <style>
        :root {
            /* Base color: #8a9c6a */
            --color-primary-50: #f5f7f0;
            --color-primary-100: #e9edd9;
            --color-primary-200: #d4dcba;
            --color-primary-300: #b8c592;
            --color-primary-400: #9bad72;
            --color-primary-500: #8a9c6a;
            --color-primary-600: #6e8055;
            --color-primary-700: #566546;
            --color-primary-800: #465239;
            --color-primary-900: #3b4530;

            /* Complementary colors for harmony */
            --color-success-50: #f0f7f3;
            --color-success-100: #dcefe4;
            --color-success-soft: #e8f5eb;
            --color-success-800: #2d6b47;

            --color-warning-50: #fef9f0;
            --color-warning-100: #fef0d7;
            --color-warning-soft: #fff4e0;
            --color-warning-800: #9c6c1a;

            --color-info-50: #f0f7fc;
            --color-info-100: #dcedf9;
            --color-info-soft: #e3f2fd;
            --color-info-800: #1a6094;

            /* Neutral colors */
            --color-gray-100: #f3f4f6;
            --color-gray-200: #e5e7eb;
            --color-gray-700: #374151;
            --color-gray-800: #1f2937;
            --color-gray-900: #111827;
        }

        /* Primary Color Utilities */
        .text-primary-50 {
            color: var(--color-primary-50) !important;
        }

        .text-primary-100 {
            color: var(--color-primary-100) !important;
        }

        .text-primary-200 {
            color: var(--color-primary-200) !important;
        }

        .text-primary-300 {
            color: var(--color-primary-300) !important;
        }

        .text-primary-400 {
            color: var(--color-primary-400) !important;
        }

        .text-primary-500 {
            color: var(--color-primary-500) !important;
        }

        .text-primary-600 {
            color: var(--color-primary-600) !important;
        }

        .text-primary-700 {
            color: var(--color-primary-700) !important;
        }

        .text-primary-800 {
            color: var(--color-primary-800) !important;
        }

        .text-primary-900 {
            color: var(--color-primary-900) !important;
        }

        .bg-primary-50 {
            background-color: var(--color-primary-50) !important;
        }

        .bg-primary-100 {
            background-color: var(--color-primary-100) !important;
        }

        .bg-primary-200 {
            background-color: var(--color-primary-200) !important;
        }

        .bg-primary-300 {
            background-color: var(--color-primary-300) !important;
        }

        .bg-primary-400 {
            background-color: var(--color-primary-400) !important;
        }

        .bg-primary-500 {
            background-color: var(--color-primary-500) !important;
        }

        .bg-primary-600 {
            background-color: var(--color-primary-600) !important;
        }

        .bg-primary-700 {
            background-color: var(--color-primary-700) !important;
        }

        .bg-primary-800 {
            background-color: var(--color-primary-800) !important;
        }

        .bg-primary-900 {
            background-color: var(--color-primary-900) !important;
        }

        .border-primary-50 {
            border-color: var(--color-primary-50) !important;
        }

        .border-primary-100 {
            border-color: var(--color-primary-100) !important;
        }

        .border-primary-200 {
            border-color: var(--color-primary-200) !important;
        }

        .border-primary-300 {
            border-color: var(--color-primary-300) !important;
        }

        .border-primary-400 {
            border-color: var(--color-primary-400) !important;
        }

        .border-primary-500 {
            border-color: var(--color-primary-500) !important;
        }

        .border-primary-600 {
            border-color: var(--color-primary-600) !important;
        }

        .border-primary-700 {
            border-color: var(--color-primary-700) !important;
        }

        .border-primary-800 {
            border-color: var(--color-primary-800) !important;
        }

        .border-primary-900 {
            border-color: var(--color-primary-900) !important;
        }

        /* Neutral Color Utilities */
        .text-gray-100 {
            color: var(--color-gray-100) !important;
        }

        .text-gray-200 {
            color: var(--color-gray-200) !important;
        }

        .text-gray-700 {
            color: var(--color-gray-700) !important;
        }

        .text-gray-800 {
            color: var(--color-gray-800) !important;
        }

        .text-gray-900 {
            color: var(--color-gray-900) !important;
        }

        .bg-gray-100 {
            background-color: var(--color-gray-100) !important;
        }

        .bg-gray-200 {
            background-color: var(--color-gray-200) !important;
        }

        .bg-gray-700 {
            background-color: var(--color-gray-700) !important;
        }

        .bg-gray-800 {
            background-color: var(--color-gray-800) !important;
        }

        .bg-gray-900 {
            background-color: var(--color-gray-900) !important;
        }

        /* Status Colors */
        .bg-warning-soft {
            background-color: var(--color-warning-soft) !important;
        }

        .bg-success-soft {
            background-color: var(--color-success-soft) !important;
        }

        .bg-info-soft {
            background-color: var(--color-info-soft) !important;
        }

        .text-warning-800 {
            color: var(--color-warning-800) !important;
        }

        .text-success-800 {
            color: var(--color-success-800) !important;
        }

        .text-info-800 {
            color: var(--color-info-800) !important;
        }

        /* Buttons */
        .btn-primary {
            background-color: var(--color-primary-600) !important;
            border-color: var(--color-primary-600) !important;
            color: white !important;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: var(--color-primary-700) !important;
            border-color: var(--color-primary-700) !important;
            color: white !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(110, 128, 85, 0.3);
        }

        .btn-outline-primary {
            background-color: transparent !important;
            border: 2px solid var(--color-primary-500) !important;
            color: var(--color-primary-700) !important;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background-color: var(--color-primary-600) !important;
            border-color: var(--color-primary-600) !important;
            color: white !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(110, 128, 85, 0.2);
        }

        /* Alerts */
        .alert-primary-50 {
            background-color: var(--color-primary-50) !important;
            border-color: var(--color-primary-200) !important;
            color: var(--color-primary-800) !important;
        }

        /* Cards & Effects */
        .hover-lift {
            transition: all 0.3s ease;
            border-left: 4px solid var(--color-primary-200);
        }

        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(110, 128, 85, 0.15) !important;
            border-left-color: var(--color-primary-500);
        }

        /* Pagination */
        .pagination .page-link {
            color: var(--color-primary-700);
            border-color: var(--color-primary-200);
        }

        .pagination .page-link:hover {
            background-color: var(--color-primary-100);
            border-color: var(--color-primary-500);
            color: var(--color-primary-800);
        }

        .pagination .page-item.active .page-link {
            background-color: var(--color-primary-600);
            border-color: var(--color-primary-600);
            color: white;
        }

        /* Border Radius */
        .rounded-3 {
            border-radius: 0.75rem !important;
        }

        .rounded-4 {
            border-radius: 1rem !important;
        }
    </style>
@endsection