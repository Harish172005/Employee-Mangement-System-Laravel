@extends('layouts.app')
@section('title', 'Departments')
@section('heading', 'Departments')

@section('content')
    <div class="filter-panel d-flex flex-wrap align-items-start gap-3 justify-content-between mb-3">
        <form method="GET" class="department-filters">
            <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control" placeholder="Search departments...">
            <select name="status" class="form-select">
                <option value="">Any status</option>
                <option value="active" @selected(($filters['status'] ?? '') === 'active')>Active</option>
                <option value="inactive" @selected(($filters['status'] ?? '') === 'inactive')>Inactive</option>
            </select>
            <select name="employees" class="form-select">
                <option value="">Any employee count</option>
                <option value="with" @selected(($filters['employees'] ?? '') === 'with')>Has employees</option>
                <option value="without" @selected(($filters['employees'] ?? '') === 'without')>No employees</option>
            </select>
            <select name="employee_status" class="form-select">
                <option value="">Any employee status</option>
                <option value="active" @selected(($filters['employee_status'] ?? '') === 'active')>Has active employees</option>
                <option value="inactive" @selected(($filters['employee_status'] ?? '') === 'inactive')>Has inactive employees</option>
            </select>
            <button class="btn btn-outline-primary">Search</button>
        </form>
        <a href="{{ route('admin.departments.create') }}" class="btn btn-primary align-self-start text-nowrap">Add Department</a>
    </div>

    <x-card>
        <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr><th>#</th><th>Name</th><th>Description</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse ($departments as $department)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $department->name }}</td>
                        <td>{{ $department->description }}</td>
                        <td><x-status-badge :status="$department->status" /></td>
                        <td class="table-actions">
                            <span class="me-2">{{ $department->employees_count }} employees</span>
                            <a href="{{ route('admin.departments.show', $department) }}" class="btn btn-sm btn-info">View</a>
                            <a href="{{ route('admin.departments.edit', $department) }}" class="btn btn-sm btn-warning">Edit</a>
                            @unless ($department->status->value === 'inactive')
                                <form action="{{ route('admin.departments.destroy', $department) }}" method="POST" class="d-inline"
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
        </div>
    </x-card>
    {{ $departments->links() }}
@endsection
