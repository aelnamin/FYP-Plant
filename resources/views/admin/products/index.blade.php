@extends('layouts.admin-main')

@section('content')
<div class="container mt-4">
    <h2>Manage Products</h2>

    <a href="{{ route('admin.products.create') }}" class="btn btn-primary mb-3">Add New Product</a>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th> <!-- NEW -->
                <th>Product</th>
                <th>Category</th>
                <th>Seller</th>
                <th>Price (RM)</th>
                <th>Stock</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            @foreach($products as $p)
            <tr>
                <td>{{ $p->id }}</td>

                <!-- SHOW FIRST IMAGE -->
                <td>
                    @if ($p->images->first())
                    <img src="{{ asset('images/' . $p->images->first()->image_path) }}"
                        width="60" height="60" style="object-fit:cover; border-radius:4px;">
                    @else
                    <span class="text-muted">No Image</span>
                    @endif

                </td>

                <td>{{ $p->product_name }}</td>
                <td>{{ $p->category->category_name }}</td>
                <td>{{ $p->seller->business_name ?? 'Unknown' }}</td>
                <td>{{ number_format($p->price, 2) }}</td>
                <td>{{ $p->stock_quantity }}</td>
                <td>{{ $p->approval_status ?? 'Pending' }}</td>
                <td>
                    <a href="{{ route('admin.products.show', $p->id) }}" class="btn btn-info btn-sm">View</a>
                    <a href="{{ route('admin.products.edit', $p->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('admin.products.destroy', $p->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection