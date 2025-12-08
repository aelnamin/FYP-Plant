@extends('layouts.sellers-main')

@section('title', 'Inventory Management')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4>Inventory</h4>
    <a href="{{ route('inventory.create') }}" class="btn btn-primary">+ Add Item</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <th width="80">Edit</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($products as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>
                        @if ($item->status === 'approved')
                        <span class="badge bg-success">Approved</span>
                        @else
                        <span class="badge bg-warning">Pending</span>
                        @endif
                    </td>
                    <td>
                        <a href="#" class="btn btn-sm btn-secondary">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>
@endsection