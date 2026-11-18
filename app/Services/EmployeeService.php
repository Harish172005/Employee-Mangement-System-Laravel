<?php

namespace App\Services;

use App\Contracts\EmployeeRepository;
use App\Events\EmployeeStatusChanged;
use App\Exceptions\ServiceException;
use App\Jobs\SendEmployeeWelcomeEmail;
use App\Models\Employee;
use App\Notifications\EmployeeUpdatedNotification;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EmployeeService
{
    private const PHOTO_DIR = 'profile_photos';

    private const PHOTO_DISK = 'public';

    public function __construct(
        private EmployeeRepository $employeeRepository
    ) {}

    public function getAllEmployees(): LengthAwarePaginator
    {
        $page = request()->get('page', 1);

        return Cache::remember(
            "employees.page.{$page}",
            now()->addMinutes(15),
            fn () => $this->employeeRepository->getAllEmployees()
        );
    }

    public function getFilteredEmployees(array $filters = []): LengthAwarePaginator
    {
        return $this->employeeRepository->getFilteredEmployees($filters);
    }

    public function getEmployeeById($id)
    {
        return $this->employeeRepository->getEmployeeById($id);
    }

    public function createEmployee(array $data, $profilePhoto = null): Employee
    {
        $path = null;

        try {
            $employee = DB::transaction(function () use ($data, $profilePhoto, &$path) {

                // Upload profile photo
                if ($profilePhoto) {
                    $path = $profilePhoto->store(
                        self::PHOTO_DIR,
                        self::PHOTO_DISK
                    );

                    $data['profile_photo'] = $path;
                }

                // Generate employee code
                $employeeCode = 'EMP'.str_pad(
                    (string) (Employee::withTrashed()->max('id') + 1),
                    4,
                    '0',
                    STR_PAD_LEFT
                );

                // User data
                $userData = [
                    'name' => trim(
                        $data['first_name'].' '.$data['last_name']
                    ),
                    'email' => $data['email'],
                    'username' => $data['username'],
                    'password' => Hash::make($data['password']),
                    'role' => $data['role'],
                    'status' => $data['status'],
                ];

                // Employee data
                $employeeData = [
                    'employee_code' => $employeeCode,
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'] ?? null,
                    'date_of_birth' => $data['date_of_birth'] ?? null,
                    'date_of_joining' => $data['date_of_joining'],
                    'gender' => $data['gender'] ?? null,
                    'department_id' => $data['department_id'],
                    'designation' => $data['designation'],
                    'salary' => $data['salary'],
                    'address' => $data['address'] ?? null,
                    'profile_photo' => $data['profile_photo'] ?? null,
                    'status' => $data['status'],
                ];

                return $this->employeeRepository->createEmployee(
                    $userData,
                    $employeeData
                );
            });

            // Transaction has successfully committed here
            Cache::forget('employees.all');

            // Add welcome email job to the queue
            SendEmployeeWelcomeEmail::dispatch($employee);

            return $employee;

        } catch (\Throwable $e) {

            if ($path) {
                Storage::disk(self::PHOTO_DISK)->delete($path);
            }

            Log::error('Employee creation failed.', [
                'error' => $e->getMessage(),
            ]);

            throw new ServiceException(
                'Failed to create employee.',
                $e
            );
        }
    }

    public function updateEmployee($id, array $data, $profilePhoto = null)
    {
        $employee = $this->employeeRepository->getEmployeeById($id);

        if (! $employee) {
            throw new ServiceException('Employee not found.');
        }

        // Capture old status before update
        $oldStatus = $employee->status;

        $oldPhoto = $employee->profile_photo;
        $newPhoto = null;

        try {
            if ($profilePhoto) {
                $newPhoto = $profilePhoto->store(
                    self::PHOTO_DIR,
                    self::PHOTO_DISK
                );

                $data['profile_photo'] = $newPhoto;
            }

            $updated = $this->employeeRepository
                ->updateEmployee($employee, $data);

            // Dispatch event only if status was actually changed
            if (
                isset($data['status']) &&
                $oldStatus !== $updated->status
            ) {
                event(new EmployeeStatusChanged(
                    $updated,
                    $oldStatus,
                    $updated->status
                ));
            }

            if ($updated->user) {
                $updated->user->notify(
                    new EmployeeUpdatedNotification(
                        $updated,
                        'Your employee profile has been updated by the administrator.'
                    )
                );
            }

            // Delete the old photo only after successful DB update
            if ($newPhoto && $oldPhoto) {
                Storage::disk(self::PHOTO_DISK)
                    ->delete($oldPhoto);
            }

            Cache::forget('employees.all');

            return $updated;

        } catch (\Throwable $e) {

            // Remove newly uploaded photo if update failed
            if ($newPhoto) {
                Storage::disk(self::PHOTO_DISK)
                    ->delete($newPhoto);
            }

            Log::error('Employee update failed.', [
                'employee_id' => $employee->id,
                'error' => $e->getMessage(),
            ]);

            throw new ServiceException(
                'Could not update the employee.',
                $e
            );
        }
    }

    public function deleteEmployee($id)
    {
        $deleted = $this->employeeRepository->deleteEmployee($id);

        Cache::forget('employees.all');

        return $deleted;
    }

    public function restoreEmployee($id)
    {
        $employee = $this->getEmployeeById($id);

        abort_unless($employee?->trashed(), 404);

        Cache::forget('employees.all');

        return $this->employeeRepository->restoreEmployee($employee);
    }

    public function forceDeleteEmployee($id)
    {
        $employee = $this->getEmployeeById($id);

        abort_unless($employee?->trashed(), 404);

        $photo = $employee->profile_photo;

        try {
            $deleted = $this->employeeRepository
                ->forceDeleteEmployee($employee);

            if ($deleted && $photo) {
                Storage::disk(self::PHOTO_DISK)->delete($photo);
            }

            return $deleted;

        } catch (\Throwable $e) {

            Log::error('Employee permanent deletion failed.', [
                'employee_id' => $employee->id,
                'error' => $e->getMessage(),
            ]);

            throw new ServiceException(
                'Could not permanently delete the employee.',
                $e
            );
        }
    }
}
