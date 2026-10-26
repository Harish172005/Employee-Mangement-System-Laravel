@extends('layouts.app')
@section('title', 'Add Department')
@section('heading', 'Add Department')

@section('content')
    <x-card>
        <form method="POST" data-validate-form action="{{ route('admin.departments.store') }}">
            @csrf
            @include('departments._form')
            <button class="btn btn-primary">Save</button>
            <a href="{{ route('admin.departments.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </x-card>
@endsection
