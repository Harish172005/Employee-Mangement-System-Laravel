@extends('layouts.app')
@section('title', 'Reports')
@section('heading', 'Reports')

@section('content')
    <x-card title="Employees and Salary by Department (placeholder data)">
        <table class="table table-striped mb-0">
            <thead>
                <tr><th>Department</th><th>Employees</th><th>Average Salary</th><th>Total Salary</th></tr>
            </thead>
            <tbody>
                @foreach ($report as $row)
                    <tr>
                        <td>{{ $row['department'] }}</td>
                        <td>{{ $row['employees'] }}</td>
                        <td>{{ number_format($row['avg_salary']) }}</td>
                        <td>{{ number_format($row['total_salary']) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </x-card>
    <p class="text-muted small">In Module 5 this table is produced with the Query Builder (join, group by, having, aggregates).</p>
@endsection
