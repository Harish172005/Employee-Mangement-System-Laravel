<?php

namespace App\Repositories;

use App\Contracts\ProfileRepository;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;

class EloquentProfileRepository implements ProfileRepository
{
    public function updateProfile($profile, array $data)
    {

        if ($profile) {
            return $profile->update($data);
        }

        return false;
    }

    public function getOwnDepartment($id)
    {
        $employee = Employee::findOrFail($id);

        if (! $employee) {
            return null;
        }

        $department = Department::withCount('employees')
            ->findOrFail($employee->department_id);

        return $department;
    }

    public function getDepartmentEmployees($employee)
    {
        $department = Department::query()
            ->withCount('employees')
            ->with('employees')
            ->findOrFail($employee->department_id);

        return $department;
    }

    public function updatePassword(
        User $user,
        string $password
    ): bool {
        return $user->update([
            'password' => $password,
        ]);
    }
}
