@extends('layouts.guest')
@section('title', 'Login')

@section('content')
    <x-card title="Forgot Password">
        <form method="POST" action="{{ route('password.email') }}" data-validate-form>
            @csrf
            <x-form.input name="email" label="Email" type="email" data-validate="required|email" />
            <button class="btn btn-primary w-100">Send Password Reset Link</button>
        </form>
    </x-card>

@endsection