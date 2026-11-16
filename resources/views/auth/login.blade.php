@extends('layouts.guest')
@section('title', __('messages.login'))

@section('content')
    <x-card :title="__('messages.welcome_back')">
        <p class="text-secondary mb-4">{{ __('messages.welcome_sign_in') }}</p>
        <form method="POST" action="{{ route('login.attempt') }}" data-validate-form>
            @csrf
            <x-form.input name="username" :label="__('messages.username')" type="string" data-validate="required" />
            <x-form.input name="password" :label="__('messages.password')" type="password" data-validate="required" />
            <button class="btn btn-primary w-100">{{ __('messages.sign_in') }}</button>
            <div class="mt-3 text-center small">
                <a href="{{ route('password.request') }}">{{ __('messages.forgot_password_question') }}</a>
            </div>
        </form>
    </x-card>
@endsection
