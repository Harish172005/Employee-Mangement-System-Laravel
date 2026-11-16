@extends('layouts.app')
@section('title', __('messages.change_password_title'))
@section('heading', __('messages.change_password_title'))

@section('content')
    <x-card :title="__('messages.update_your_password')">
        <form method="POST" data-validate-form action="{{ route('profile.password.update') }}">
            @csrf
            @method('PUT')
            <x-form.input name="current_password" :label="__('messages.current_password')" type="password" data-validate="required" />
            <x-form.input name="password" :label="__('messages.new_password')" type="password" data-validate="required" />
            <x-form.input name="password_confirmation" :label="__('messages.confirm_new_password')" type="password" data-validate="required" />
            <button class="btn btn-primary">{{ __('messages.update_password') }}</button>
        </form>
    </x-card>
@endsection
