<?php

namespace App\Services;

use App\Contracts\ProfileRepository;
use App\Exceptions\ServiceException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfileService
{
    protected $profileRepository;

    public function __construct(ProfileRepository $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    public function getOwnProfile()
    {
        $employee = auth()->user()->employee;

        if (!$employee) {
            throw new ServiceException('Profile not found.');
        }
        return $employee;
    }
  
    public function updateOwnProfile(array $data)
    {
        $profile = $this->getOwnProfile();

        $updated = $this->profileRepository->updateProfile($profile, $data);

        if (!$updated) {
            throw new ServiceException('Failed to update profile.');
        }

        return $profile;
    }

    public function getOwnDepartment()
    {
        $profile = $this->getOwnProfile();

        $department = $this->profileRepository->getOwnDepartment($profile->id);

        return $department;
    }

    public function changePassword(int $userId, string $currentPassword, string $newPassword)
    {
        $user = auth()->user();

        if (!$user || $user->getKey() !== $userId) {
            throw new \Exception('User not found.');
        }

        if (!Hash::check($currentPassword, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => [
                    'The current password is incorrect.'
                ],
            ]);
        }

        if (Hash::check($newPassword, $user->password)) {
            throw ValidationException::withMessages([
                'password' => [
                    'The new password must be different from the current password.'
                ],
            ]);
        }

        $updated = $this->profileRepository->updateProfile($user, ['password' => Hash::make($newPassword)]);

        if (!$updated) {
            throw new ServiceException('Failed to update password.');
        }

    }
}