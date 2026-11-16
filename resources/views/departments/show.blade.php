@extends('layouts.app')
@section('title', __('messages.department_details'))
@section('heading', __('messages.department_details'))

@section('content')
    <x-card :title="$department->name">
        <dl class="row detail-list mb-0">
            <dt class="col-sm-3">{{ __('messages.description') }}</dt><dd class="col-sm-9">{{ $department->description }}</dd>
            <dt class="col-sm-3">{{ __('messages.status') }}</dt><dd class="col-sm-9"><x-status-badge :status="$department->status" /></dd>
            <dt class="col-sm-3">{{ __('messages.employees') }}</dt><dd class="col-sm-9">{{ $department->employees_count }}</dd>
        </dl>
    </x-card>
    <x-card :title="__('messages.department_employees')">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr><th>{{ __('messages.employee') }}</th><th>{{ __('messages.employee_code') }}</th></tr>
                </thead>
                <tbody>
                    @foreach ($department->employees as $employee)
                        <tr>
                            <td>{{ $employee->full_name }}</td>
                            <td>{{ $employee->employee_code }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('admin.departments.edit', $department) }}" class="btn btn-primary">{{ __('messages.edit_department') }}</a>
        <a href="{{ route('admin.departments.index') }}" class="btn btn-outline-secondary">{{ __('messages.back_to_departments') }}</a>
    </div>
@endsection
