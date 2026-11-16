@extends('layouts.app')
@section('title', 'Departments')
@section('heading', 'Departments')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <form method="GET" class="d-flex">
            <input type="text" name="q" value="{{ $search }}" class="form-control me-2" placeholder="Search departments...">
            <button class="btn btn-outline-secondary">Search</button>
        </form>
        <a href="{{ route('admin.departments.create') }}" class="btn btn-primary">Add Department</a>
    </div>

    <x-card>
        <table class="table table-striped mb-0">
            <thead>
                <tr><th>#</th><th>Name</th><th>Description</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse ($departments as $department)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $department['name'] }}</td>
                        <td>{{ $department['description'] }}</td>
                        <td><x-status-badge :status="$department['status']" /></td>
                        <td>
                            <a href="{{ route('admin.departments.show', $department['id']) }}" class="btn btn-sm btn-info">View</a>
                            <a href="{{ route('admin.departments.edit', $department['id']) }}" class="btn btn-sm btn-warning">Edit</a>
                            @unless ($department['status'] === 'inactive')
                                <form action="{{ route('admin.departments.destroy', $department['id']) }}" method="POST" class="d-inline"
                                      data-confirm="Deactivate this department?">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Deactivate</button>
                                </form>
                            @endunless
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center">No departments found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </x-card>
@endsection
