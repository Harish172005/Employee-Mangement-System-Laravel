@extends('layouts.guest')
@section('title', 'Forgot password')

@section('content')
    <x-card title="Reset your password">
        <p class="text-secondary mb-4">Enter the email address linked to your account and we’ll send you a reset link.</p>
        <form method="POST" action="{{ route('password.email') }}" data-validate-form>
            @csrf
            <x-form.input name="email" label="Email" type="email" data-validate="required|email" />
            <button class="btn btn-primary w-100">Send reset link</button>
        </form>
        <div class="mt-3 text-center small">
            <a href="{{ route('login') }}">Return to sign in</a>
        </div>
    </x-card>
@endsection