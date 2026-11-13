<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;

class DashboardController extends Controller
{
    public function __construct(protected DashboardService $dashboardService)
    {
    }
public function __invoke()
{

    $report = $this->dashboardService->getDashboardData();

    return view('dashboard', $report );
}
}
