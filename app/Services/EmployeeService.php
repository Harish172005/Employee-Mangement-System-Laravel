<?php

namespace App\Services;

use App\Contracts\EmployeeRepository;
use App\Models\Employee;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Enums\Role;

class EmployeeService
{

const PHOTO_DIR = 'profile_photos';
const PHOTO_DISK = 'public';
    private EmployeeRepository $employeeRepository;

    public function __construct(EmployeeRepository $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }
    function getAllEmployees()
    {
        return $this->employeeRepository->getAllEmployees();
    }

    public function getFilteredEmployees($filters = [])
    {
        return $this->employeeRepository->getFilteredEmployees($filters ?? []);
        
    }
    public function getEmployeeById($id)
    {
        return $this->employeeRepository->getEmployeeById($id);
    }

    public function createEmployee($data, $profilePhoto = null)
    {
           try {
            $data['employee_code'] = 'EMP' . str_pad((string) (Employee::withTrashed()->max('id') + 1), 4, '0', STR_PAD_LEFT);

            if ($profilePhoto) {
                $path = $profilePhoto->store(self::PHOTO_DIR, self::PHOTO_DISK);
                $data['profile_photo'] = $path;
            }

            return $this->employeeRepository->createEmployee($data);
        } catch (\Throwable $e) {
            if ($path) {
                Storage::disk(self::PHOTO_DISK)->delete($path);
            }

            Log::error('Employee create failed: ' . $e->getMessage());

            return back()->withInput()->with('error', 'Failed to create employee.');
        }
    }

    public function updateEmployee($id, $data, $profilePhoto = null)
    {
        $employee = $this->employeeRepository->getEmployeeById($id);
        if (!$employee) {
            throw new \Exception("Employee not found");
        }

        $oldPhoto = $employee->profile_photo;
        $newPhoto = null;

        try {
            if ($profilePhoto) {
                $newPhoto = $profilePhoto->store(self::PHOTO_DIR, self::PHOTO_DISK);
                $data['profile_photo'] = $newPhoto;
            }

            return $this->employeeRepository->updateEmployee($employee, $data);
        } catch (\Throwable $e) {
            if ($newPhoto) {
                Storage::disk(self::PHOTO_DISK)->delete($newPhoto);
            }

        }
        if ($newPhoto && $oldPhoto) {
          Storage::disk(self::PHOTO_DISK)->delete($oldPhoto);
        }

        Log::error('Employee update failed: ' . $e->getMessage(), ['employee_id' => $employee->id]);

        return back()->withInput()->with('error', 'Could not update the employee. Please try again.');  
    }

    public function deleteEmployee($id)
    {
        return $this->employeeRepository->deleteEmployee($id);
    }   

    public function restoreEmployee($id)
    {
        $employee = $this->getEmployeeById($id);
        abort_unless($employee?->trashed(), 404);
        return $this->employeeRepository->restoreEmployee($employee);
    }   

    public function forceDeleteEmployee($id)
    {
        $employee = $this->employeeRepository->getEmployeeById($id);
        abort_unless($employee?->trashed(), 404);
        $photo = $employee?->profile_photo;
        $deleted =  $this->employeeRepository->forceDeleteEmployee($employee);

        if ($photo) {
            Storage::disk(self::PHOTO_DISK)->delete($photo);
        }

        return $deleted;
    }   
}