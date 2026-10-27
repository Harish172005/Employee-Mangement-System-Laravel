@extends('layouts.app')
@section('title', 'Reports')
@section('heading', 'Reports')

@section('content')
    <x-card title="Eager-loading query comparison">
        <p class="mb-0">
            Loading 10 employees without eager loading ran <strong>{{ $nPlusOneQueryCount }}</strong> queries;
            loading them with <code>with('department')</code> ran <strong>{{ $eagerLoadedQueryCount }}</strong>.
            The eager-loaded result avoids one department lookup per employee.
        </p>
    </x-card>

    <x-card title="Employees and Salary by Department">
        <table class="table table-striped mb-0">
            <thead>
                <tr><th>Department</th><th>Employees</th><th>Average Salary</th><th>Total Salary</th></tr>
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
    </x-card>
    <p class="text-muted small">Employee relations are eager-aggregated with Eloquent to avoid per-department queries.</p>
@endsection
