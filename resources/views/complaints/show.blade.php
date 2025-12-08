@extends('layouts.app')

@section('content')
<h1>Complaint Details</h1>

<p><strong>User:</strong> {{ $complaint->user->name }}</p>
<p><strong>Message:</strong> {{ $complaint->message }}</p>
<p><strong>Status:</strong> {{ $complaint->status }}</p>
<p><strong>Handled By:</strong> {{ $complaint->admin->name ?? 'Not handled' }}</p>

<a href="{{ route('complaints.index') }}" class="btn btn-secondary">Back</a>
@endsection