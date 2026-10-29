<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Models\Department;
use App\Models\Employee;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $employees = Employee::all();
       $stats = [
            'Total Employees'    => Employee::withTrashed()->count(),
            'Active Employees'   => Employee::active()->withTrashed()->count(),
            'Inactive Employees' => Employee::inactive()->withTrashed()->count(),
            'Departments'        => Department::count(),
        ];

        $departments = Department::withCount('employees')                 // adds employees_count
            ->withSum('employees as salary_sum', 'salary')                // adds salary_sum
            ->get();                                                      // already ordered by name (AlphabeticalScope)

        $byDepartment = $departments->pluck('employees_count', 'name');   // [name => count]
        $totalSalary  = $departments->sum('salary_sum');

        return view('dashboard', compact('stats', 'byDepartment', 'totalSalary'));
    }
}
