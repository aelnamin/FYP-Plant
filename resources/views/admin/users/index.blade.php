@extends('layouts.admin-main')

@section('title', 'User Management')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-dark text-white">
        User List
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th width="30">ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th width="100">Role</th>
                    <th width="80">Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($users as $u)
                <tr>
                    <td>{{ $u->id }}</td>
                    <td>{{ $u->name }}</td>
                    <td>{{ $u->email }}</td>
                    <td>{{ ucfirst($u->role) }}</td>
                    <td>
                        <a href="{{ route('admin.users.edit', $u->id) }}"
                            class="btn btn-sm btn-primary">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>
@endsection