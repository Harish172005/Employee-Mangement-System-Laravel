<?php

namespace App\Repositories;

use App\Contracts\DashboardRepository;
use App\Models\Department;
use App\Models\Employee;

class EloquentDashboardRepository implements DashboardRepository
{
    public function getStats(): array
    {
        return [
            'Total Employees' => Employee::withTrashed()->count(),

            'Active Employees' => Employee::withTrashed()
                ->where('status', 'active')
                ->count(),

            'Inactive Employees' => Employee::withTrashed()
                ->where('status', 'inactive')
                ->count(),

            'Departments' => Department::count(),
        ];
    }

    public function getDepartmentsWithSalary()
    {
        return Department::query()
            ->withSum('employees as salary_sum', 'salary')
            ->get();
    }

    public function getDepartmentReport()
    {
        return Department::query()
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
    }
}