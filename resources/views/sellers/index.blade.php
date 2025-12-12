@extends('layouts.app')

@section('content')
<h1>Seller List</h1>

<a href="{{ route('sellers.create') }}" class="btn btn-primary">Add Seller</a>

<table class="table mt-3">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Actions</th>
    </tr>

    @foreach($sellers as $seller)
    <tr>
        <td>{{ $seller->id }}</td>
        <td>{{ $seller->name }}</td>
        <td>{{ $seller->email }}</td>
        <td>
            <a href="{{ route('sellers.inventory.create') }}" class="btn btn-success mb-3">Add New Product</a>
            <a href="{{ route('sellers.inventory.edit', $p->id) }}" class="btn btn-warning btn-sm">Edit</a>
            <a href="{{ route('sellers.inventory.show', $p->id) }}" class="btn btn-info btn-sm">View</a>
            <form action="{{ route('sellers.inventory.delete', $p->id) }}" method="POST" class="d-inline">

                @csrf
                @method('DELETE')
                <button class="btn btn-danger btn-sm" onclick="return confirm('Delete seller?')">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
@endsection