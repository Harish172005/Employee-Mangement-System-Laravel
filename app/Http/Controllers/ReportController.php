<?php

namespace App\Http\Controllers;

class ReportController extends Controller
{
    public function __invoke()
    {
        // Dummy data. In Module 5 this comes from a Query Builder join/group by query.
        $report = [
            ['department' => 'IT',         'employees' => 45, 'avg_salary' => 52000, 'total_salary' => 2340000],
            ['department' => 'HR',         'employees' => 15, 'avg_salary' => 38000, 'total_salary' => 570000],
            ['department' => 'Finance',    'employees' => 20, 'avg_salary' => 45000, 'total_salary' => 900000],
            ['department' => 'Sales',      'employees' => 30, 'avg_salary' => 40000, 'total_salary' => 1200000],
            ['department' => 'Operations', 'employees' => 15, 'avg_salary' => 36000, 'total_salary' => 540000],
        ];

        return view('reports', compact('report'));
    }
}
