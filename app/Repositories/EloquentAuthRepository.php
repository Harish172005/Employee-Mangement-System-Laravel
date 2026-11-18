<?php

namespace App\Repositories;

use App\Contracts\AuthRepository;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password as PasswordBroker;

class EloquentAuthRepository implements AuthRepository
{
    public function attempt(array $credentials, bool $remember): bool
    {
        return Auth::attempt($credentials, $remember);
    }

    public function authenticatedUser(): ?User
    {
        return Auth::user();
    }

    public function logout(): void
    {
        Auth::logout();
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function sendResetLink(array $credentials): string
    {
        return PasswordBroker::sendResetLink($credentials);
    }

    public function resetPassword(array $credentials): string
    {
        return PasswordBroker::reset(
            $credentials,
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password),
                ])->save();
            }
        );
    }
}
