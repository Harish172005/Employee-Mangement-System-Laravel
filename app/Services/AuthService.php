<?php

namespace App\Services;

use App\Contracts\AuthRepository;
use App\Enums\Status;
use Illuminate\Support\Facades\Password as PasswordBroker;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthService
{
    public function __construct(protected AuthRepository $authRepository) {}

    public function login(array $credentials, bool $remember, string $ip): array
    {
        $key = Str::lower($credentials['username']).'|'.$ip;

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            return [
                'success' => false,
                'error' => "Too many login attempts. Please try again in $seconds seconds.",
            ];
        }

        if (! $this->authRepository->attempt($credentials, $remember)) {
            RateLimiter::hit($key, 60);

            return [
                'success' => false,
                'error' => 'These credentials do not match our records.',
            ];
        }

        $user = $this->authRepository->authenticatedUser();

        if ($user->status !== Status::Active) {
            $this->authRepository->logout();

            return [
                'success' => false,
                'error' => 'This account is inactive.',
            ];
        }

        RateLimiter::clear($key);

        return [
            'success' => true,
            'user' => $user,
        ];
    }

    public function sendResetLink(string $email): array
    {
        $user = $this->authRepository->findByEmail($email);

        if (! $user) {
            return [
                'success' => false,
                'status' => null,
            ];
        }

        $status = $this->authRepository->sendResetLink(['email' => $email]);

        return [
            'success' => $status === PasswordBroker::RESET_LINK_SENT,
            'status' => $status,
        ];
    }

    public function resetPassword(array $credentials): array
    {
        $status = $this->authRepository->resetPassword($credentials);

        return [
            'success' => $status === PasswordBroker::PASSWORD_RESET,
            'status' => $status,
        ];
    }

    public function logout(): void
    {
        $this->authRepository->logout();
    }
}
