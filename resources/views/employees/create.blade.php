@extends('layouts.app')
@section('title', 'Add Employee')
@section('heading', 'Add Employee')

@section('content')
    <x-card title="Employee information">
        <form method="POST" data-validate-form action="{{ route('admin.employees.store') }}" enctype="multipart/form-data">
            @csrf
            @include('employees._form', ['employee' => null])
            <div class="d-flex flex-wrap gap-2 mt-3">
                <button class="btn btn-primary">Save employee</button>
                <a href="{{ route('admin.employees.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </x-card>
@endsection
