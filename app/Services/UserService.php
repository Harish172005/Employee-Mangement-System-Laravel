<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;   
use App\Contracts\UserRepository; 

class UserService
{
    public function __construct(protected UserRepository $userRepository)
    {
    }
    public function createUser(array $data): User
    {
        return $this->userRepository->createUser($data);
    }

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