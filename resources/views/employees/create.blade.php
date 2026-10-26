@extends('layouts.app')
@section('title', 'Add Employee')
@section('heading', 'Add Employee')

@section('content')
    <x-card>
        <form method="POST" data-validate-form action="{{ route('admin.employees.store') }}" enctype="multipart/form-data">
            @csrf
            @include('employees._form')
            <button class="btn btn-primary">Save</button>
            <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </x-card>
@endsection
