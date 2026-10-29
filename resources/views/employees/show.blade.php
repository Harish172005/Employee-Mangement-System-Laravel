@extends('layouts.app')
@section('title', 'Employee Details')
@section('heading', 'Employee Details')

@section('content')
    <x-card :title="$employee->full_name">
        <img src="{{ $employee->photo_url }}" alt="{{ $employee->full_name }}"
     class="rounded-circle border" width="120" height="120" style="object-fit: cover;">
        <dl class="row mb-0">
            <dt class="col-sm-3">Employee Code</dt><dd class="col-sm-9">{{ $employee->employee_code }}</dd>
            <dt class="col-sm-3">Email</dt><dd class="col-sm-9">{{ $employee->email }}</dd>
            <dt class="col-sm-3">Name</dt><dd class="col-sm-9">{{ $employee->full_name }}</dd>
            <dt class="col-sm-3">Phone</dt><dd class="col-sm-9">{{ $employee->phone }}</dd>
            <dt class="col-sm-3">Department</dt><dd class="col-sm-9">{{ $employee->department?->name ?? '-' }}</dd>
            <dt class="col-sm-3">Designation</dt><dd class="col-sm-9">{{ $employee->designation }}</dd>
            <dt class="col-sm-3">Salary</dt><dd class="col-sm-9">{{ number_format((float) $employee->salary, 2) }}</dd>
            <dt class="col-sm-3">Status</dt><dd class="col-sm-9"><x-status-badge :status="$employee->status" /></dd>
            <dt class="col-sm-3">Gender</dt><dd class="col-sm-9">{{ ucfirst($employee->gender->value) }}</dd>
            <dt class="col-sm-3">Skills</dt><dd class="col-sm-9">{{ $employee->skills->pluck('name')->join(', ') ?: 'None' }}</dd>
        </dl>
    </x-card>
    <x-card title="Activity log">
        @forelse ($employee->activityLogs->groupBy('action') as $action => $logs)
            <h6>{{ ucfirst($action) }} ({{ $logs->count() }})</h6>
            <ul>
                @foreach ($logs as $log)
                    <li>{{ $log->description }} — {{ $log->created_at->format('Y-m-d H:i') }}</li>
                @endforeach
            </ul>
        @empty
            <p class="mb-0">No activity recorded.</p>
        @endforelse
    </x-card>
    <a href="{{ route('admin.employees.edit', $employee) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary">Back</a>
@endsection
