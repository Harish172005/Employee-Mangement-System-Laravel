<?php

namespace App\Contracts;

interface UserRepository
{
    public function deleteUser($user);

    public function restoreUser($user);

    public function forceDeleteUser($user);
}
