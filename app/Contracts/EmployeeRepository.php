<?php

namespace App\Contracts;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Employee;

interface EmployeeRepository
{
    public function getAllEmployees(): LengthAwarePaginator;  // Should return a LengthAwarePaginator

    public function getFilteredEmployees($filters = []): LengthAwarePaginator;  // Should return a LengthAwarePaginator 
    public function createEmployee(array $data): Employee;  // Should return an Employee model instance
    public function updateEmployee(Employee $employee, array $data): Employee;  // Should return an Employee model instance

    public function getEmployeeById($id): ?Employee;  // Should return an Employee model instance or null
    public function deleteEmployee(Employee $employee): bool;  // Should return a boolean indicating success or failure

    public function restoreEmployee(Employee $employee): bool;  // Should return a boolean indicating success or failure
    public function forceDeleteEmployee(Employee $employee): bool;  // Should return a boolean indicating
}
