@extends('layouts.app')
@section('title', 'Change Password')
@section('heading', 'Change Password')

@section('content')
    <x-card>
        <form method="POST" data-validate-form action="{{ route('profile.password.update') }}">
            @csrf
            @method('PUT')
            <x-form.input name="current_password" label="Current Password" type="password" data-validate="required" />
            <x-form.input name="password" label="New Password" type="password" data-validate="required" />
            <x-form.input name="password_confirmation" label="Confirm New Password" type="password" data-validate="required" />
            <button class="btn btn-primary">Change Password</button>
        </form>
    </x-card>
@endsection
