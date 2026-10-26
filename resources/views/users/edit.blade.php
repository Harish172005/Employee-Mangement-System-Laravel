@extends('layouts.app')
@section('title', 'Edit User')
@section('heading', 'Edit User')

@section('content')
    <x-card>
        <form method="POST" data-validate-form action="{{ route('admin.users.update', $user['username']) }}">
            @csrf
            @method('PUT')
            @include('users._form', ['user' => $user])
            <p class="text-muted small">Leave the password empty to keep the current one.</p>
            <button class="btn btn-primary">Update</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </x-card>
@endsection
