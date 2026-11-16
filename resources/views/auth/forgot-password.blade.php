@extends('layouts.guest')
@section('title', __('messages.forgot_password'))

@section('content')
    <x-card :title="__('messages.reset_your_password')">
        <p class="text-secondary mb-4">{{ __('messages.enter_reset_email') }}</p>
        <form method="POST" action="{{ route('password.email') }}" data-validate-form>
            @csrf
            <x-form.input name="email" :label="__('messages.email')" type="email" data-validate="required|email" />
            <button class="btn btn-primary w-100">{{ __('messages.send_reset_link') }}</button>
        </form>
        <div class="mt-3 text-center small">
            <a href="{{ route('login') }}">{{ __('messages.return_to_sign_in') }}</a>
        </div>
    </x-card>
@endsection