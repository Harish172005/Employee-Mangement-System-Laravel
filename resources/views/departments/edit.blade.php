@extends('layouts.app')
@section('title', 'Edit Department')
@section('heading', 'Edit Department')

@section('content')
    <x-card>
        <form method="POST" data-validate-form action="{{ route('admin.departments.update', $department) }}">
            @csrf
            @method('PUT')
            @include('departments._form', ['department' => $department])
            <button class="btn btn-primary">Update</button>
            <a href="{{ route('admin.departments.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </x-card>
@endsection
