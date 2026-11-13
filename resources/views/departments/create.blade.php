@extends('layouts.app')
@section('title', 'Add Department')
@section('heading', 'Add Department')

@section('content')
    <x-card title="Department information">
        <form method="POST" data-validate-form action="{{ route('admin.departments.store') }}">
            @csrf
            @include('departments._form', ['department' => null])
            <div class="d-flex flex-wrap gap-2 mt-3">
                <button class="btn btn-primary">Save department</button>
                <a href="{{ route('admin.departments.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </x-card>
@endsection
