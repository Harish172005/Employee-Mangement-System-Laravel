
@extends('layouts.app')
@section('title', 'Employees')
@section('heading', 'Employees')

@section('content')
    <div class="d-flex flex-wrap gap-2 justify-content-between mb-3">
        <form method="GET" action="{{ route('admin.employees.index') }}" class="row g-2">
            <input type="text" id="employee-search" name="q" value="{{ $filters['q'] ?? '' }}"
                   class="form-control" placeholder="Search name, email or code..."
                   autocomplete="off" data-search-url="{{ route('admin.employees.search') }}">
            <select name="department" class="form-select">
                <option value="">All departments</option>
                @foreach ($departments as $id => $name)
                    <option value="{{ $id }}" @selected(($filters['department'] ?? '') == $id)>{{ $name }}</option>
                @endforeach
            </select>
            <select name="status" class="form-select">
                <option value="">All statuses</option>
                <option value="active" @selected(($filters['status'] ?? '') === 'active')>Active</option>
                <option value="inactive" @selected(($filters['status'] ?? '') === 'inactive')>Inactive</option>
            </select>
            <select name="gender" class="form-select">
                <option value="">All genders</option>
                <option value="male" @selected(($filters['gender'] ?? '') === 'male')>Male</option>
                <option value="female" @selected(($filters['gender'] ?? '') === 'female')>Female</option>
                <option value="other" @selected(($filters['gender'] ?? '') === 'other')>Other</option>
            </select>
            <select name="sort" class="form-select">
                <option value="last_name" @selected(($filters['sort'] ?? '') === 'last_name')>Sort: Last name</option>
                <option value="employee_code" @selected(($filters['sort'] ?? '') === 'employee_code')>Sort: Code</option>
                <option value="first_name" @selected(($filters['sort'] ?? '') === 'first_name')>Sort: First name</option>
                <option value="salary" @selected(($filters['sort'] ?? '') === 'salary')>Sort: Salary</option>
                <option value="date_of_joining" @selected(($filters['sort'] ?? '') === 'date_of_joining')>Sort: Joining date</option>
            </select>
            <select name="direction" class="form-select">
                <option value="asc" @selected(($filters['direction'] ?? 'asc') === 'asc')>Ascending</option>
                <option value="desc" @selected(($filters['direction'] ?? '') === 'desc')>Descending</option>
            </select>
            <label class="form-check">
                <input class="form-check-input" type="checkbox" name="with_trashed" value="1" @checked(request()->boolean('with_trashed'))>
                <span class="form-check-label">Include deactivated records</span>
            </label>
            <button class="btn btn-outline-secondary">Apply filters</button>
        </form>
        <a href="{{ route('admin.employees.create') }}" class="btn btn-primary">Add Employee</a>
    </div>

    <div class="mb-3"><a href="{{ route('admin.employees.export') }}" class="btn btn-sm btn-outline-success">Export CSV</a></div>

    <x-card>
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr><th>#</th><th>Code</th><th>Name</th><th>Department</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody id="employee-rows">
                    @forelse ($employees as $employee)
                        <tr>
                            <td>{{ $employees->firstItem() + $loop->index }}</td>
                            <td>{{ $employee->employee_code }}</td>
                            <td>{{ $employee->full_name }}</td>
                            <td>{{ $employee->department?->name ?? '-' }}</td>
                            <td><x-status-badge :status="$employee->status" /></td>
                            <td>
                                @if ($employee->trashed() && $employee->status === \App\Enums\Status::Inactive)
                                    <form action="{{ route('admin.employees.restore', $employee) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-success">Restore</button>
                                    </form>
                                    <form action="{{ route('admin.employees.force-delete', $employee) }}" method="POST" class="d-inline" data-confirm="Permanently delete this employee? This cannot be undone.">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Force delete</button>
                                    </form>
                                @else
                                    <a href="{{ route('admin.employees.show', $employee) }}" class="btn btn-sm btn-info">View</a>
                                    <a href="{{ route('admin.employees.edit', $employee) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('admin.employees.destroy', $employee) }}" method="POST"
                                          class="d-inline" data-confirm="Deactivate this employee?">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Deactivate</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">No employees found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    <div id="employee-pagination">{{ $employees->links() }}</div>
@endsection
