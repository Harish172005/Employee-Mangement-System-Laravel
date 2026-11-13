@extends('layouts.app')
@section('title', 'Department Details')
@section('heading', 'Department Details')

@section('content')
    <x-card :title="$department->name">
        <dl class="row detail-list mb-0">
            <dt class="col-sm-3">Description</dt><dd class="col-sm-9">{{ $department->description }}</dd>
            <dt class="col-sm-3">Status</dt><dd class="col-sm-9"><x-status-badge :status="$department->status" /></dd>
            <dt class="col-sm-3">Employees</dt><dd class="col-sm-9">{{ $department->employees_count }}</dd>
        </dl>
    </x-card>
    <x-card title="Department employees">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr><th>Employee</th><th>Employee code</th></tr>
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
        <a href="{{ route('admin.departments.edit', $department) }}" class="btn btn-primary">Edit department</a>
        <a href="{{ route('admin.departments.index') }}" class="btn btn-outline-secondary">Back to departments</a>
    </div>
@endsection
