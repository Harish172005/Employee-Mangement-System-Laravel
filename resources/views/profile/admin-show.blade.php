@extends('layouts.app')
@section('title', __('messages.my_profile'))
@section('heading', __('messages.my_profile'))

@section('content')
    <section class="profile-hero mb-4">
        <div class="profile-identity">
            <img src="{{ asset('images/avatar.svg') }}" alt="" class="profile-avatar">
            <div class="profile-identity-copy">
                <span class="profile-eyebrow">{{ __('messages.your_administrator_profile') }}</span>
                <h2>{{ $user->name }}</h2>
                <p>{{ $user->email }}</p>
                <div class="profile-tags">
                    <span class="profile-tag">@ {{ $user->username }}</span>
                    <x-status-badge :status="$user->status" />
                </div>
            </div>
        </div>
        <div class="profile-hero-actions">
            <a href="#admin-change-password" class="btn btn-light">{{ __('messages.security_settings') }}</a>
        </div>
    </section>

    <div class="profile-overview-grid mb-3">
        <article class="profile-overview-card">
            <span class="profile-overview-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/><path d="M20 8v6m-3-3h6"/></svg>
            </span>
            <span class="profile-overview-label">{{ __('messages.account_role') }}</span>
            <strong>{{ __('messages.' . strtolower($user->role->value)) }}</strong>
        </article>
        <article class="profile-overview-card">
            <span class="profile-overview-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 11h18"/></svg>
            </span>
            <span class="profile-overview-label">{{ __('messages.member_since') }}</span>
            <strong>{{ $user->created_at?->format('M d, Y') ?? __('messages.not_available') }}</strong>
        </article>
        <article class="profile-overview-card">
            <span class="profile-overview-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11Z"/><path d="m9 12 2 2 4-4"/></svg>
            </span>
            <span class="profile-overview-label">{{ __('messages.account_status') }}</span>
            <strong><x-status-badge :status="$user->status" /></strong>
        </article>
    </div>

    <div class="row g-3">
        <div class="col-lg-7">
            <x-card :title="__('messages.account_information')">
                <div class="profile-data-grid">
                    <div class="profile-data-item">
                        <span>{{ __('messages.display_name') }}</span>
                        <strong>{{ $user->name }}</strong>
                    </div>
                    <div class="profile-data-item">
                        <span>{{ __('messages.username') }}</span>
                        <strong>@ {{ $user->username }}</strong>
                    </div>
                    <div class="profile-data-item">
                        <span>{{ __('messages.email_address') }}</span>
                        <strong>{{ $user->email }}</strong>
                    </div>
                    <div class="profile-data-item">
                        <span>{{ __('messages.last_updated') }}</span>
                        <strong>{{ $user->updated_at?->format('M d, Y') ?? __('messages.not_available') }}</strong>
                    </div>
                </div>
            </x-card>
        </div>

        <div class="col-lg-5">
            <x-card :title="__('messages.security_settings')" id="admin-change-password">
                <div class="profile-security-note">
                    <span class="profile-security-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </span>
                    <p>{{ __('messages.security_password_tip') }}</p>
                </div>
                <form method="POST" data-validate-form action="{{ route('admin.profile.password.update') }}">
                    @csrf
                    @method('PUT')
                    <x-form.input name="current_password" :label="__('messages.current_password')" type="password" data-validate="required" autocomplete="current-password" />
                    <x-form.input name="password" :label="__('messages.new_password')" type="password" data-validate="required" autocomplete="new-password" />
                    <x-form.input name="password_confirmation" :label="__('messages.confirm_new_password')" type="password" data-validate="required" autocomplete="new-password" />
                    <button class="btn btn-primary w-100">{{ __('messages.update_password') }}</button>
                </form>
            </x-card>
        </div>
    </div>
@endsection
