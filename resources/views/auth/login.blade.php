@extends('layouts.guest')
@section('title', 'Login')

@section('content')
    <x-card title="Welcome back">
        <p class="text-secondary mb-4">Sign in to continue to your employee workspace.</p>
        <form method="POST" action="{{ route('login.attempt') }}" data-validate-form>
            @csrf
            <x-form.input name="username" label="Username" type="string" data-validate="required" />
            <x-form.input name="password" label="Password" type="password" data-validate="required" />
            <button class="btn btn-primary w-100">Sign in</button>
            <div class="mt-3 text-center small">
                <a href="{{ route('password.request') }}">Forgot your password?</a>
            </div>
        </form>
    </x-card>
@endsection
