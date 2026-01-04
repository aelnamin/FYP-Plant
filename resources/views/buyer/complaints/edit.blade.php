@extends('layouts.main')

@section('content')
    <h1>Handle Complaint</h1>

    <form action="{{ route('complaints.update', $complaint->id) }}" method="POST">
        @csrf @method('PUT')

        <label>Status:</label>
        <select name="status" class="form-control">
            <option value="pending" {{ $complaint->status == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="in review" {{ $complaint->status == 'in review' ? 'selected' : '' }}>In Review</option>
            <option value="resolved" {{ $complaint->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
        </select>

        <button class="btn btn-primary mt-3">Update</button>
    </form>
@endsection