@extends('layouts.guest')
@section('title', 'Login')

@section('content')
    <x-card title="Login">
        <form method="POST" action="{{ route('login.attempt') }}" data-validate-form>
            @csrf
            <x-form.input name="email" label="Email" type="email" data-validate="required|email" />
            <x-form.input name="password" label="Password" type="password" data-validate="required" />
            <button class="btn btn-primary w-100">Login</button>
            <div class="mt-3 text-center">
                <a href="{{ route('password.request') }}">Forgot your password?</a>
            </div>
        </form>
    </x-card>
@endsection
