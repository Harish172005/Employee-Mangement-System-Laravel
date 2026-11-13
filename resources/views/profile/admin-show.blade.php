@extends('layouts.app')
@section('title', 'My Profile')
@section('heading', 'My Profile')

@section('content')
    <section class="profile-hero mb-4">
        <div class="profile-identity">
            <img src="{{ asset('images/avatar.svg') }}" alt="" class="profile-avatar">
            <div class="profile-identity-copy">
                <span class="profile-eyebrow">Your administrator profile</span>
                <h2>{{ $user->name }}</h2>
                <p>{{ $user->email }}</p>
                <div class="profile-tags">
                    <span class="profile-tag">@ {{ $user->username }}</span>
                    <x-status-badge :status="$user->status" />
                </div>
            </div>
        </div>
        <div class="profile-hero-actions">
            <a href="#admin-change-password" class="btn btn-light">Security settings</a>
        </div>
    </section>

    <div class="profile-overview-grid mb-3">
        <article class="profile-overview-card">
            <span class="profile-overview-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/><path d="M20 8v6m-3-3h6"/></svg>
            </span>
            <span class="profile-overview-label">Account role</span>
            <strong>{{ ucfirst($user->role->value) }}</strong>
        </article>
        <article class="profile-overview-card">
            <span class="profile-overview-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 11h18"/></svg>
            </span>
            <span class="profile-overview-label">Member since</span>
            <strong>{{ $user->created_at?->format('M d, Y') ?? 'Not available' }}</strong>
        </article>
        <article class="profile-overview-card">
            <span class="profile-overview-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11Z"/><path d="m9 12 2 2 4-4"/></svg>
            </span>
            <span class="profile-overview-label">Account status</span>
            <strong><x-status-badge :status="$user->status" /></strong>
        </article>
    </div>

    <div class="row g-3">
        <div class="col-lg-7">
            <x-card title="Account information">
                <div class="profile-data-grid">
                    <div class="profile-data-item">
                        <span>Display name</span>
                        <strong>{{ $user->name }}</strong>
                    </div>
                    <div class="profile-data-item">
                        <span>Username</span>
                        <strong>@ {{ $user->username }}</strong>
                    </div>
                    <div class="profile-data-item">
                        <span>Email address</span>
                        <strong>{{ $user->email }}</strong>
                    </div>
                    <div class="profile-data-item">
                        <span>Last updated</span>
                        <strong>{{ $user->updated_at?->format('M d, Y') ?? 'Not available' }}</strong>
                    </div>
                </div>
            </x-card>
        </div>

        <div class="col-lg-5">
            <x-card title="Security settings" id="admin-change-password">
                <div class="profile-security-note">
                    <span class="profile-security-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </span>
                    <p>Keep your account secure by using a unique password you don't use elsewhere.</p>
                </div>
                <form method="POST" data-validate-form action="{{ route('admin.profile.password.update') }}">
                    @csrf
                    @method('PUT')
                    <x-form.input name="current_password" label="Current password" type="password" data-validate="required" autocomplete="current-password" />
                    <x-form.input name="password" label="New password" type="password" data-validate="required" autocomplete="new-password" />
                    <x-form.input name="password_confirmation" label="Confirm new password" type="password" data-validate="required" autocomplete="new-password" />
                    <button class="btn btn-primary w-100">Update password</button>
                </form>
            </x-card>
        </div>
    </div>
@endsection
