<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Models\Department;
use App\Models\Employee;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $employees = Employee::withoutGlobalScope(\App\Scopes\ActiveEmployeeScope::class);
        $stats = [
            'Total Employees' => (clone $employees)->count(),
            'Active Employees' => (clone $employees)->active()->count(),
            'Inactive Employees' => (clone $employees)->inactive()->count(),
            'Departments' => Department::count(),
        ];

        $departments = Department::withCount([
            'employees as employees_count' => fn ($query) => $query->withoutGlobalScope(\App\Scopes\ActiveEmployeeScope::class),
        ])->withSum([
            'employees as salary_sum' => fn ($query) => $query->withoutGlobalScope(\App\Scopes\ActiveEmployeeScope::class),
        ], 'salary')->orderBy('name')->get();
        $byDepartment = $departments->mapWithKeys(fn (Department $department) => [
            $department->name => $department->employees_count,
        ]);
        $totalSalary = $departments->sum('salary_sum');

        return view('dashboard', compact('stats', 'byDepartment', 'totalSalary'));
    }
}
