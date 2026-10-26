<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function __invoke()
    {
        // Dummy data. Real counts come from Eloquent in Module 6.
        $stats = [
            'Total Employees'    => 125,
            'Active Employees'   => 118,
            'Inactive Employees' => 7,
            'Departments'        => 8,
        ];

        $byDepartment = [
            'IT' => 45, 'HR' => 15, 'Finance' => 20, 'Sales' => 30, 'Operations' => 15,
        ];

        return view('dashboard', compact('stats', 'byDepartment'));
    }
}
