@extends('layouts.app')
@section('title', 'Department Details')
@section('heading', 'Department Details')

@section('content')
    <x-card :title="$department->name">
        <dl class="row mb-0">
            <dt class="col-sm-3">Description</dt><dd class="col-sm-9">{{ $department->description }}</dd>
            <dt class="col-sm-3">Status</dt><dd class="col-sm-9"><x-status-badge :status="$department->status" /></dd>
            <dt class="col-sm-3">Employees</dt><dd class="col-sm-9">{{ $department->employees_count }}</dd>
        </dl>
        <ul class="mt-3">
            @foreach ($department->employees as $employee)
                <li>{{ $employee->full_name }} ({{ $employee->employee_code }})</li>
            @endforeach
        </ul>
    </x-card>
    <a href="{{ route('admin.departments.edit', $department) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('admin.departments.index') }}" class="btn btn-secondary">Back</a>
@endsection
