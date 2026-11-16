<?php

namespace App\Contracts;

interface UserRepository
{
    public function createUser(array $data);

    public function deleteUser($user);

    public function restoreUser($user);

    public function forceDeleteUser($user);
}