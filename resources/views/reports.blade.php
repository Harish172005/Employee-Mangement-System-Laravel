@extends('layouts.app')
@section('title', __('messages.reports'))
@section('heading', __('messages.reports'))

@section('content')
    <x-card :title="__('messages.eager_loading_comparison')">
        <p class="mb-0 text-secondary">
            {{ __('messages.report_query_comparison', ['lazy' => $nPlusOneQueryCount, 'eager' => $eagerLoadedQueryCount]) }}
        </p>
    </x-card>

    <x-card :title="__('messages.employees_salary_by_department')">
        <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr><th>{{ __('messages.department') }}</th><th>{{ __('messages.employees') }}</th><th>{{ __('messages.average_salary') }}</th><th>{{ __('messages.total_salary_label') }}</th></tr>
            </thead>
            <tbody>
                @foreach ($report as $row)
                    <tr>
                        <td>{{ $row['department'] }}</td>
                        <td>{{ $row['employees'] }}</td>
                        <td>{{ number_format((float) $row['avg_salary'], 2) }}</td>
                        <td>{{ number_format((float) $row['total_salary'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </x-card>
    <p class="text-muted small">{{ __('messages.employee_relations_aggregated') }}</p>
@endsection
