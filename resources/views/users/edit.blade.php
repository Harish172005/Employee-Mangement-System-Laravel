@extends('layouts.app')
@section('title', __('messages.user_edit_title'))
@section('heading', __('messages.user_edit_title'))

@section('content')
    <div class="user-form-intro">
        <span class="user-form-intro-icon" aria-hidden="true">
            <svg viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L8 18l-4 1 1-4Z"/></svg>
        </span>
        <div>
            <h2>{{ __('messages.update_account_details') }}</h2>
            <p>{{ __('messages.account_details_help') }}</p>
        </div>
    </div>

    <div class="row g-3 align-items-start">
        <div class="col-xl-8">
            <x-card :title="__('messages.account_settings')">
                <form method="POST" data-validate-form action="{{ route('admin.users.update', $user['username']) }}">
                    @csrf
                    @method('PUT')
                    @include('users._form', ['user' => $user])
                    <div class="user-form-actions">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">{{ __('messages.cancel') }}</a>
                        <button class="btn btn-primary">{{ __('messages.save_changes') }}</button>
                    </div>
                </form>
            </x-card>
        </div>
        <div class="col-xl-4">
            <aside class="user-form-aside">
                <span class="user-form-aside-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M5 21a7 7 0 0 1 14 0"/></svg>
                </span>
                <h3>{{ $user['name'] }}</h3>
                <p>{{ $user['email'] }}</p>
                <div class="user-aside-meta">
                    <span>{{ __('messages.current_role') }}</span>
                    <strong>{{ __('messages.' . strtolower($user['role'] instanceof \BackedEnum ? $user['role']->value : $user['role'])) }}</strong>
                </div>
                <div class="user-aside-meta">
                    <span>{{ __('messages.account_status') }}</span>
                    <strong>{{ __('messages.' . strtolower($user['status'] instanceof \BackedEnum ? $user['status']->value : $user['status'])) }}</strong>
                </div>
            </aside>
        </div>
    </div>
@endsection
