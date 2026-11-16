<?php

namespace App\Contracts;

interface DashboardRepository
{
    public function getStats(): array;

    public function getDepartmentsWithSalary();

    public function getDepartmentReport();
}