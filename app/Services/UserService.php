<?php

namespace App\Services;

use App\Contracts\UserRepository;

class UserService
{
    public function __construct(protected UserRepository $userRepository) {}

    public function deleteUser($user)
    {
        return $this->userRepository->deleteUser($user);

    }

    public function restoreUser($user)
    {
        return $this->userRepository->restoreUser($user);
    }

    public function forceDeleteUser($user)
    {
        return $this->userRepository->forceDeleteUser($user);
    }
}
