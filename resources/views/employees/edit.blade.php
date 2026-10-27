@extends('layouts.app')
@section('title', 'Edit Employee')
@section('heading', 'Edit Employee')

@section('content')
    <x-card>
        <form method="POST" data-validate-form action="{{ route('admin.employees.update', $employee) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('employees._form', ['employee' => $employee])
            <button class="btn btn-primary">Update</button>
            <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </x-card>
@endsection
