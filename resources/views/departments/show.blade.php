@extends('layouts.app')
@section('title', 'Department Details')
@section('heading', 'Department Details')

@section('content')
    <x-card :title="$department['name']">
        <dl class="row mb-0">
            <dt class="col-sm-3">Description</dt><dd class="col-sm-9">{{ $department['description'] }}</dd>
            <dt class="col-sm-3">Status</dt><dd class="col-sm-9"><x-status-badge :status="$department['status']" /></dd>
            <dt class="col-sm-3">Employees</dt><dd class="col-sm-9">{{ $department['employee_count'] }}</dd>
        </dl>
    </x-card>
    <a href="{{ route('admin.departments.edit', $department['id']) }}" class="btn btn-warning">Edit</a>
    <a href="{{ route('admin.departments.index') }}" class="btn btn-secondary">Back</a>
@endsection
