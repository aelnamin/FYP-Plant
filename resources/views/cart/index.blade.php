@extends('layouts.main')

@section('content')
<div class="container mx-auto py-10">

    <h1 class="text-3xl font-bold mb-6 text-pink-600">Your Cart</h1>

    @if (count($cart) == 0)
    <div class="bg-white p-6 rounded-xl shadow text-center">
        <h2 class="text-xl text-gray-500">Your cart is empty.</h2>
        <a href="{{ route('products.browse') }}"
            class="mt-4 inline-block bg-green-500 text-white px-4 py-2 rounded-lg">
            Browse Products
        </a>
    </div>
    @else
    <div class="bg-white rounded-xl shadow p-6">
        @foreach ($cart as $id => $item)
        <div class="flex items-center justify-between py-4 border-b">

            {{-- IMAGE --}}
            <img src="{{ asset('images/' . $item['image']) }}"
                class="w-20 h-20 object-cover rounded-lg shadow">

            {{-- PRODUCT DETAILS --}}
            <div class="flex-1 px-4">
                <h2 class="font-semibold text-lg">{{ $item['name'] }}</h2>
                <p class="text-gray-500">RM {{ number_format($item['price'], 2) }}</p>
            </div>

            {{-- QUANTITY --}}
            <div class="flex items-center space-x-3">
                <button class="bg-gray-200 px-3 py-1 rounded">-</button>
                <span class="font-semibold">{{ $item['quantity'] }}</span>
                <button class="bg-gray-200 px-3 py-1 rounded">+</button>
            </div>

            {{-- REMOVE --}}
            <form action="{{ route('cart.remove', $id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="text-red-500 font-medium ml-5">Remove</button>
            </form>

        </div>
        @endforeach

        <div class="flex justify-between items-center mt-6">
            <h2 class="text-xl font-semibold">Total:</h2>

            <span class="text-2xl text-green-600 font-bold">
                RM {{ number_format(collect($cart)->sum(fn($p) => $p['price'] * $p['quantity']), 2) }}
            </span>
        </div>

        <div class="text-right mt-6">
            <a href="{{ route('checkout') }}"
                class="bg-pink-600 text-white px-6 py-3 rounded-lg text-lg shadow-md">
                Proceed to Checkout
            </a>
        </div>
    </div>
    @endif

</div>
@endsection