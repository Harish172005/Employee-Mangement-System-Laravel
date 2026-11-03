@extends('layouts.app')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('content')

    {{-- Statistics --}}
    <div class="row">
        @foreach ($stats as $label => $value)
            <div class="col-md-3 mb-3">
                <x-card>
                    <div class="text-muted">{{ $label }}</div>
                    <div class="fs-3 fw-bold">{{ $value }}</div>
                </x-card>
            </div>
        @endforeach
    </div>
    {{-- Total Salary --}}
    <x-card title="Total Salary">
        <div class="fs-4 fw-bold">
            {{ number_format((float) $totalSalary, 2) }}
        </div>
    </x-card>


    {{-- Department Report --}}
    <x-card title="Employees and Salary by Department">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>Department</th>
                        <th>Employees</th>
                        <th>Average Salary</th>
                        <th>Total Salary</th>
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
                                No department data found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

@endsection