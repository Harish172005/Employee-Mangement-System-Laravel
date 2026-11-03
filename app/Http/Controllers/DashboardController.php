<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

public function __invoke()
{
    /*
    |--------------------------------------------------------------------------
    | Dashboard Statistics
    |--------------------------------------------------------------------------
    */

    $stats = [
        'Total Employees' => Employee::withTrashed()->count(),

        'Active Employees' => Employee::withTrashed()
            ->where('status', 'active')
            ->count(),

        'Inactive Employees' => Employee::withTrashed()
            ->where('status', 'inactive')
            ->count(),

        'Departments' => Department::count(),
    ];


    /*
    |--------------------------------------------------------------------------
    | Department Statistics
    |--------------------------------------------------------------------------
    */

    $departments = Department::query()
        ->withCount('employees')
        ->withSum('employees as salary_sum', 'salary')
        ->get();

    $report = Department::query()
        ->withCount('employees')
        ->withAvg('employees as avg_salary', 'salary')
        ->withSum('employees as total_salary', 'salary')
        ->get()
        ->map(fn ($department) => [
            'department' => $department->name,
            'employees' => $department->employees_count,
            'avg_salary' => $department->avg_salary ?? 0,
            'total_salary' => $department->total_salary ?? 0,
        ]);

    $totalSalary = $departments->sum('salary_sum');

    return view('dashboard', compact(
        'stats',
        'totalSalary',
        'report',
    ));
}
}
