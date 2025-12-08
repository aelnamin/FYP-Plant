@extends('layouts.app')

@section('content')
<h1>Edit Seller</h1>

<form method="POST" action="{{ route('sellers.update', $seller->id) }}">
    @csrf
    @method('PUT')

    <label>Name:</label>
    <input type="text" name="name" value="{{ $seller->name }}" class="form-control" required>

    <label>Email:</label>
    <input type="email" name="email" value="{{ $seller->email }}" class="form-control" required>

    <button type="submit" class="btn btn-primary mt-3">Update Seller</button>
</form>
@endsection