@extends('layouts.app')

@section('content')
<h1>Complaints</h1>

<a href="{{ route('complaints.create') }}" class="btn btn-primary">File Complaint</a>

<table class="table mt-3">
    <thead>
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Message</th>
            <th>Status</th>
            <th>Admin</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($complaints as $c)
        <tr>
            <td>{{ $c->id }}</td>
            <td>{{ $c->user->name }}</td>
            <td>{{ $c->message }}</td>
            <td>{{ $c->status }}</td>
            <td>{{ $c->admin->name ?? 'Not handled' }}</td>
            <td>
                <a href="{{ route('complaints.show', $c->id) }}" class="btn btn-info">View</a>
                <a href="{{ route('complaints.edit', $c->id) }}" class="btn btn-warning">Handle</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection