<?php

namespace App\Contracts;

use App\Models\User;

interface AuthRepository
{
    public function attempt(array $credentials, bool $remember): bool;

    public function authenticatedUser(): ?User;

    public function logout(): void;

    public function findByEmail(string $email): ?User;

    public function sendResetLink(array $credentials): string;

    public function resetPassword(array $credentials): string;
}
