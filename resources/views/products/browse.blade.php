@extends('layouts.main')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<style>
    /* These styles ONLY affect this browse page */
    .browse-page {
        --browse-primary: #8a9c6a;
        --browse-primary-light: #b8c4a0;
        --browse-primary-lighter: #e8ede1;
        --browse-primary-dark: #6c7a55;
        --browse-text: #2c3e2d;
        --browse-text-light: #5a6d5b;
        --browse-border: #d8dfc9;
        --browse-bg-light: #f9faf8;
    }

    /* ALL styles are scoped to .browse-page */
    .browse-page .filter-container {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        position: sticky;
        top: 20px;
        box-shadow: 0 4px 12px rgba(138, 156, 106, 0.08);
        border: 1px solid var(--browse-border);
    }

    .browse-page .filter-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--browse-primary-lighter);
    }

    .browse-page .filter-header h5 {
        font-weight: 700;
        color: var(--browse-text);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .browse-page .filter-reset-btn {
        background: var(--browse-primary-lighter);
        color: var(--browse-primary-dark);
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.875rem;
        transition: all 0.3s;
    }

    .browse-page .filter-reset-btn:hover {
        background: var(--browse-primary);
        color: white;
        transform: translateY(-1px);
    }

    .browse-page .filter-section {
        margin-bottom: 1.5rem;
    }
    

    .browse-page .filter-section-title {
        color: var(--browse-text);
        font-weight: 600;
        font-size: 0.95rem;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .browse-page .filter-options {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .browse-page .filter-option {
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        color: var(--browse-text-light);
        text-decoration: none;
        transition: all 0.2s;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .browse-page .filter-option:hover {
        background: var(--browse-primary-lighter);
        color: var(--browse-text);
    }

    .browse-page .filter-option.active {
        background: var(--browse-primary);
        color: white;
    }

    .browse-page .price-inputs {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .browse-page .price-input {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid var(--browse-border);
        border-radius: 6px;
        font-size: 0.9rem;
    }

    .browse-page .price-input:focus {
        outline: none;
        border-color: var(--browse-primary);
    }

    .browse-page .price-apply-btn {
        width: 100%;
        background: var(--browse-primary);
        color: white;
        border: none;
        padding: 0.75rem;
        border-radius: 6px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .browse-page .price-apply-btn:hover {
        background: var(--browse-primary-dark);
        transform: translateY(-1px);
    }

    .browse-page .sort-select {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid var(--browse-border);
        border-radius: 6px;
        background: white;
        font-size: 0.9rem;
    }

    .browse-page .sort-select:focus {
        outline: none;
        border-color: var(--browse-primary);
    }

    .browse-page .search-container .form-control {
        border-radius: 8px;
        padding: 0.75rem 1rem;
    }

    .browse-page .search-container .form-control:focus {
        border-color: var(--browse-primary);
        box-shadow: none;
    }

    .browse-page .search-container .btn {
        background: var(--browse-primary);
        border-color: var(--browse-primary);
        padding: 0.75rem 1.5rem;
        font-weight: 600;
    }

    .browse-page .search-container .btn:hover {
        background: var(--browse-primary-dark);
        border-color: var(--browse-primary-dark);
    }

    .browse-page .filter-badge {
        background: var(--browse-primary-lighter);
        color: var(--browse-primary-dark);
        border: 1px solid var(--browse-primary-light);
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .browse-page .product-card {
        border: 1px solid var(--browse-border);
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.3s;
        background: white;
    }

    .browse-page .product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 20px rgba(138, 156, 106, 0.1);
        border-color: var(--browse-primary-light);
    }

    .browse-page .product-card img {
        height: 200px;
        object-fit: cover;
        width: 100%;
    }

    .browse-page .price-badge {
        background: var(--browse-primary);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .browse-page .card-body {
        padding: 1rem;
    }

    .browse-page .care-icon {
        width: 24px;
        height: 24px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 8px;
        background: var(--browse-primary-lighter);
        color: var(--browse-primary-dark);
    }

    /* VERY IMPORTANT: Scoped button styles that won't affect cart */
    .browse-page .btn.browse-success {
        background: var(--browse-primary);
        border-color: var(--browse-primary);
        color: white;
    }

    .browse-page .btn.browse-success:hover {
        background: var(--browse-primary-dark);
        border-color: var(--browse-primary-dark);
    }

    .browse-page .btn.browse-outline-success {
        color: var(--browse-primary);
        border-color: var(--browse-primary);
        background: transparent;
    }

    .browse-page .btn.browse-outline-success:hover {
        background: var(--browse-primary);
        border-color: var(--browse-primary);
        color: white;
    }

    @media (max-width: 992px) {
        .browse-page .filter-container {
            position: relative;
            top: 0;
            margin-bottom: 2rem;
        }
        .browse-page .price-inputs {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="browse-page">
    <div class="container py-4">
        <div class="row">
            <div class="col-lg-3 mb-4">
                <div class="filter-container">
                    <div class="filter-header">
                        <h5><i class="bi bi-funnel"></i> Filters</h5>
                        @php
                            // Safely check if any filters are applied
                            $hasFilters = false;
                            $queryParams = request()->query();
                            $filterParams = ['search', 'category', 'min_price', 'max_price', 'difficulty', 'sunlight', 'growth_stage', 'sort_by'];
                            
                            foreach($filterParams as $param) {
                                if(!empty($queryParams[$param])) {
                                    $hasFilters = true;
                                    break;
                                }
                            }
                        @endphp
                        @if($hasFilters)
                        <a href="{{ route('products.browse') }}" class="filter-reset-btn">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </a>
                        @endif
                    </div>

                    <!-- Category Filter -->
                    <div class="filter-section">
                        <div class="filter-section-title"><i class="bi bi-tag"></i> Categories</div>
                        <div class="filter-options">
                            @php
                                $currentQuery = request()->query();
                                $categoryQuery = $currentQuery;
                                unset($categoryQuery['category']);
                                unset($categoryQuery['page']);
                            @endphp
                            <a href="{{ route('products.browse', $categoryQuery) }}"
                               class="filter-option {{ empty(request('category')) ? 'active' : '' }}">
                                <span><i class="bi bi-grid me-2"></i>All Categories</span>
                                @if(empty(request('category')))<i class="bi bi-check"></i>@endif
                            </a>
                            @foreach($categories as $cat)
                            @php
                                $catQuery = $currentQuery;
                                $catQuery['category'] = $cat->id;
                                unset($catQuery['page']);
                            @endphp
                            <a href="{{ route('products.browse', $catQuery) }}"
                               class="filter-option {{ request('category') == $cat->id ? 'active' : '' }}">
                                <span><i class="bi bi-chevron-right me-2"></i>{{ $cat->category_name }}</span>
                                @if(request('category') == $cat->id)<i class="bi bi-check"></i>@endif
                            </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Filter -->
                    <div class="filter-section">
                        <div class="filter-section-title"><i class="bi bi-currency-dollar"></i> Price Range</div>
                        <form method="GET" action="{{ route('products.browse') }}" id="price-form">
                            <div class="price-inputs">
                                <input type="number" name="min_price" class="price-input" placeholder="Min" value="{{ request('min_price') }}" min="0">
                                <input type="number" name="max_price" class="price-input" placeholder="Max" value="{{ request('max_price') }}" min="0">
                            </div>
                            @php
                                $currentQuery = request()->query();
                                unset($currentQuery['min_price']);
                                unset($currentQuery['max_price']);
                                unset($currentQuery['page']);
                            @endphp
                            @foreach($currentQuery as $key => $value)
                                @if(is_string($key) && is_string($value))
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endif
                            @endforeach
                            <button type="submit" class="price-apply-btn">
                                <i class="bi bi-filter-circle me-2"></i>Apply Price
                            </button>
                        </form>
                    </div>

                    <!-- Other Filters -->
                    @php $filters = [
                        'difficulty' => ['icon' => 'bar-chart', 'label' => 'Difficulty', 'data' => $difficultyLevels],
                        'sunlight' => ['icon' => 'sun', 'label' => 'Sunlight', 'data' => $sunlightOptions],
                        'growth_stage' => ['icon' => 'arrow-up-right-circle', 'label' => 'Growth Stage', 'data' => $growthStages]
                    ] @endphp
                    @foreach($filters as $key => $filter)
                        @if($filter['data'] && $filter['data']->count() > 0)
                        <div class="filter-section">
                            <div class="filter-section-title"><i class="bi bi-{{ $filter['icon'] }}"></i> {{ $filter['label'] }}</div>
                            <div class="filter-options">
                                @php
                                    $currentQuery = request()->query();
                                    $allQuery = $currentQuery;
                                    unset($allQuery[$key]);
                                    unset($allQuery['page']);
                                @endphp
                                <a href="{{ route('products.browse', $allQuery) }}"
                                   class="filter-option {{ empty(request($key)) ? 'active' : '' }}">
                                    <span>All</span>
                                    @if(empty(request($key)))<i class="bi bi-check"></i>@endif
                                </a>
                                @foreach($filter['data'] as $item)
                                @php
                                    $itemQuery = $currentQuery;
                                    $itemQuery[$key] = $item;
                                    unset($itemQuery['page']);
                                @endphp
                                <a href="{{ route('products.browse', $itemQuery) }}"
                                   class="filter-option {{ request($key) == $item ? 'active' : '' }}">
                                    <span>{{ is_string($item) ? ucfirst($item) : $item }}</span>
                                    @if(request($key) == $item)<i class="bi bi-check"></i>@endif
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    @endforeach

                    <!-- Sort By -->
                    <div class="filter-section">
                        <div class="filter-section-title"><i class="bi bi-sort-down"></i> Sort By</div>
                        <form method="GET" action="{{ route('products.browse') }}" id="sort-form">
                            <select name="sort_by" class="sort-select" onchange="this.form.submit()">
                                <option value="latest" {{ request('sort_by') == 'latest' ? 'selected' : '' }}>Latest First</option>
                                <option value="price_low_high" {{ request('sort_by') == 'price_low_high' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_high_low" {{ request('sort_by') == 'price_high_low' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                                <option value="difficulty" {{ request('sort_by') == 'difficulty' ? 'selected' : '' }}>Difficulty</option>
                            </select>
                            @php
                                $currentQuery = request()->query();
                                unset($currentQuery['sort_by']);
                                unset($currentQuery['page']);
                            @endphp
                            @foreach($currentQuery as $key => $value)
                                @if(is_string($key) && is_string($value))
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endif
                            @endforeach
                        </form>
                    </div>
                </div>
            </div>

            <!-- MAIN CONTENT -->
            <div class="col-lg-9">
                <!-- Search -->
                <form method="GET" action="{{ route('products.browse') }}" class="search-container mb-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search products..." value="{{ request('search') }}">
                        <button type="submit" class="btn browse-success"><i class="bi bi-search me-2"></i>Search</button>
                    </div>
                </form>

                <!-- Active Filters -->
                @php 
                $activeFilters = [];
                if(request()->has('category') && !empty(request('category'))) {
                    $category = $categories->where('id', request('category'))->first();
                    if($category) {
                        $activeFilters['category'] = [
                            'icon' => 'tag', 
                            'value' => $category->category_name
                        ];
                    }
                }
                if(request()->has('min_price') || request()->has('max_price')) {
                    $minPrice = request('min_price');
                    $maxPrice = request('max_price');
                    if(!empty($minPrice) || !empty($maxPrice)) {
                        $activeFilters['price'] = [
                            'icon' => 'currency-dollar', 
                            'value' => 'RM' . ($minPrice ?? '0') . ' - RM' . ($maxPrice ?? '∞')
                        ];
                    }
                }
                if(request()->has('difficulty') && !empty(request('difficulty'))) {
                    $activeFilters['difficulty'] = [
                        'icon' => 'bar-chart', 
                        'value' => ucfirst(request('difficulty'))
                    ];
                }
                if(request()->has('sunlight') && !empty(request('sunlight'))) {
                    $activeFilters['sunlight'] = [
                        'icon' => 'sun', 
                        'value' => request('sunlight')
                    ];
                }
                if(request()->has('growth_stage') && !empty(request('growth_stage'))) {
                    $activeFilters['growth_stage'] = [
                        'icon' => 'arrow-up-right-circle', 
                        'value' => ucfirst(request('growth_stage'))
                    ];
                }
                @endphp
                @if(count($activeFilters) > 0)
                <div class="mb-4">
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <small class="text-muted">Active filters:</small>
                        @foreach($activeFilters as $key => $filter)
                            @if(!empty($filter['value']))
                            @php
                                $currentQuery = request()->query();
                                if($key === 'price') {
                                    unset($currentQuery['min_price']);
                                    unset($currentQuery['max_price']);
                                } else {
                                    unset($currentQuery[$key]);
                                }
                                unset($currentQuery['page']);
                            @endphp
                            <span class="filter-badge">
                                <i class="bi bi-{{ $filter['icon'] }} me-1"></i>{{ $filter['value'] }}
                                <a href="{{ route('products.browse', $currentQuery) }}">
                                    <i class="bi bi-x ms-1"></i>
                                </a>
                            </span>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Results Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0"><i class=" style="color: var(--browse-primary);"></i>{{ $products->total() }} product{{ $products->total() != 1 ? 's' : '' }} found</h5>
                    @if(request()->has('sort_by') && request('sort_by') != 'latest')
                    @php
                        $sortLabels = [
                            'price_low_high' => 'Price: Low to High',
                            'price_high_low' => 'Price: High to Low',
                            'name' => 'Name',
                            'difficulty' => 'Difficulty'
                        ];
                        $currentSort = request('sort_by');
                        $sortText = isset($sortLabels[$currentSort]) ? $sortLabels[$currentSort] : '';
                    @endphp
                    @if($sortText)
                    <small class="text-muted">Sorted by {{ $sortText }}</small>
                    @endif
                    @endif
                </div>

                <!-- Products Grid -->
                @if($products->count() > 0)
                <div class="row g-3">
                    @foreach($products as $p)
                    <div class="col-md-6 col-lg-4">
                        <div class="card product-card h-100">
                            <div class="position-relative">
                                <img src="{{ $p->images->first() ? asset('images/' . $p->images->first()->image_path) : asset('images/default.jpg') }}" class="card-img-top" alt="{{ $p->product_name }}">
                                <div class="position-absolute top-0 end-0 m-2"><span class="price-badge bg-primary">RM {{ number_format($p->price, 2) }}</span></div>
                            </div>
                            <div class="card-body">
                                <h6 class="fw-bold mb-2">{{ Str::limit($p->product_name, 30) }}</h6>
                                <small class="text-muted d-block mb-2"><i class="bi bi-tag me-1"></i>{{ $p->category->category_name ?? 'Uncategorized' }}</small>
                                <small class="text-muted d-block mb-3"><i class="bi bi-shop me-1"></i>{{ $p->seller->business_name ?? 'Unknown Seller' }}</small>
                                <div class="border-top pt-3">
                                    @if($p->sunlight_requirement)<div class="d-flex align-items-center mb-2"><span class="care-icon"><i class="bi bi-sun"></i></span><small>{{ $p->sunlight_requirement }}</small></div>@endif
                                    @if($p->watering_frequency)<div class="d-flex align-items-center mb-2"><span class="care-icon"><i class="bi bi-droplet"></i></span><small>Water: {{ $p->watering_frequency }}</small></div>@endif
                                    @if($p->difficulty_level)<div class="d-flex align-items-center mb-2"><span class="care-icon"><i class="bi bi-bar-chart"></i></span><small>{{ ucfirst($p->difficulty_level) }} care</small></div>@endif
                                    @if($p->growth_stage)<div class="d-flex align-items-center"><span class="care-icon"><i class="bi bi-arrow-up-right-circle"></i></span><small>{{ ucfirst($p->growth_stage) }}</small></div>@endif
                                </div>
                            </div>
                            <div class="card-footer bg-white border-0 pt-0">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('products.show', $p->id) }}" class="btn browse-outline-success"><i class="bi bi-info-circle me-1"></i>Details</a>
                                    @if($p->stock_quantity > 0)
    <form action="{{ route('cart.add', $p->id) }}" method="POST">
        @csrf
        <input type="hidden" name="quantity" value="1">
        <button type="submit" class="btn browse-success w-100">
            <i class="bi bi-cart-plus me-1"></i>Add to Cart
        </button>
    </form>
@else
    <button class="btn btn-secondary w-100" disabled>
        <i class="bi bi-x-circle me-1"></i>Out of Stock
    </button>
@endif

                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-4">{{ $products->appends(request()->all())->links() }}</div>
                @else
                <div class="text-center py-5">
                    <i class="bi bi-emoji-frown display-1 text-muted"></i>
                    <h4 class="mt-3">No products found</h4>
                    <p class="text-muted">Try adjusting your filters or search term</p>
                    <a href="{{ route('products.browse') }}" class="btn browse-outline-success mt-3">
                        <i class="bi bi-arrow-clockwise me-2"></i>Clear All Filters
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const priceInputs = document.querySelectorAll('#price-form input[type="number"]');
    let priceTimeout;
    priceInputs.forEach(input => {
        input.addEventListener('input', function() {
            clearTimeout(priceTimeout);
            priceTimeout = setTimeout(() => {
                const minPrice = document.querySelector('input[name="min_price"]').value;
                const maxPrice = document.querySelector('input[name="max_price"]').value;
                if (minPrice || maxPrice) document.getElementById('price-form').submit();
            }, 800);
        });
    });
});
</script>
@endsection