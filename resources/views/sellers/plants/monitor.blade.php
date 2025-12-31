@extends('layouts.sellers-main')

@section('title', 'Plant Growth & Care Monitoring | Seller Dashboard')

@section('content')
    <div class="mb-10">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Plant Growth & Care Monitoring</h1>
                <p class="text-gray-600 mt-2">Track and manage your plants' health and development</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <input type="text" placeholder="Search plants..."
                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
                <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-plus mr-2"></i> Add Plant
                </button>
            </div>
        </div>

        <!-- Success Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Plant Selection -->
        <div class="flex flex-wrap gap-4 mb-6">
            <div class="flex-1 min-w-[300px]">
                <label class="block text-gray-700 text-sm font-medium mb-2" for="plant-select">Select Plant</label>
                <select id="plant-select"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">Select a plant...</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" data-product="{{ json_encode($product) }}">
                            {{ $product->product_name }} (ID: PL-{{ str_pad($product->id, 3, '0', STR_PAD_LEFT) }})
                        </option>
                    @endforeach
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
    </div>

    <!-- Growth Log Form -->
    <div id="growth-log-form" class="hidden mb-8 card bg-white rounded-xl p-6 shadow-md">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Log Growth Data</h3>
        <form id="growthForm" method="POST" action="">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">Height (cm)</label>
                    <input type="number" step="0.1" name="height_cm"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">Leaf Count</label>
                    <input type="number" name="leaf_count" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Growth Stage</label>
                    <select name="growth_stage" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="seedling">Seedling</option>
                        <option value="vegetative">Vegetative</option>
                        <option value="flowering">Flowering</option>
                        <option value="fruiting">Fruiting</option>
                        <option value="mature">Mature</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Notes</label>
                    <textarea name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg"></textarea>
                </div>
            </div>
            <div class="mt-4 flex justify-end space-x-3">
                <button type="button" onclick="hideForm('growth-log-form')"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Save Growth Log
                </button>
            </div>
        </form>
    </div>

    <!-- Care Log Form -->
    <div id="care-log-form" class="hidden mb-8 card bg-white rounded-xl p-6 shadow-md">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Log Care Activity</h3>
        <form id="careForm" method="POST" action="">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-2">Care Type</label>
                    <select name="care_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
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
                    <input type="date" name="care_date" value="{{ date('Y-m-d') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Notes</label>
                    <textarea name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg"></textarea>
                </div>
            </div>
            <div class="mt-4 flex justify-end space-x-3">
                <button type="button" onclick="hideForm('care-log-form')"
                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Save Care Log
                </button>
            </div>
        </form>
    </div>

    <!-- Dynamic Content Area -->
    <div id="plant-monitoring-content">
        <!-- Content will be loaded here when a plant is selected -->
    </div>

    <!-- Default Content -->
    <div id="default-content" class="text-center py-16">
        <div class="w-32 h-32 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-seedling text-6xl text-green-600"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Select a Plant to Monitor</h2>
        <p class="text-gray-600 max-w-md mx-auto mb-8">
            Choose a plant from the dropdown above to view its growth and care monitoring data.
        </p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-3xl mx-auto">
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <i class="fas fa-chart-line text-3xl text-green-500 mb-4"></i>
                <h3 class="font-semibold text-gray-800 mb-2">Track Growth</h3>
                <p class="text-gray-600 text-sm">Monitor height, leaf count, and growth stages</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <i class="fas fa-calendar-check text-3xl text-blue-500 mb-4"></i>
                <h3 class="font-semibold text-gray-800 mb-2">Log Care Activities</h3>
                <p class="text-gray-600 text-sm">Record watering, fertilizing, and other care tasks</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <i class="fas fa-chart-bar text-3xl text-purple-500 mb-4"></i>
                <h3 class="font-semibold text-gray-800 mb-2">View Analytics</h3>
                <p class="text-gray-600 text-sm">Analyze growth trends and care patterns</p>
            </div>
        </div>
    </div>

    <!-- Template for plant monitoring content (hidden) -->
    <template id="plant-monitoring-template">
        <!-- This template content will be inserted dynamically -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Growth Monitoring -->
            <div class="lg:col-span-2">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-seedling mr-3 text-green-600"></i> 🌱 Growth Monitoring
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

                    <!-- Leaves Count Card -->
                    <div class="card bg-white rounded-xl p-6 shadow-md">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">Number of Leaves</h3>
                                <p class="text-gray-600 text-sm">Current count</p>
                            </div>
                            <i class="fas fa-leaf text-2xl text-green-500"></i>
                        </div>
                        <div class="mb-4">
                            <div class="flex justify-between mb-1">
                                <span class="text-gray-700" id="leaf-display">0 leaves</span>
                                <span class="font-bold text-green-600" id="leaf-trend">+0%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" id="leaf-progress" style="width: 0%"></div>
                            </div>
                        </div>
                        <div class="text-sm text-gray-600 flex justify-between">
                            <span id="leaf-status">No data</span>
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
                </div>

                <!-- Growth Rate Chart -->
                <div class="card bg-white rounded-xl p-6 shadow-md mb-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-6">Growth Rate Over Time</h3>
                    <div class="chart-container">
                        <canvas id="growthChart"></canvas>
                    </div>
                </div>

                <!-- Care Monitoring Section -->
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-tint mr-3 text-blue-600"></i> 💧 Care Monitoring
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

                    <button onclick="generateReport()"
                        class="w-full mt-6 bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-medium">
                        <i class="fas fa-file-export mr-2"></i> Generate Health Report
                    </button>
                </div>

                <!-- Quick Actions -->
                <div class="card bg-white rounded-xl p-6 shadow-md mb-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-6">Quick Actions</h3>

                    <div class="space-y-4">
                        <button onclick="showCareForm('watering')"
                            class="w-full flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-green-50 hover:border-green-300 transition-colors">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-tint text-blue-600"></i>
                                </div>
                                <div class="text-left">
                                    <div class="font-medium text-gray-800">Log Watering</div>
                                    <div class="text-sm text-gray-600">Record today's watering</div>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </button>

                        <button onclick="showCareForm('fertilizing')"
                            class="w-full flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-green-50 hover:border-green-300 transition-colors">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-flask text-yellow-600"></i>
                                </div>
                                <div class="text-left">
                                    <div class="font-medium text-gray-800">Add Fertilizer</div>
                                    <div class="text-sm text-gray-600">Log fertilizer application</div>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </button>

                        <button onclick="showGrowthForm()"
                            class="w-full flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-green-50 hover:border-green-300 transition-colors">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-clipboard-list text-purple-600"></i>
                                </div>
                                <div class="text-left">
                                    <div class="font-medium text-gray-800">Record Measurements</div>
                                    <div class="text-sm text-gray-600">Update growth metrics</div>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </button>

                        <button onclick="showCareForm('pest_control')"
                            class="w-full flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-green-50 hover:border-green-300 transition-colors">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-bug text-red-600"></i>
                                </div>
                                <div class="text-left">
                                    <div class="font-medium text-gray-800">Report Issue</div>
                                    <div class="text-sm text-gray-600">Log pest or disease</div>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
