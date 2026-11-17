<?php

namespace App\Contracts;

use App\Models\Department;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface DepartmentRepository
{
    public function getAllDepartments($filters = []): LengthAwarePaginator;  // Should return a LengthAwarePaginator

    public function createDepartment(array $data): Department;  // Should return a Department model instance

    public function updateDepartment(Department $department, array $data): Department;  // Should return a Department model instance

    public function deleteDepartment(Department $department): bool;  // Should return a boolean indicating success or failure

    public function restoreDepartment(Department $department): bool;  // Should return a boolean indicating success or failure

    public function getDepartmentById($id): ?Department;  // Should return a Department model instance or null
}
