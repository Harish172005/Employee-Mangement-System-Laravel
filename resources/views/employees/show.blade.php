@extends('layouts.app')
@section('title', 'Employee Details')
@section('heading', 'Employee Details')

@section('content')
    <section class="profile-hero mb-4">
        <div class="profile-identity">
            <img src="{{ $employee->photo_url }}" alt="{{ $employee->full_name }}"
                 class="profile-avatar">
            <div class="profile-identity-copy">
                <span class="profile-eyebrow">Employee profile</span>
                <h2>{{ $employee->full_name }}</h2>
                <p>{{ $employee->designation }} <span aria-hidden="true">·</span> {{ $employee->department?->name ?? 'Unassigned' }}</p>
                <div class="profile-tags">
                    <span class="profile-tag">{{ $employee->employee_code }}</span>
                    <x-status-badge :status="$employee->status" />
                </div>
            </div>
        </div>
        <div class="profile-hero-actions">
            <a href="{{ route('admin.employees.edit', $employee) }}" class="btn btn-light">Edit employee</a>
            <a href="{{ route('admin.employees.index') }}" class="btn btn-outline-light">All employees</a>
        </div>
    </section>

    <div class="row g-3">
        <div class="col-lg-8">
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
                        <span>Gender</span>
                        <strong>{{ ucfirst($employee->gender->value) }}</strong>
                    </div>
                    <div class="profile-data-item">
                        <span>Employee code</span>
                        <strong>{{ $employee->employee_code }}</strong>
                    </div>
                    <div class="profile-data-item">
                        <span>Department</span>
                        <strong>{{ $employee->department?->name ?? 'Unassigned' }}</strong>
                    </div>
                    <div class="profile-data-item">
                        <span>Designation</span>
                        <strong>{{ $employee->designation }}</strong>
                    </div>
                    <div class="profile-data-item">
                        <span>Date joined</span>
                        <strong>{{ $employee->date_of_joining?->format('M d, Y') ?? 'Not provided' }}</strong>
                    </div>
                    <div class="profile-data-item">
                        <span>Annual salary</span>
                        <strong>{{ number_format((float) $employee->salary, 2) }}</strong>
                    </div>
                    <div class="profile-data-item profile-data-item-wide">
                        <span>Skills</span>
                        <strong>{{ $employee->skills->pluck('name')->join(', ') ?: 'No skills listed' }}</strong>
                    </div>
                </div>
            </x-card>
        </div>

        <div class="col-lg-4">
            <x-card title="Activity">
                @forelse ($employee->activityLogs->groupBy('action') as $action => $logs)
                    <div class="profile-activity-group">
                        <h3>{{ ucfirst($action) }} <span>{{ $logs->count() }}</span></h3>
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
                    <div class="profile-empty">No activity recorded yet.</div>
                @endforelse
            </x-card>
        </div>
    </div>
@endsection
