@extends('layouts.app')

@section('content')
<h1>Add New Seller</h1>

<form method="POST" action="{{ route('sellers.store') }}">
    @csrf

    <label>Name:</label>
    <input type="text" name="name" class="form-control" required>

    <label>Email:</label>
    <input type="email" name="email" class="form-control" required>

    <label>Password:</label>
    <input type="password" name="password" class="form-control" required>

    <button type="submit" class="btn btn-success mt-3">Create Seller</button>
</form>
@endsection