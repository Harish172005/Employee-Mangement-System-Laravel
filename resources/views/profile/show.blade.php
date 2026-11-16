@extends('layouts.app')
@section('title', 'My Profile')
@section('heading', 'My Profile')

@section('content')
    <x-card :title="$employee['first_name'] . ' ' . $employee['last_name']">
        <dl class="row mb-0">
            <dt class="col-sm-3">Employee Code</dt><dd class="col-sm-9">{{ $employee['code'] }}</dd>
            <dt class="col-sm-3">Email</dt><dd class="col-sm-9">{{ $employee['email'] }}</dd>
            <dt class="col-sm-3">Phone</dt><dd class="col-sm-9">{{ $employee['phone'] }}</dd>
            <dt class="col-sm-3">Address</dt><dd class="col-sm-9">{{ $employee['address'] }}</dd>
            <dt class="col-sm-3">Department</dt><dd class="col-sm-9">{{ $employee['department'] }}</dd>
            <dt class="col-sm-3">Designation</dt><dd class="col-sm-9">{{ $employee['designation'] }}</dd>
            <dt class="col-sm-3">Date of Joining</dt><dd class="col-sm-9">{{ $employee['date_of_joining'] }}</dd>
            <dt class="col-sm-3">Status</dt><dd class="col-sm-9"><x-status-badge :status="$employee['status']" /></dd>
        </dl>
    </x-card>
    <a href="{{ route('profile.edit') }}" class="btn btn-warning">Edit My Details</a>
@endsection
