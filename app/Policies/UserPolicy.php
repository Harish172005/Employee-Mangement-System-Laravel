<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === Role::Admin;
    }

    public function create(User $user): bool
    {
        return $user->role === Role::Admin;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
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

    public function restore(User $user): bool
    {
        return $user->role === Role::Admin;
    }

    public function forceDelete(User $user): bool
    {
        return $user->role === Role::Admin;
    }
}
