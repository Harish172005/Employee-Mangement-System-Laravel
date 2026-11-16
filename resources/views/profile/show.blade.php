@extends('layouts.app')
@section('title', __('messages.my_profile'))
@section('heading', __('messages.my_profile'))

@section('content')
    <section class="profile-hero mb-4">
        <div class="profile-identity">
            <img src="{{ $employee->photo_url }}" alt="{{ $employee->full_name }}" class="profile-avatar">
            <div class="profile-identity-copy">
                <span class="profile-eyebrow">{{ __('messages.your_employee_profile') }}</span>
                <h2>{{ $employee->full_name }}</h2>
                <p>{{ $employee->designation }} <span aria-hidden="true">·</span> {{ $employee->department?->name ?? __('messages.department_not_assigned') }}</p>
                <div class="profile-tags">
                    <span class="profile-tag">{{ __('messages.id') }} {{ $employee->employee_code }}</span>
                    <x-status-badge :status="$employee->status" />
                </div>
            </div>
        </div>
        <div class="profile-hero-actions">
            <a href="{{ route('profile.edit') }}" class="btn btn-light">{{ __('messages.edit_contact_details') }}</a>
        </div>
    </section>

    <div class="profile-overview-grid mb-3">
        <article class="profile-overview-card">
            <span class="profile-overview-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M9 20V8h6v12"/></svg>
            </span>
            <span class="profile-overview-label">{{ __('messages.department') }}</span>
            <strong>{{ $employee->department?->name ?? __('messages.not_assigned') }}</strong>
        </article>
        <article class="profile-overview-card">
            <span class="profile-overview-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 11h18"/></svg>
            </span>
            <span class="profile-overview-label">{{ __('messages.date_joined') }}</span>
            <strong>{{ $employee->date_of_joining?->format('M d, Y') ?? __('messages.not_provided') }}</strong>
        </article>
        <article class="profile-overview-card">
            <span class="profile-overview-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </span>
            <span class="profile-overview-label">{{ __('messages.salary') }}</span>
            <strong>{{ number_format((float) $employee->salary, 2) }}</strong>
        </article>
    </div>

    <div class="row g-3">
        <div class="col-lg-7">
            <x-card :title="__('messages.personal_information')">
                <div class="profile-data-grid">
                    <div class="profile-data-item">
                        <span>{{ __('messages.email_address') }}</span>
                        <strong>{{ $employee->email }}</strong>
                    </div>
                    <div class="profile-data-item">
                        <span>{{ __('messages.phone_number') }}</span>
                        <strong>{{ $employee->phone ?: __('messages.not_provided') }}</strong>
                    </div>
                    <div class="profile-data-item">
                        <span>{{ __('messages.date_of_birth_label') }}</span>
                        <strong>{{ $employee->date_of_birth?->format('M d, Y') ?? __('messages.not_provided') }}</strong>
                    </div>
                    <div class="profile-data-item">
                        <span>{{ __('messages.gender') }}</span>
                        <strong>{{ $employee->gender ? __('messages.' . strtolower($employee->gender->value)) : __('messages.not_provided') }}</strong>
                    </div>
                    <div class="profile-data-item profile-data-item-wide">
                        <span>{{ __('messages.home_address') }}</span>
                        <strong>{{ $employee->address ?: __('messages.not_provided') }}</strong>
                    </div>
                </div>
            </x-card>

            <x-card :title="__('messages.skills_expertise')">
                @forelse ($employee->skills as $skill)
                    <span class="profile-skill-chip">{{ $skill->name }}</span>
                @empty
                    <p class="profile-empty mb-0">{{ __('messages.no_profile_skills') }}</p>
                @endforelse
            </x-card>
        </div>

        <div class="col-lg-5">
            <x-card :title="__('messages.employment_details')">
                <div class="profile-data-grid profile-data-grid-single">
                    <div class="profile-data-item">
                        <span>{{ __('messages.employee_id') }}</span>
                        <strong>{{ $employee->employee_code }}</strong>
                    </div>
                    <div class="profile-data-item">
                        <span>{{ __('messages.department') }}</span>
                        <strong>{{ $employee->department?->name ?? __('messages.not_assigned') }}</strong>
                    </div>
                    <div class="profile-data-item">
                        <span>{{ __('messages.designation') }}</span>
                        <strong>{{ $employee->designation ?: __('messages.not_provided') }}</strong>
                    </div>
                    <div class="profile-data-item">
                        <span>{{ __('messages.employment_status') }}</span>
                        <strong><x-status-badge :status="$employee->status" /></strong>
                    </div>
                </div>
            </x-card>

            <x-card :title="__('messages.change_password')">
                <p class="text-secondary small mb-4">{{ __('messages.profile_password_tip') }}</p>
                <form method="POST" data-validate-form action="{{ route('profile.password.update') }}">
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
