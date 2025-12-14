@extends('layouts.admin-main')

@section('title', 'User Management')

@section('content')
    <div class="container mt-4">

        <a href="{{ route('admin.users.create') }}" class="btn btn-primary mb-3">Add New User</a>

        @if($users->isEmpty())
            <p class="text-center text-muted">No users found.</p>
        @else

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($users as $u)
                        <tr>
                            <td>{{ $u->id }}</td>
                            <td>{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td>{{ ucfirst($u->role) }}</td>
                            <td>
                                <a href="{{ route('admin.users.edit', $u->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-warning btn-sm">Delete</button>
                                </form>
                                <a href="{{ route('admin.users.reports', $u->id) }}" class="btn btn-primary btn-sm">Reports</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection