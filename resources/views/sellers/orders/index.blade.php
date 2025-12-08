@extends('layouts.sellers-main')

@section('title', 'Order Management')

@section('content')
<h4>Orders</h4>

<div class="card shadow-sm mt-3">
    <div class="card-body">
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Order ID</th>
                    <th>Buyer</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($orders as $o)
                <tr>
                    <td>#{{ $o->id }}</td>
                    <td>{{ $o->buyer->name }}</td>
                    <td>{{ ucfirst($o->status) }}</td>
                    <td>{{ $o->created_at->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>
@endsection