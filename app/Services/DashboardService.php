<?php

namespace App\Services;

use App\Contracts\DashboardRepository;

class DashboardService
{
    public function __construct(
        private DashboardRepository $dashboardRepository
    ) {}

    public function getDashboardData(): array
    {
        $stats = $this->dashboardRepository->getStats();

        $departments = $this->dashboardRepository->getDepartmentsWithSalary();

        $report = $this->dashboardRepository->getDepartmentReport();

        $totalSalary = $departments->sum('salary_sum');

        return [
            'stats' => $stats,
            'totalSalary' => $totalSalary,
            'report' => $report,
        ];
    }
}
