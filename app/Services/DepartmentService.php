<?php

namespace App\Services;

use App\Contracts\DepartmentRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DepartmentService
{
    protected $departmentRepository;

    public function __construct(DepartmentRepository $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
    }

    public function getAllDepartments($filters = []): LengthAwarePaginator
    {
        return $this->departmentRepository->getAllDepartments($filters);
    }

    public function createDepartment(array $data)
    {
        return $this->departmentRepository->createDepartment($data);
    }

    public function updateDepartment($department, array $data = [])
    {
        return $this->departmentRepository->updateDepartment($department, $data);
    }

    public function deleteDepartment($department)
    {
        return $this->departmentRepository->deleteDepartment($department);
    }

    public function restoreDepartment($id)
    {
        $department = $this->getDepartmentById($id);
        abort_unless($department?->trashed(), 404);

        return $this->departmentRepository->restoreDepartment($department);
    }

    public function getDepartmentById($id)
    {
        return $this->departmentRepository->getDepartmentById($id);
    }
}
