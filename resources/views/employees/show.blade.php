@extends('layouts.app')
@section('title', 'Employee Details')
@section('heading', 'Employee Details')

@section('content')
    <x-card :title="$employee['first_name'] . ' ' . $employee['last_name']">
        <dl class="row mb-0">
            <dt class="col-sm-3">Employee Code</dt><dd class="col-sm-9">{{ $employee['code'] }}</dd>
            <dt class="col-sm-3">Email</dt><dd class="col-sm-9">{{ $employee['email'] }}</dd>
            <dt class="col-sm-3">Phone</dt><dd class="col-sm-9">{{ $employee['phone'] }}</dd>
            <dt class="col-sm-3">Department</dt><dd class="col-sm-9">{{ $departments[$employee['department_id']] ?? '-' }}</dd>
            <dt class="col-sm-3">Designation</dt><dd class="col-sm-9">{{ $employee['designation'] }}</dd>
            <dt class="col-sm-3">Salary</dt><dd class="col-sm-9">{{ number_format($employee['salary']) }}</dd>
            <dt class="col-sm-3">Status</dt><dd class="col-sm-9"><x-status-badge :status="$employee['status']" /></dd>
        </dl>
    </x-card>
    <a href="{{ route('admin.employees.edit', $employee['id']) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary">Back</a>
@endsection
