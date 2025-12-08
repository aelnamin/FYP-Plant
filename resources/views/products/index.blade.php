@extends('layouts.main')

@section('title', 'All Products')

@section('content')

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-success" style="font-weight: 600;">All Products</h2>
        <a href="{{ route('products.create') }}" class="btn btn-success rounded-pill px-4">
            + Add Product
        </a>
    </div>

    <div class="card shadow-sm rounded-4">
        <div class="card-body p-4">

            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Seller</th>
                        <th>Name</th>
                        <th>Price (RM)</th>
                        <th>Stock</th>
                        <th style="width: 200px;">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->seller->name }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ number_format($product->price, 2) }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>
                            <div class="btn-group" role="group">

                                <a href="{{ route('products.show', $product->id) }}"
                                    class="btn btn-outline-success btn-sm rounded-pill px-3">
                                    View
                                </a>

                                <a href="{{ route('products.edit', $product->id) }}"
                                    class="btn btn-outline-warning btn-sm rounded-pill px-3">
                                    Edit
                                </a>

                                <form action="{{ route('products.destroy', $product->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure?')"
                                    style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                        Delete
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>

        </div>
    </div>

</div>

@endsection