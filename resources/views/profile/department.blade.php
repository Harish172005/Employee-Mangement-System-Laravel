@extends('layouts.app')
@section('title', 'My Department')
@section('heading', 'My Department')

@section('content')
    <x-card :title="$department['name']">
        <dl class="row mb-0">
            <dt class="col-sm-3">Description</dt><dd class="col-sm-9">{{ $department['description'] }}</dd>
            <dt class="col-sm-3">Total Employees</dt><dd class="col-sm-9">{{ $department['employee_count'] }}</dd>
            <dt class="col-sm-3">Status</dt><dd class="col-sm-9"><x-status-badge :status="$department['status']" /></dd>
        </dl>
    </x-card>
@endsection
