@extends('layouts.app')
@section('title', 'Edit Department')
@section('heading', 'Edit Department')

@section('content')
    <x-card title="Department information">
        <form method="POST" data-validate-form action="{{ route('admin.departments.update', $department) }}">
            @csrf
            @method('PUT')
            @include('departments._form', ['department' => $department])
            <div class="d-flex flex-wrap gap-2 mt-3">
                <button class="btn btn-primary">Save changes</button>
                <a href="{{ route('admin.departments.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </x-card>
@endsection
