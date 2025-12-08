@extends('layouts.app')

@section('content')
<h1>Seller Details</h1>

<p><strong>ID:</strong> {{ $seller->id }}</p>
<p><strong>Name:</strong> {{ $seller->name }}</p>
<p><strong>Email:</strong> {{ $seller->email }}</p>

<a href="{{ route('sellers.index') }}" class="btn btn-secondary">Back</a>
@endsection