@endsection

@push('styles')
    <style>
        :root {
            --primary-color: #10b981;
            --secondary-color: #059669;
            --accent-color: #34d399;
        }

        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .progress-bar {
            height: 10px;
            border-radius: 5px;
            background-color: #e5e7eb;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 5px;
            background-color: var(--primary-color);
            transition: width 0.5s ease;
        }

        .stage-indicator {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .seedling-stage {
            background-color: #fef3c7;
            color: #92400e;
        }

        .vegetative-stage {
            background-color: #d1fae5;
            color: #065f46;
        }

        .flowering-stage {
            background-color: #fce7f3;
            color: #9d174d;
        }

        .fruiting-stage {
            background-color: #fde68a;
            color: #92400e;
        }

        .status-good {
            color: #10b981;
        }

        .status-warning {
            color: #f59e0b;
        }

        .status-danger {
            color: #ef4444;
        }

        .chart-container {
            position: relative;
            height: 250px;
            width: 100%;
        }
    </style>
@endpush

@push('scripts')
    <script>
        let growthChart = null;
        let selectedProductId = null;
        let selectedProduct = null;

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function () {
            const plantSelect = document.getElementById('plant-select');

            plantSelect.addEventListener('change', function () {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption.value) {
                    selectedProduct = JSON.parse(selectedOption.getAttribute('data-product'));
                    selectedProductId = selectedOption.value;
                    loadPlantData(selectedProduct);
                } else {
                    showDefaultContent();
                }
            });
        });

        function loadPlantData(product) {
            // Hide default content
            document.getElementById('default-content').classList.add('hidden');

            // Get template and create new content
            const template = document.getElementById('plant-monitoring-template');
            const content = template.content.cloneNode(true);

            // Update plant summary
            content.getElementById('plant-name').textContent = product.product_name;
            content.getElementById('plant-id').textContent = `ID: PL-${String(product.id).padStart(3, '0')}`;
            content.getElementById('summary-watering').textContent = product.watering_frequency || 'Not set';
            content.getElementById('summary-sunlight').textContent = product.sunlight_requirement || 'Not set';
            content.getElementById('summary-difficulty').textContent = product.difficulty_level || 'Not set';
            content.getElementById('summary-stock').textContent = product.stock_quantity || 0;

            // Update forms with correct action URLs
            const growthForm = content.getElementById('growthForm');
            const careForm = content.getElementById('careForm');

            if (growthForm) {
                growthForm.action = `/seller/plants/${product.id}/growth`;
            }

            if (careForm) {
                careForm.action = `/seller/plants/${product.id}/care`;
            }

            // Clear existing content and add new
            const contentArea = document.getElementById('plant-monitoring-content');
            contentArea.innerHTML = '';
            contentArea.appendChild(content);

            // Load growth and care data
            loadGrowthData(product.id);
            loadCareData(product.id);

            // Initialize chart
            initializeGrowthChart();
        }

        function showDefaultContent() {
            document.getElementById('plant-monitoring-content').innerHTML = '';
            document.getElementById('default-content').classList.remove('hidden');
        }

        async function loadGrowthData(productId) {
            try {
                const response = await fetch(`/seller/plants/${productId}/growth-data`);
                const data = await response.json();

                if (data.success) {
                    updateGrowthDisplay(data.data);
                }
            } catch (error) {
                console.error('Error loading growth data:', error);
            }
        }

        async function loadCareData(productId) {
            try {
                const response = await fetch(`/seller/plants/${productId}/care-data`);
                const data = await response.json();

                if (data.success) {
                    updateCareDisplay(data.data);
                }
            } catch (error) {
                console.error('Error loading care data:', error);
            }
        }

        function updateGrowthDisplay(data) {
            const latestLog = data.latest_log;
            const logs = data.logs || [];

            // Update height display
            if (latestLog && latestLog.height_cm) {
                const targetHeight = 25; // You can make this dynamic
                const percentage = Math.min(Math.round((latestLog.height_cm / targetHeight) * 100), 100);
                document.getElementById('height-display').textContent = `${latestLog.height_cm} cm / ${targetHeight} cm`;
                document.getElementById('height-percentage').textContent = `${percentage}%`;
                document.getElementById('height-progress').style.width = `${percentage}%`;
            }

            // Update leaf display
            if (latestLog && latestLog.leaf_count) {
                const maxLeaves = 20; // You can make this dynamic
                const percentage = Math.min(Math.round((latestLog.leaf_count / maxLeaves) * 100), 100);
                document.getElementById('leaf-display').textContent = `${latestLog.leaf_count} leaves`;
                document.getElementById('leaf-progress').style.width = `${percentage}%`;
            }

            // Update growth stage
            if (latestLog && latestLog.growth_stage) {
                const stage = latestLog.growth_stage.toLowerCase();
                const stageIndicator = document.getElementById('stage-indicator');
                stageIndicator.textContent = latestLog.growth_stage;
                stageIndicator.className = 'stage-indicator ' + getStageClass(stage);

                // Update summary stage
                document.getElementById('summary-stage').textContent = latestLog.growth_stage;
                document.getElementById('summary-stage').className = 'stage-indicator ' + getStageClass(stage);
            }

            // Update latest growth log
            if (latestLog) {
                const logHtml = `
                                        <p class="font-medium text-gray-800">${latestLog.growth_stage || 'No stage'}</p>
                                        <p class="text-gray-600 text-sm">Height: ${latestLog.height_cm || 'N/A'} cm</p>
                                        <p class="text-gray-600 text-sm">Leaves: ${latestLog.leaf_count || 'N/A'}</p>
                                        <p class="text-gray-600 text-sm mt-1">${latestLog.notes || 'No notes'}</p>
                                    `;
                document.getElementById('latest-growth-log').innerHTML = logHtml;
                document.getElementById('growth-log-count').textContent = logs.length;
            }
        }

        function updateCareDisplay(data) {
            const latestLog = data.latest_log;
            const logs = data.logs || [];
            const wateringLogs = data.watering_logs || [];

            // Update watering info
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
                document.getElementById('watering-info').innerHTML = wateringHtml;
            }

            // Update latest care log
            if (latestLog) {
                const careDate = new Date(latestLog.care_date).toLocaleDateString();
                const careHtml = `
                                        <p class="font-medium text-gray-800">${formatCareType(latestLog.care_type)}</p>
                                        <p class="text-gray-600 text-sm">Date: ${careDate}</p>
                                        <p class="text-gray-600 text-sm mt-1">${latestLog.notes || 'No notes'}</p>
                                    `;
                document.getElementById('latest-care-log').innerHTML = careHtml;
                document.getElementById('care-log-count').textContent = logs.length;
            }

            // Update care history
            updateCareHistory(logs);
        }

        function updateCareHistory(logs) {
            const historyContainer = document.getElementById('care-history');

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

        function showGrowthForm() {
            document.getElementById('growth-log-form').classList.remove('hidden');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function showCareForm(careType = null) {
            if (careType) {
                document.querySelector('#careForm select[name="care_type"]').value = careType;
            }
            document.getElementById('care-log-form').classList.remove('hidden');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function hideForm(formId) {
            document.getElementById(formId).classList.add('hidden');
        }

        function getStageClass(stage) {
            switch (stage.toLowerCase()) {
                case 'seedling': return 'seedling-stage';
                case 'vegetative': return 'vegetative-stage';
                case 'flowering': return 'flowering-stage';
                case 'fruiting': return 'fruiting-stage';
                case 'mature': return 'fruiting-stage';
                default: return 'seedling-stage';
            }
        }

        function formatCareType(type) {
            return type.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
        }

        function getCareTypeIcon(type) {
            switch (type.toLowerCase()) {
                case 'watering': return 'fas fa-tint';
                case 'fertilizing': return 'fas fa-flask';
                case 'pruning': return 'fas fa-cut';
                case 'repotting': return 'fas fa-seedling';
                case 'pest_control': return 'fas fa-bug';
                case 'disease_treatment': return 'fas fa-plus-square';
                default: return 'fas fa-heart';
            }
        }

        function getCareTypeColor(type) {
            switch (type.toLowerCase()) {
                case 'watering': return { bg: 'bg-blue-100', text: 'text-blue-600' };
                case 'fertilizing': return { bg: 'bg-yellow-100', text: 'text-yellow-600' };
                case 'pruning': return { bg: 'bg-green-100', text: 'text-green-600' };
                case 'repotting': return { bg: 'bg-purple-100', text: 'text-purple-600' };
                case 'pest_control': return { bg: 'bg-red-100', text: 'text-red-600' };
                case 'disease_treatment': return { bg: 'bg-orange-100', text: 'text-orange-600' };
                default: return { bg: 'bg-gray-100', text: 'text-gray-600' };
            }
        }

        function initializeGrowthChart() {
            const ctx = document.getElementById('growthChart');
            if (ctx) {
                if (growthChart) {
                    growthChart.destroy();
                }

                growthChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5', 'Week 6', 'Week 7', 'Week 8'],
                        datasets: [
                            {
                                label: 'Height (cm)',
                                data: [5, 7.2, 9.5, 11.8, 13.5, 15.2, 16.8, 18.5],
                                borderColor: '#10b981',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4
                            },
                            {
                                label: 'Leaves Count',
                                data: [3, 4, 6, 8, 10, 12, 14, 16],
                                borderColor: '#f59e0b',
                                backgroundColor: 'rgba(245, 158, 11, 0.1)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Measurement'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Week'
                                }
                            }
                        }
                    }
                });
            }
        }

        function generateReport() {
            if (!selectedProduct) {
                alert('Please select a plant first.');
                return;
            }

            alert(`Generating health report for ${selectedProduct.product_name}...`);
            // Here you would typically make an API call to generate the report
        }

        // Handle form submissions
        document.addEventListener('submit', function (e) {
            if (e.target.id === 'growthForm' || e.target.id === 'careForm') {
                e.preventDefault();

                const form = e.target;
                const formData = new FormData(form);

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message || 'Log saved successfully!');
                            form.reset();
                            hideForm(form.id + '-form');

                            // Reload the data
                            if (selectedProductId) {
                                loadGrowthData(selectedProductId);
                                loadCareData(selectedProductId);
                            }
                        } else {
                            alert(data.message || 'Error saving log.');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while saving the log.');
                    });
            }
        });
    </script>
@endpush