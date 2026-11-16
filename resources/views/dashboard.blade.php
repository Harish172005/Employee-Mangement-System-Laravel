@extends('layouts.app')

@section('title', __('messages.dashboard'))
@section('heading', __('messages.dashboard'))

@section('content')

    {{-- Statistics --}}
    <div class="row g-3">
        @foreach ($stats as $label => $value)
            <div class="col-sm-6 col-xl-3">
                <x-card class="stat-card">
                    <div class="stat-label">{{ __('messages.' . strtolower(str_replace(' ', '_', $label))) }}</div>
                    <div class="stat-value">{{ $value }}</div>
                </x-card>
            </div>
        @endforeach
    </div>
    {{-- Total Salary --}}
    <div class="row">
        <div class="col-12">
            <x-card :title="__('messages.total_salary')">
                <div class="fs-3 fw-bold text-primary">
                    {{ number_format((float) $totalSalary, 2) }}
                </div>
                <div class="small text-muted mt-1">{{ __('messages.across_all_departments') }}</div>
            </x-card>
        </div>
    </div>


    {{-- Department Report --}}
    <x-card :title="__('messages.employees_salary_by_department')">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>{{ __('messages.department') }}</th>
                        <th>{{ __('messages.employees') }}</th>
                        <th>{{ __('messages.average_salary') }}</th>
                        <th>{{ __('messages.total_salary_label') }}</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($report as $row)
                        <tr>
                            <td>{{ $row['department'] }}</td>
                            <td>{{ $row['employees'] }}</td>
                            <td>
                                {{ number_format((float) $row['avg_salary'], 2) }}
                            </td>
                            <td>
                                {{ number_format((float) $row['total_salary'], 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                {{ __('messages.no_department_data') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

@endsection