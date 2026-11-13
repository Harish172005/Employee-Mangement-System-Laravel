@extends('layouts.app')
@section('title', 'My Profile')
@section('heading', 'My Profile')

@section('content')
    <section class="profile-hero mb-4">
        <div class="profile-identity">
            <img src="{{ $employee->photo_url }}" alt="{{ $employee->full_name }}" class="profile-avatar">
            <div class="profile-identity-copy">
                <span class="profile-eyebrow">Your employee profile</span>
                <h2>{{ $employee->full_name }}</h2>
                <p>{{ $employee->designation }} <span aria-hidden="true">·</span> {{ $employee->department?->name ?? 'Department not assigned' }}</p>
                <div class="profile-tags">
                    <span class="profile-tag">ID {{ $employee->employee_code }}</span>
                    <x-status-badge :status="$employee->status" />
                </div>
            </div>
        </div>
        <div class="profile-hero-actions">
            <a href="{{ route('profile.edit') }}" class="btn btn-light">Edit contact details</a>
        </div>
    </section>

    <div class="profile-overview-grid mb-3">
        <article class="profile-overview-card">
            <span class="profile-overview-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M9 20V8h6v12"/></svg>
            </span>
            <span class="profile-overview-label">Department</span>
            <strong>{{ $employee->department?->name ?? 'Not assigned' }}</strong>
        </article>
        <article class="profile-overview-card">
            <span class="profile-overview-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 11h18"/></svg>
            </span>
            <span class="profile-overview-label">Date joined</span>
            <strong>{{ $employee->date_of_joining?->format('M d, Y') ?? 'Not provided' }}</strong>
        </article>
        <article class="profile-overview-card">
            <span class="profile-overview-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </span>
            <span class="profile-overview-label">Salary</span>
            <strong>{{ number_format((float) $employee->salary, 2) }}</strong>
        </article>
    </div>

    <div class="row g-3">
        <div class="col-lg-7">
            <x-card title="Personal information">
                <div class="profile-data-grid">
                    <div class="profile-data-item">
                        <span>Email address</span>
                        <strong>{{ $employee->email }}</strong>
                    </div>
                    <div class="profile-data-item">
                        <span>Phone number</span>
                        <strong>{{ $employee->phone ?: 'Not provided' }}</strong>
                    </div>
                    <div class="profile-data-item">
                        <span>Date of birth</span>
                        <strong>{{ $employee->date_of_birth?->format('M d, Y') ?? 'Not provided' }}</strong>
                    </div>
                    <div class="profile-data-item">
                        <span>Gender</span>
                        <strong>{{ $employee->gender ? ucfirst($employee->gender->value) : 'Not provided' }}</strong>
                    </div>
                    <div class="profile-data-item profile-data-item-wide">
                        <span>Home address</span>
                        <strong>{{ $employee->address ?: 'Not provided' }}</strong>
                    </div>
                </div>
            </x-card>

            <x-card title="Skills &amp; expertise">
                @forelse ($employee->skills as $skill)
                    <span class="profile-skill-chip">{{ $skill->name }}</span>
                @empty
                    <p class="profile-empty mb-0">No skills have been added to your profile yet.</p>
                @endforelse
            </x-card>
        </div>

        <div class="col-lg-5">
            <x-card title="Employment details">
                <div class="profile-data-grid profile-data-grid-single">
                    <div class="profile-data-item">
                        <span>Employee ID</span>
                        <strong>{{ $employee->employee_code }}</strong>
                    </div>
                    <div class="profile-data-item">
                        <span>Department</span>
                        <strong>{{ $employee->department?->name ?? 'Not assigned' }}</strong>
                    </div>
                    <div class="profile-data-item">
                        <span>Designation</span>
                        <strong>{{ $employee->designation ?: 'Not provided' }}</strong>
                    </div>
                    <div class="profile-data-item">
                        <span>Employment status</span>
                        <strong><x-status-badge :status="$employee->status" /></strong>
                    </div>
                </div>
            </x-card>

            <x-card title="Change password">
                <p class="text-secondary small mb-4">Verify your current password before setting a new one.</p>
                <form method="POST" data-validate-form action="{{ route('profile.password.update') }}">
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
