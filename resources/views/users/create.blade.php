@extends('layouts.app')
@section('title', 'Add User')
@section('heading', 'Add User')

@section('content')

    <x-card>
        <form method="POST" data-validate-form action="{{ route('admin.users.store') }}">
            @csrf
            @include('users._form')
            <button class="btn btn-primary">Save</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </x-card>
@endsection
