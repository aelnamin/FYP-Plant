@extends('layouts.sellers-main')

@section('title', 'Plant Growth & Care Monitoring | Seller Dashboard')

@section('styles')
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


@section('content')
    <div class="container mx-auto px-4 py-8 bg-gray-50">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Plant Growth & Care Monitoring</h1>
            <p class="page-subtitle">Track and manage your plants' health and development</p>
        </div>

        <!-- Success Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Error Messages -->
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        @if($products->isEmpty())
            <!-- No Plants Found Section -->
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-store-slash text-4xl text-yellow-600"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-4">No Plants Found</h2>
                <p class="text-gray-600 max-w-md mx-auto mb-8">
                    @if(auth()->user()->sellerProfile)
                        You don't have any plants listed yet. Add some plants to your inventory to start monitoring them.
                    @else
                        You need to set up your seller profile first before you can add plants.
                    @endif
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @if(auth()->user()->sellerProfile)
                        <a href="{{ route('sellers.products.create') }}"
                            class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            <i class="fas fa-plus mr-2"></i> Add New Plant
                        </a>
                    @else
                        <a href="{{ route('sellers.profile') }}"
                            class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-user-tie mr-2"></i> Complete Seller Profile
                        </a>
                    @endif
                    <a href="{{ route('seller.dashboard') }}"
                        class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        @else
            <!-- Plant Selection -->
            <div class="flex flex-wrap gap-4 mb-6">
                <div class="flex-1 min-w-[300px]">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Select Plant</label>
                    <select id="plant-select"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        <option value="">Select a plant...</option>
                        @forelse($products as $product)
                            <option value="{{ $product->id }}"
                                data-growth-route="{{ route('sellers.plants.growth.store', ['product' => $product->id]) }}"
                                data-care-route="{{ route('sellers.plants.care.store', ['product' => $product->id]) }}"
                                data-product='@json($product)'>
                                {{ $product->product_name }} (ID: PL-{{ str_pad($product->id, 3, '0', STR_PAD_LEFT) }})
                            </option>
                        @empty
                            <option value="" disabled>No plants available</option>
                        @endforelse
                    </select>
                </div>

                <div class="flex-1 min-w-[300px]">
                    <label class="block text-gray-700 text-sm font-medium mb-2" for="date-range">Date Range</label>
                    <select id="date-range"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="7">Last 7 days</option>
                        <option value="30" selected>Last 30 days</option>
                        <option value="90">Last 90 days</option>
                        <option value="all">All time</option>
                    </select>
                </div>
            </div>

            <!-- Replace the existing growth-form-container and care-form-container sections with this: -->

            <!-- Form Section - Two Columns Side by Side -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Left Column: Log Growth Data Form -->
                <div id="growth-form-container" class="hidden card bg-white rounded-xl p-6 shadow-md">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-seedling mr-2 600"></i> Log Growth Data
                    </h3>
                    <form id="growth-log-form" method="POST">
                        @csrf
                        <input type="hidden" name="plant_id" id="growth-plant-id">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-medium mb-2">Height (cm)</label>
                                <input type="number" step="0.1" name="height_cm" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-medium mb-2">Growth Stage</label>
                                <select name="growth_stage" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                    <option value="seedling">Seedling</option>
                                    <option value="vegetative">Vegetative</option>
                                    <option value="flowering">Flowering</option>
                                    <option value="fruiting">Fruiting</option>
                                    <option value="mature">Mature</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-gray-700 text-sm font-medium mb-2">Notes</label>
                                <textarea name="notes" rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"></textarea>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" onclick="hideBothForms()"
                                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center">
                                <i class="fas fa-save mr-2"></i> Save Growth Log
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right Column: Log Care Activity Form -->
                <div id="care-form-container" class="hidden card bg-white rounded-xl p-6 shadow-md">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-tint mr-2 600"></i> Log Care Activity
                    </h3>
                    <form id="care-log-form" method="POST">
                        @csrf
                        <input type="hidden" name="plant_id" id="care-plant-id">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-medium mb-2">Care Type</label>
                                <select name="care_type" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="watering">Watering</option>
                                    <option value="fertilizing">Fertilizing</option>
                                    <option value="pruning">Pruning</option>
                                    <option value="repotting">Repotting</option>
                                    <option value="pest_control">Pest Control</option>
                                    <option value="disease_treatment">Disease Treatment</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-medium mb-2">Care Date</label>
                                <input type="date" name="care_date" value="{{ date('Y-m-d') }}" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-gray-700 text-sm font-medium mb-2">Description / Notes</label>
                                <textarea name="description" rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" onclick="hideBothForms()"
                                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                                <i class="fas fa-save mr-2"></i> Save Care Log
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Dynamic Content Area -->
            <div id="plant-monitoring-content">
                <!-- Content will be loaded here when a plant is selected -->
            </div>

            <!-- Default Content (shown when no plant selected) -->
            <div id="default-content" class="text-center py-16">
                <div class="w-32 h-32 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-seedling text-6xl text-green-600"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Select a Plant to Monitor</h2>
                <p class="text-gray-600 max-w-md mx-auto mb-8">
                    Choose a plant from the dropdown above to view its growth and care monitoring data.
                </p>
        @endif
        </div>

        <!-- Template for plant monitoring content (hidden) - Updated -->
        <template id="plant-monitoring-template">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Growth Monitoring -->
                <div class="lg:col-span-2">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-seedling mr-3 text-green-600"></i> Growth Monitoring
                    </h2>

                    <!-- Growth Metrics Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- Plant Height Card -->
                        <div class="card bg-white rounded-xl p-6 shadow-md">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">Plant Height</h3>
                                    <p class="text-gray-600 text-sm">Current vs Target</p>
                                </div>
                                <i class="fas fa-ruler-vertical text-2xl text-green-500"></i>
                            </div>
                            <div class="mb-4">
                                <div class="flex justify-between mb-1">
                                    <span class="text-gray-700" id="height-display">0 cm / 0 cm</span>
                                    <span class="font-bold text-green-600" id="height-percentage">0%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" id="height-progress" style="width: 0%"></div>
                                </div>
                            </div>
                            <div class="text-sm text-gray-600 flex justify-between">
                                <span id="last-measured">Not measured yet</span>
                                <button onclick="showGrowthForm()" class="text-green-600 hover:text-green-800 font-medium">
                                    Update <i class="fas fa-arrow-right ml-1"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Growth Stage Card -->
                        <div class="card bg-white rounded-xl p-6 shadow-md">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">Growth Stage</h3>
                                    <p class="text-gray-600 text-sm">Current & Next Stage</p>
                                </div>
                                <i class="fas fa-chart-line text-2xl text-green-500"></i>
                            </div>
                            <div class="mb-4">
                                <div class="flex items-center mb-3">
                                    <span id="stage-indicator" class="stage-indicator seedling-stage mr-3">Seedling</span>
                                    <span class="text-gray-700" id="stage-duration">Day 0 of stage</span>
                                </div>
                                <div class="text-sm text-gray-600" id="next-stage">
                                    Next: <span class="font-medium">Not determined</span>
                                </div>
                            </div>
                            <div class="text-sm text-gray-600 flex justify-between">
                                <span id="stage-progress">Progress: 0% of stage</span>
                                <button onclick="showGrowthForm()" class="text-green-600 hover:text-green-800 font-medium">
                                    Update Stage <i class="fas fa-arrow-right ml-1"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Latest Growth Log Card -->
                        <div class="card bg-white rounded-xl p-6 shadow-md">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">Latest Growth Log</h3>
                                    <p class="text-gray-600 text-sm">Most recent entry</p>
                                </div>
                                <i class="fas fa-clipboard-list text-2xl text-green-500"></i>
                            </div>
                            <div class="mb-4">
                                <div id="latest-growth-log">
                                    <p class="text-gray-700">No growth logs yet.</p>
                                </div>
                            </div>
                            <div class="text-sm text-gray-600 flex justify-between">
                                <span>Total logs: <span id="growth-log-count">0</span></span>
                                <button onclick="showGrowthForm()" class="text-green-600 hover:text-green-800 font-medium">
                                    Add Log <i class="fas fa-arrow-right ml-1"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Empty Card (replaces Leaves Count Card) -->
                        <div class="card bg-white rounded-xl p-6 shadow-md">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">Growth Progress</h3>
                                    <p class="text-gray-600 text-sm">Overall development</p>
                                </div>
                                <i class="fas fa-chart-pie text-2xl text-green-500"></i>
                            </div>
                            <div class="mb-4">
                                <div class="flex justify-between mb-1">
                                    <span class="text-gray-700" id="overall-progress">0% complete</span>
                                    <span class="font-bold text-green-600" id="days-in-stage">Day 0</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" id="overall-progress-bar" style="width: 0%"></div>
                                </div>
                            </div>
                            <div class="text-sm text-gray-600 flex justify-between">
                                <span id="growth-trend">No trend data</span>
                                <button onclick="showGrowthForm()" class="text-green-600 hover:text-green-800 font-medium">
                                    Update <i class="fas fa-arrow-right ml-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>



                    <!-- Care Monitoring Section -->
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-tint mr-3 text-blue-600"></i> Care Monitoring
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- Watering Card -->
                        <div class="card bg-white rounded-xl p-6 shadow-md">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">Watering</h3>
                                    <p class="text-gray-600 text-sm">Last watering</p>
                                </div>
                                <i class="fas fa-tint text-2xl text-blue-500"></i>
                            </div>
                            <div class="mb-4">
                                <div id="watering-info">
                                    <p class="text-gray-700">No watering logs yet.</p>
                                </div>
                            </div>
                            <div class="text-sm text-gray-600 flex justify-between">
                                <span id="watering-frequency">Frequency: Not set</span>
                                <button onclick="showCareForm('watering')"
                                    class="text-blue-600 hover:text-blue-800 font-medium">
                                    Log Watering <i class="fas fa-arrow-right ml-1"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Latest Care Log Card -->
                        <div class="card bg-white rounded-xl p-6 shadow-md">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">Latest Care Activity</h3>
                                    <p class="text-gray-600 text-sm">Most recent care</p>
                                </div>
                                <i class="fas fa-heart text-2xl text-red-500"></i>
                            </div>
                            <div class="mb-4">
                                <div id="latest-care-log">
                                    <p class="text-gray-700">No care logs yet.</p>
                                </div>
                            </div>
                            <div class="text-sm text-gray-600 flex justify-between">
                                <span>Total care logs: <span id="care-log-count">0</span></span>
                                <button onclick="showCareForm()" class="text-blue-600 hover:text-blue-800 font-medium">
                                    Add Log <i class="fas fa-arrow-right ml-1"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Care History -->
                        <div class="md:col-span-2 card bg-white rounded-xl p-6 shadow-md">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Care History</h3>
                            <div id="care-history" class="space-y-3">
                                <p class="text-gray-600 text-center py-4">No care history available.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Summary & Actions -->
                <div class="lg:col-span-1">
                    <!-- Plant Summary Card -->
                    <div class="card bg-white rounded-xl p-6 shadow-md mb-8">
                        <h3 class="text-xl font-semibold text-gray-800 mb-6">Plant Summary</h3>

                        <div class="flex items-center mb-6">
                            <div class="w-20 h-20 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-seedling text-4xl text-green-600"></i>
                            </div>
                            <div>
                                <h4 id="plant-name" class="text-lg font-bold text-gray-800">Plant Name</h4>
                                <p id="plant-id" class="text-gray-600">ID: PL-000</p>
                                <div class="flex items-center mt-1">
                                    <span id="summary-stage" class="stage-indicator seedling-stage">Seedling</span>
                                    <span id="planted-date" class="ml-2 text-sm text-gray-600">Planted: Unknown</span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-gray-700">Watering Frequency</span>
                                <span id="summary-watering" class="font-medium">Not set</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-700">Sunlight Requirement</span>
                                <span id="summary-sunlight" class="font-medium">Not set</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-700">Difficulty Level</span>
                                <span id="summary-difficulty" class="font-medium">Not set</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-700">Stock Quantity</span>
                                <span id="summary-stock" class="font-medium">0</span>
                            </div>
                        </div>
                        <br>
                        <a href="{{ route('sellers.plants.care-report', $product->id) }}" class="btn btn-success">
                            Generate Report (PDF)
                        </a>

                    </div>
        </template>

        <script>
            let growthChart = null;
            let selectedProductId = null;
            let selectedProduct = null;
            let growthFormAction = null;
            let careFormAction = null;

            // Initialize when DOM is loaded
            document.addEventListener('DOMContentLoaded', function () {
                const plantSelect = document.getElementById('plant-select');

                // Handle plant selection
                plantSelect.addEventListener('change', function () {
                    const selectedOption = this.options[this.selectedIndex];
                    if (selectedOption.value) {
                        selectedProduct = JSON.parse(selectedOption.getAttribute('data-product'));
                        selectedProductId = selectedOption.value;
                        growthFormAction = selectedOption.getAttribute('data-growth-route');
                        careFormAction = selectedOption.getAttribute('data-care-route');
                        loadPlantData(selectedOption);
                    } else {
                        showDefaultContent();
                    }
                });

                // Handle growth form submission
                document.getElementById('growth-log-form')?.addEventListener('submit', function (e) {
                    e.preventDefault();
                    submitGrowthForm();
                });

                // Handle care form submission
                document.getElementById('care-log-form')?.addEventListener('submit', function (e) {
                    e.preventDefault();
                    submitCareForm();
                });
            });

            // Load plant data and render monitoring cards
            function loadPlantData(option) {
                const product = JSON.parse(option.getAttribute('data-product'));
                const contentArea = document.getElementById('plant-monitoring-content');
                contentArea.innerHTML = '';

                document.getElementById('default-content').classList.add('hidden');

                const template = document.getElementById('plant-monitoring-template');
                const clone = template.content.cloneNode(true);
                contentArea.appendChild(clone);

                // Update plant summary
                const plantName = contentArea.querySelector('#plant-name');
                const plantId = contentArea.querySelector('#plant-id');
                const summaryWatering = contentArea.querySelector('#summary-watering');
                const summarySunlight = contentArea.querySelector('#summary-sunlight');
                const summaryDifficulty = contentArea.querySelector('#summary-difficulty');
                const summaryStock = contentArea.querySelector('#summary-stock');

                if (plantName) plantName.textContent = product.product_name;
                if (plantId) plantId.textContent = `ID: PL-${String(product.id).padStart(3, '0')}`;
                if (summaryWatering) summaryWatering.textContent = product.watering_frequency || 'Not set';
                if (summarySunlight) summarySunlight.textContent = product.sunlight_requirement || 'Not set';
                if (summaryDifficulty) summaryDifficulty.textContent = product.difficulty_level || 'Not set';
                if (summaryStock) summaryStock.textContent = product.stock_quantity || 0;

                // Load data
                loadGrowthData(product.id);
                loadCareData(product.id);
                initializeGrowthChart();
            }

            // Show Growth Form
            function showGrowthForm() {
                const formContainer = document.getElementById('growth-form-container');
                const careFormContainer = document.getElementById('care-form-container');
                const plantIdInput = document.getElementById('growth-plant-id');

                formContainer.classList.remove('hidden');
                careFormContainer.classList.add('hidden');

                if (plantIdInput && selectedProductId) {
                    plantIdInput.value = selectedProductId;
                }
            }

            // Show Care Form
            function showCareForm(careType = null) {
                const formContainer = document.getElementById('care-form-container');
                const growthFormContainer = document.getElementById('growth-form-container');
                const plantIdInput = document.getElementById('care-plant-id');

                formContainer.classList.remove('hidden');
                growthFormContainer.classList.add('hidden');

                if (plantIdInput && selectedProductId) {
                    plantIdInput.value = selectedProductId;
                }

                if (careType) {
                    const careSelect = document.querySelector('#care-log-form select[name="care_type"]');
                    if (careSelect) careSelect.value = careType;
                }
            }

            // Submit Growth Form via AJAX
            async function submitGrowthForm() {
                if (!growthFormAction || !selectedProductId) {
                    alert('Please select a plant first');
                    return;
                }

                const form = document.getElementById('growth-log-form');
                const formData = new FormData(form);

                try {
                    const response = await fetch(growthFormAction, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (response.ok) {
                        alert('Growth data saved successfully!');
                        hideForm('growth-form-container');
                        form.reset();
                        loadGrowthData(selectedProductId); // Refresh growth data
                    } else {
                        throw new Error(data.message || 'Failed to save growth data');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error saving growth data: ' + error.message);
                }
            }

            // Submit Care Form via AJAX
            async function submitCareForm() {
                if (!careFormAction || !selectedProductId) {
                    alert('Please select a plant first');
                    return;
                }

                const form = document.getElementById('care-log-form');

                // Debug: Check form values
                console.log('Form elements:');
                const formData = new FormData(form);
                for (let [key, value] of formData.entries()) {
                    console.log(`${key}: ${value}`);
                }

                // Check if care_date is in the form
                const careDateInput = form.querySelector('input[name="care_date"]');
                console.log('Care date input value:', careDateInput ? careDateInput.value : 'Not found');

                try {
                    const response = await fetch(careFormAction, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                            // Don't set Content-Type for FormData - let browser set it automatically
                        },
                        body: formData
                    });

                    const data = await response.json();
                    console.log('Server response:', data);

                    if (response.ok) {
                        alert('Care activity saved successfully!');
                        hideForm('care-form-container');
                        form.reset();
                        loadCareData(selectedProductId); // Refresh care data
                    } else {
                        throw new Error(data.message || 'Failed to save care activity');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error saving care activity: ' + error.message);
                }
            }

            // Hide any form
            function hideForm(formId) {
                const form = document.getElementById(formId);
                if (form) form.classList.add('hidden');
            }

            // Fetch growth data
            async function loadGrowthData(productId) {
                try {
                    const response = await fetch(`/seller/plants/${productId}/growth-data`);
                    const data = await response.json();
                    if (data.success) updateGrowthDisplay(data.data);
                } catch (error) {
                    console.error('Error loading growth data:', error);
                }
            }

            // Fetch care data
            async function loadCareData(productId) {
                try {
                    const response = await fetch(`/seller/plants/${productId}/care-data`);
                    const data = await response.json();
                    if (data.success) updateCareDisplay(data.data);
                } catch (error) {
                    console.error('Error loading care data:', error);
                }
            }

            // Update growth display cards
            function updateGrowthDisplay(data) {
                const latestLog = data.latest_log;
                const logs = data.logs || [];

                if (latestLog && latestLog.height_cm) {
                    const targetHeight = 25;
                    const percentage = Math.min(Math.round((latestLog.height_cm / targetHeight) * 100), 100);
                    const heightDisplay = document.getElementById('height-display');
                    const heightPercentage = document.getElementById('height-percentage');
                    const heightProgress = document.getElementById('height-progress');

                    if (heightDisplay) heightDisplay.textContent = `${latestLog.height_cm} cm / ${targetHeight} cm`;
                    if (heightPercentage) heightPercentage.textContent = `${percentage}%`;
                    if (heightProgress) heightProgress.style.width = `${percentage}%`;
                }

                // Update overall progress
                if (latestLog && latestLog.growth_stage) {
                    const stageProgress = document.getElementById('overall-progress');
                    const overallProgressBar = document.getElementById('overall-progress-bar');
                    const daysInStage = document.getElementById('days-in-stage');

                    // Calculate progress based on growth stage
                    let stagePercentage = 0;
                    switch (latestLog.growth_stage.toLowerCase()) {
                        case 'seedling': stagePercentage = 20; break;
                        case 'vegetative': stagePercentage = 40; break;
                        case 'flowering': stagePercentage = 60; break;
                        case 'fruiting': stagePercentage = 80; break;
                        case 'mature': stagePercentage = 100; break;
                    }

                    if (stageProgress) stageProgress.textContent = `${stagePercentage}% complete`;
                    if (overallProgressBar) overallProgressBar.style.width = `${stagePercentage}%`;
                    if (daysInStage) daysInStage.textContent = latestLog.growth_stage;
                }

                if (latestLog && latestLog.growth_stage) {
                    const stage = latestLog.growth_stage.toLowerCase();
                    const stageIndicator = document.getElementById('stage-indicator');
                    const summaryStage = document.getElementById('summary-stage');

                    if (stageIndicator) {
                        stageIndicator.textContent = latestLog.growth_stage;
                        stageIndicator.className = 'stage-indicator ' + getStageClass(stage);
                    }
                    if (summaryStage) {
                        summaryStage.textContent = latestLog.growth_stage;
                        summaryStage.className = 'stage-indicator ' + getStageClass(stage);
                    }
                }

                if (latestLog) {
                    const latestHtml = `
                                                                        <p class="font-medium text-gray-800">${latestLog.growth_stage || 'No stage'}</p>
                                                                        <p class="text-gray-600 text-sm">Height: ${latestLog.height_cm || 'N/A'} cm</p>
                                                                        <p class="text-gray-600 text-sm mt-1">${latestLog.notes || 'No notes'}</p>
                                                                    `;
                    const latestContainer = document.getElementById('latest-growth-log');
                    const countElem = document.getElementById('growth-log-count');
                    if (latestContainer) latestContainer.innerHTML = latestHtml;
                    if (countElem) countElem.textContent = logs.length;
                }
            }

            // Update care display cards
            function updateCareDisplay(data) {
                const latestLog = data.latest_log;
                const logs = data.logs || [];
                const wateringLogs = data.watering_logs || [];

                if (wateringLogs.length > 0) {
                    const latestWatering = wateringLogs[0];
                    const wateringDate = new Date(latestWatering.care_date);
                    const today = new Date();
                    const daysDiff = Math.floor((today - wateringDate) / (1000 * 60 * 60 * 24));

                    const wateringHtml = `
                                                                        <p class="font-medium text-gray-800">Last watered: ${daysDiff} days ago</p>
                                                                        <p class="text-gray-600 text-sm">Date: ${wateringDate.toLocaleDateString()}</p>
                                                                        ${latestWatering.notes ? `<p class="text-gray-600 text-sm">Notes: ${latestWatering.notes}</p>` : ''}
                                                                    `;
                    const wateringContainer = document.getElementById('watering-info');
                    if (wateringContainer) wateringContainer.innerHTML = wateringHtml;
                }

                if (latestLog) {
                    const careDate = new Date(latestLog.care_date).toLocaleDateString();
                    const careHtml = `
                                                                        <p class="font-medium text-gray-800">${formatCareType(latestLog.care_type)}</p>
                                                                        <p class="text-gray-600 text-sm">Date: ${careDate}</p>
                                                                        <p class="text-gray-600 text-sm mt-1">${latestLog.notes || 'No notes'}</p>
                                                                    `;
                    const latestContainer = document.getElementById('latest-care-log');
                    const countElem = document.getElementById('care-log-count');
                    if (latestContainer) latestContainer.innerHTML = careHtml;
                    if (countElem) countElem.textContent = logs.length;
                }

                updateCareHistory(logs);
            }

            // Update recent care history
            function updateCareHistory(logs) {
                const historyContainer = document.getElementById('care-history');
                if (!historyContainer) return;

                if (logs.length === 0) {
                    historyContainer.innerHTML = '<p class="text-gray-600 text-center py-4">No care history available.</p>';
                    return;
                }

                let historyHtml = '';
                logs.slice(0, 5).forEach(log => {
                    const date = new Date(log.care_date).toLocaleDateString();
                    const typeIcon = getCareTypeIcon(log.care_type);
                    const typeColor = getCareTypeColor(log.care_type);

                    historyHtml += `
                                                                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                                                            <div class="w-10 h-10 ${typeColor.bg} rounded-lg flex items-center justify-center mr-3">
                                                                                <i class="${typeIcon} ${typeColor.text}"></i>
                                                                            </div>
                                                                            <div class="flex-1">
                                                                                <div class="font-medium text-gray-800">${formatCareType(log.care_type)}</div>
                                                                                <div class="text-sm text-gray-600">${date} - ${log.notes || 'No notes'}</div>
                                                                            </div>
                                                                        </div>
                                                                    `;
                });

                historyContainer.innerHTML = historyHtml;
            }

            // Helper functions
            function getStageClass(stage) {
                switch (stage) {
                    case 'seedling': return 'seedling-stage';
                    case 'vegetative': return 'vegetative-stage';
                    case 'flowering': return 'flowering-stage';
                    case 'fruiting': return 'fruiting-stage';
                    default: return 'seedling-stage';
                }
            }

            function formatCareType(type) {
                return type.split('_').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ');
            }

            function getCareTypeIcon(type) {
                switch (type) {
                    case 'watering': return 'fas fa-tint';
                    case 'fertilizing': return 'fas fa-flask';
                    case 'pruning': return 'fas fa-cut';
                    case 'repotting': return 'fas fa-seedling';
                    case 'pest_control': return 'fas fa-bug';
                    case 'disease_treatment': return 'fas fa-virus';
                    default: return 'fas fa-heart';
                }
            }

            function getCareTypeColor(type) {
                switch (type) {
                    case 'watering': return { bg: 'bg-blue-100', text: 'text-blue-600' };
                    case 'fertilizing': return { bg: 'bg-yellow-100', text: 'text-yellow-600' };
                    case 'pruning': return { bg: 'bg-green-100', text: 'text-green-600' };
                    case 'repotting': return { bg: 'bg-purple-100', text: 'text-purple-600' };
                    case 'pest_control': return { bg: 'bg-red-100', text: 'text-red-600' };
                    case 'disease_treatment': return { bg: 'bg-orange-100', text: 'text-orange-600' };
                    default: return { bg: 'bg-gray-100', text: 'text-gray-600' };
                }
            }

            // Chart initialization
            function initializeGrowthChart() {
                const ctx = document.getElementById('growthChart')?.getContext('2d');
                if (!ctx) return;

                if (growthChart) growthChart.destroy();

                growthChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                        datasets: [{
                            label: 'Height (cm)',
                            data: [5, 8, 12, 15],
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: true, position: 'top' } },
                        scales: {
                            y: { beginAtZero: true, grid: { drawBorder: false }, ticks: { callback: v => v + ' cm' } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }
        </script>

        <script>
            function generateReport() {
                if (!selectedProductId || !selectedProduct) {
                    alert('Please select a plant first');
                    return;
                }

                const report = `
                            Plant Health Report
                            -------------------
                            Plant Name: ${selectedProduct.product_name}
                            Plant ID: PL-${String(selectedProduct.id).padStart(3, '0')}

                            Growth Stage: ${document.getElementById('summary-stage')?.textContent || 'N/A'}
                            Watering Frequency: ${selectedProduct.watering_frequency || 'Not set'}
                            Sunlight Requirement: ${selectedProduct.sunlight_requirement || 'Not set'}
                            Difficulty Level: ${selectedProduct.difficulty_level || 'Not set'}
                            Stock Quantity: ${selectedProduct.stock_quantity || 0}

                            Generated on: ${new Date().toLocaleString()}
                            `;

                const blob = new Blob([report], { type: 'text/plain' });
                const url = URL.createObjectURL(blob);

                const a = document.createElement('a');
                a.href = url;
                a.download = `plant-health-report-${selectedProduct.id}.txt`;
                a.click();

                URL.revokeObjectURL(url);
            }
        </script>

@endsection
    </body>

    </html>