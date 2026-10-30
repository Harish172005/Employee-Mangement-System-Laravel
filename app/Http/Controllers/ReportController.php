<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function __invoke()
    {
        $connection = DB::connection();
        $connection->enableQueryLog();

        $employeesWithoutEagerLoading = Employee::query()
            ->limit(10)
            ->get();
        foreach ($employeesWithoutEagerLoading as $employee) {
            $employee->department?->name;
        }
        $nPlusOneQueryCount = count($connection->getQueryLog());

        $connection->flushQueryLog();
        $employeesWithEagerLoading = Employee::query()
            ->with('department')
            ->limit(10)
            ->get();
        foreach ($employeesWithEagerLoading as $employee) {
            $employee->department?->name;
        }
        $eagerLoadedQueryCount = count($connection->getQueryLog());
        $connection->disableQueryLog();

        $report = Department::query()
            ->withCount('employees')
            ->withAvg('employees as avg_salary')
            ->withSum('employees as total_salary', 'salary')
            ->orderBy('name')
            ->get()
            ->map(fn (Department $department) => [
                'department' => $department->name,
                'employees' => $department->employees_count,
                'avg_salary' => $department->avg_salary ?? 0,
                'total_salary' => $department->total_salary ?? 0,
            ]);

        return view('reports', compact('report', 'nPlusOneQueryCount', 'eagerLoadedQueryCount'));
    }
}
