@extends('layouts.app')

@section('content')
<h1>Submit a Complaint</h1>

<form action="{{ route('complaints.store') }}" method="POST">
    @csrf

    <label>Message:</label>
    <textarea name="message" class="form-control"></textarea>

    <button class="btn btn-success mt-3">Send</button>
</form>
@endsection