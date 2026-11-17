<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Department;
use App\Models\User;

class DepartmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === Role::Admin;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Department $department): bool
    {
        if ($user->role->value === Role::Admin->value) {
            return true;
        }

        return $user->employee?->department_id === $department->id;
    }

    public function create(User $user): bool
    {
        return $user->role === Role::Admin;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function edit(User $user): bool
    {
        return $user->role === Role::Admin;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->role === Role::Admin;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user): bool
    {
        return $user->role === Role::Admin;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user): bool
    {
        return $user->role === Role::Admin;
    }
}
