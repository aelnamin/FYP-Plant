@extends('layouts.sellers-main')

@section('content')
    <div class="container mx-auto px-4 py-8">

        {{-- Breadcrumb --}}
        <div class="flex items-center text-sm text-gray-500 mb-6">
            <a href="{{ route('seller.plants.index') }}" class="hover:text-green-600">
                My Plants
            </a>
            <i class="fas fa-chevron-right mx-2"></i>
            <span class="text-gray-700 font-medium">{{ $product->product_name }}</span>
        </div>

        {{-- Header --}}
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-8">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-seedling text-4xl text-green-600"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        {{ $product->product_name }}
                    </h1>
                    <p class="text-gray-600 mt-1">
                        Plant ID: PL-{{ str_pad($product->id, 3, '0', STR_PAD_LEFT) }}
                    </p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex gap-3 mt-4 lg:mt-0">
                <a href="{{ route('seller.plants.monitor', $product->id) }}"
                    class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg">
                    <i class="fas fa-chart-line mr-2"></i> Monitor
                </a>

                <a href="{{ route('seller.plants.edit', $product->id) }}"
                    class="border border-gray-300 hover:bg-gray-100 px-5 py-2 rounded-lg">
                    Edit
                </a>
                </div>
  

        {{-- Main Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Left: Plant Info --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Description --}}
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-3">
                        Description
                    </h2>
                    <p class="text-gray-700 leading-relaxed">
                        {{ $product->description ?? 'No description provided.' }}
                    </p>
                </div>

                {{-- Growth & Care Summary --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Growth --}}
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-seedling mr-2 text-green-600"></i>
                            Growth Summary
                        </h3>

                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Current Stage</span>
                                <span class="font-medium">
                                    {{ ucfirst($product->current_stage ?? 'Not set') }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Last Height</span>
                                <span class="font-medium">
                                    {{ $product->last_height_cm ?? 'N/A' }} cm
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Leaf Count</span>
                                <span class="font-medium">
                                    {{ $product->last_leaf_count ?? 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Care --}}
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-tint mr-2 text-blue-600"></i>
                            Care Summary
                        </h3>

                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Watering</span>
                                <span class="font-medium">
                                    {{ $product->watering_frequency ?? 'Not set' }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Sunlight</span>
                                <span class="font-medium">
                                    {{ $product->sunlight_requirement ?? 'Not set' }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Difficulty</span>
                                <span class="font-medium">
                                    {{ ucfirst($product->difficulty_level ?? 'Not set') }}
                                </span>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Recent Activity --}}
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">
                        Recent Activity
                    </h2>

                    @if($recentActivities->isEmpty())
                        <p class="text-gray-600">
                            No recent growth or care activities recorded.
                        </p>
                    @else
                        <ul class="space-y-3">
                            @foreach($recentActivities as $activity)
                                <li class="flex items-start gap-3 text-sm">
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                        <i class="fas {{ $activity->icon }} text-green-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-gray-800 font-medium">
                                            {{ $activity->title }}
                                        </p>
                                        <p class="text-gray-600">
                                            {{ $activity->description }}
                                        </p>
                                        <p class="text-xs text-gray-400 mt-1">
                                            {{ $activity->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

            </div>

            {{-- Right: Quick Info --}}
            <div class="space-y-6">

                {{-- Stock --}}
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">
                        Stock Information
                    </h3>

                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Available Stock</span>
                        <span class="text-2xl font-bold text-green-600">
                            {{ $product->stock_quantity ?? 0 }}
                        </span>
                    </div>
                </div>

                {{-- Meta --}}
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">
                        Plant Details
                    </h3>

                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Created</span>
                            <span class="font-medium">
                                {{ $product->created_at->format('d M Y') }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Last Updated</span>
                            <span class="font-medium">
                                {{ $product->updated_at->format('d M Y') }}
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection