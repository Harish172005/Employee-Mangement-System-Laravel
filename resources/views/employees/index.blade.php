@extends('layouts.app')
@section('title', 'Employees')
@section('heading', 'Employees')

@section('content')
    <div class="d-flex flex-wrap gap-2 justify-content-between mb-3">
        <form method="GET" action="{{ route('admin.employees.index') }}" class="d-flex">
            <input type="text" id="employee-search" name="q" value="{{ $search }}"
                   class="form-control me-2" placeholder="Search name, code or department..."
                   autocomplete="off" data-search-url="{{ route('admin.employees.search') }}">
            <button class="btn btn-outline-secondary">Search</button>
        </form>
        <a href="{{ route('admin.employees.create') }}" class="btn btn-primary">Add Employee</a>
    </div>

    @if ($search)
        <p class="text-muted">Showing results for "{{ $search }}"</p>
    @endif

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
                            <td>{{ $employee['code'] }}</td>
                            <td>{{ $employee['name'] }}</td>
                            <td>{{ $employee['department'] }}</td>
                            <td><x-status-badge :status="$employee['status']" /></td>
                            <td>
                                <a href="{{ route('admin.employees.show', $employee['id']) }}" class="btn btn-sm btn-info">View</a>
                                <a href="{{ route('admin.employees.edit', $employee['id']) }}" class="btn btn-sm btn-warning">Edit</a>
                                @unless ($employee['status'] === 'inactive')
                                    <form action="{{ route('admin.employees.destroy', $employee['id']) }}" method="POST"
                                          class="d-inline" data-confirm="Deactivate this employee?">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Deactivate</button>
                                    </form>
                                @endunless
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
