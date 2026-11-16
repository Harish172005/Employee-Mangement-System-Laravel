@extends('layouts.app')
@section('title', __('messages.employee_details'))
@section('heading', __('messages.employee_details'))

@section('content')
    <section class="profile-hero mb-4">
        <div class="profile-identity">
            <img src="{{ $employee->photo_url }}" alt="{{ $employee->full_name }}"
                 class="profile-avatar">
            <div class="profile-identity-copy">
                <span class="profile-eyebrow">{{ __('messages.employee_profile') }}</span>
                <h2>{{ $employee->full_name }}</h2>
                <p>{{ $employee->designation }} <span aria-hidden="true">·</span> {{ $employee->department?->name ?? __('messages.unassigned') }}</p>
                <div class="profile-tags">
                    <span class="profile-tag">{{ $employee->employee_code }}</span>
                    <x-status-badge :status="$employee->status" />
                </div>
            </div>
        </div>
        <div class="profile-hero-actions">
            <a href="{{ route('admin.employees.edit', $employee) }}" class="btn btn-light">{{ __('messages.edit_employee') }}</a>
            <a href="{{ route('admin.employees.index') }}" class="btn btn-outline-light">{{ __('messages.all_employees') }}</a>
        </div>
    </section>

    <div class="row g-3">
        <div class="col-lg-8">
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
                        <span>{{ __('messages.gender') }}</span>
                        <strong>{{ __('messages.' . strtolower($employee->gender->value)) }}</strong>
                    </div>
                    <div class="profile-data-item">
                        <span>{{ __('messages.employee_code') }}</span>
                        <strong>{{ $employee->employee_code }}</strong>
                    </div>
                    <div class="profile-data-item">
                        <span>{{ __('messages.department') }}</span>
                        <strong>{{ $employee->department?->name ?? __('messages.unassigned') }}</strong>
                    </div>
                    <div class="profile-data-item">
                        <span>{{ __('messages.designation') }}</span>
                        <strong>{{ $employee->designation }}</strong>
                    </div>
                    <div class="profile-data-item">
                        <span>{{ __('messages.date_joined') }}</span>
                        <strong>{{ $employee->date_of_joining?->format('M d, Y') ?? __('messages.not_provided') }}</strong>
                    </div>
                    <div class="profile-data-item">
                        <span>{{ __('messages.annual_salary') }}</span>
                        <strong>{{ number_format((float) $employee->salary, 2) }}</strong>
                    </div>
                    <div class="profile-data-item profile-data-item-wide">
                        <span>{{ __('messages.skills') }}</span>
                        <strong>{{ $employee->skills->pluck('name')->join(', ') ?: __('messages.no_skills_listed') }}</strong>
                    </div>
                </div>
            </x-card>
        </div>

        <div class="col-lg-4">
            <x-card :title="__('messages.activity')">
                @forelse ($employee->activityLogs->groupBy('action') as $action => $logs)
                    <div class="profile-activity-group">
                        <h3>{{ __('messages.' . strtolower($action)) }} <span>{{ $logs->count() }}</span></h3>
                        <ul class="profile-timeline">
                            @foreach ($logs as $log)
                                <li>
                                    <strong>{{ $log->description }}</strong>
                                    <time>{{ $log->created_at->format('M d, Y · H:i') }}</time>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @empty
                    <div class="profile-empty">{{ __('messages.no_activity_recorded') }}</div>
                @endforelse
            </x-card>
        </div>
    </div>
@endsection
