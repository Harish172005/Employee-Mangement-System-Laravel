@extends('layouts.app')
@section('title', __('messages.departments'))
@section('heading', __('messages.departments'))

@section('content')
    <div class="filter-panel d-flex flex-wrap align-items-start gap-3 justify-content-between mb-3">
        <form method="GET" class="department-filters">
            <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control" placeholder="{{ __('messages.search_departments') }}">
            <select name="status" class="form-select">
                <option value="">{{ __('messages.any_status') }}</option>
                <option value="active" @selected(($filters['status'] ?? '') === 'active')>{{ __('messages.active') }}</option>
                <option value="inactive" @selected(($filters['status'] ?? '') === 'inactive')>{{ __('messages.inactive') }}</option>
            </select>
            <select name="employees" class="form-select">
                <option value="">{{ __('messages.any_employee_count') }}</option>
                <option value="with" @selected(($filters['employees'] ?? '') === 'with')>{{ __('messages.has_employees') }}</option>
                <option value="without" @selected(($filters['employees'] ?? '') === 'without')>{{ __('messages.no_employees') }}</option>
            </select>
            <select name="employee_status" class="form-select">
                <option value="">{{ __('messages.any_employee_status') }}</option>
                <option value="active" @selected(($filters['employee_status'] ?? '') === 'active')>{{ __('messages.has_active_employees') }}</option>
                <option value="inactive" @selected(($filters['employee_status'] ?? '') === 'inactive')>{{ __('messages.has_inactive_employees') }}</option>
            </select>
            <button class="btn btn-outline-primary">{{ __('messages.search') }}</button>
        </form>
        <a href="{{ route('admin.departments.create') }}" class="btn btn-primary align-self-start text-nowrap">{{ __('messages.add_department') }}</a>
    </div>

    <x-card>
        <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr><th>#</th><th>{{ __('messages.name') }}</th><th>{{ __('messages.description') }}</th><th>{{ __('messages.status') }}</th><th>{{ __('messages.actions') }}</th></tr>
            </thead>
            <tbody>
                @forelse ($departments as $department)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $department->name }}</td>
                        <td>{{ $department->description }}</td>
                        <td><x-status-badge :status="$department->status" /></td>
                        <td class="table-actions">
                            <span class="me-2">{{ __('messages.employee_count', ['count' => $department->employees_count]) }}</span>
                            <a href="{{ route('admin.departments.show', $department) }}" class="btn btn-sm btn-info">{{ __('messages.view') }}</a>
                            <a href="{{ route('admin.departments.edit', $department) }}" class="btn btn-sm btn-warning">{{ __('messages.edit') }}</a>
                            @unless ($department->status->value === 'inactive')
                                <form action="{{ route('admin.departments.destroy', $department) }}" method="POST" class="d-inline"
                                      data-confirm="{{ __('messages.deactivate_department_confirm') }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">{{ __('messages.deactivate') }}</button>
                                </form>
                            @endunless
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center">{{ __('messages.no_departments_found') }}</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </x-card>
    {{ $departments->links() }}
@endsection
