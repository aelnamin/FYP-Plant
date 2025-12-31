@extends('layouts.sellers-main')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Edit Plant</h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card bg-white rounded-xl p-6 shadow-md">
            <form action="{{ route('plants.update', $plant->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Plant Name</label>
                        <input type="text" name="product_name" value="{{ old('product_name', $plant->product_name) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Stock Quantity</label>
                        <input type="number" name="stock_quantity"
                            value="{{ old('stock_quantity', $plant->stock_quantity) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Watering Frequency</label>
                        <input type="text" name="watering_frequency"
                            value="{{ old('watering_frequency', $plant->watering_frequency) }}"
                            placeholder="e.g., Daily, Every 3 days"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Sunlight Requirement</label>
                        <input type="text" name="sunlight_requirement"
                            value="{{ old('sunlight_requirement', $plant->sunlight_requirement) }}"
                            placeholder="e.g., Full sun, Partial shade"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Difficulty Level</label>
                        <select name="difficulty_level"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="Easy" {{ old('difficulty_level', $plant->difficulty_level) == 'Easy' ? 'selected' : '' }}>Easy</option>
                            <option value="Medium" {{ old('difficulty_level', $plant->difficulty_level) == 'Medium' ? 'selected' : '' }}>Medium</option>
                            <option value="Hard" {{ old('difficulty_level', $plant->difficulty_level) == 'Hard' ? 'selected' : '' }}>Hard</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('plants.index') }}"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">Update
                        Plant</button>
                </div>
            </form>
        </div>
    </div>
@endsection