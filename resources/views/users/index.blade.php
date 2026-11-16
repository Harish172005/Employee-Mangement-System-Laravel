@extends('layouts.app')
@section('title', 'Users')
@section('heading', 'Users')

@section('content')
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Add User</a>
    </div>

    <x-card>
        <table class="table table-striped mb-0">
            <thead>
                <tr><th>#</th><th>Name</th><th>Email</th><th>Username</th><th>Role</th><th>Status</th><th>Created</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user['name'] }}</td>
                        <td>{{ $user['email'] }}</td>
                        <td>{{ $user['username'] }}</td>
                        <td><span class="badge bg-primary">{{ ucfirst($user['role']) }}</span></td>
                        <td><x-status-badge :status="$user['status']" /></td>
                        <td>{{ $user['created_at'] }}</td>
                        <td>
                            <a href="{{ route('admin.users.edit', $user['username']) }}" class="btn btn-sm btn-warning">Edit</a>
                            @unless ($user['status'] === 'inactive')
                                <form action="{{ route('admin.users.destroy', $user['username']) }}" method="POST" class="d-inline"
                                      data-confirm="Deactivate this user?">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Deactivate</button>
                                </form>
                            @endunless
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </x-card>
@endsection
