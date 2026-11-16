<?php

namespace App\Repositories;

use App\Models\Employee;
use App\Contracts\EmployeeRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Enums\Status;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Enums\Role;
class EloquentEmployeeRepository implements EmployeeRepository
 {
     public function getAllEmployees(): LengthAwarePaginator
     {
        $query = Employee::with(['department', 'user']);
   
         return $query->paginate(10)->withQueryString(); 

     }

     public function getFilteredEmployees($filters = []): LengthAwarePaginator
     {

         $query = Employee::query()
            ->with(['department', 'user'])
            ->search($filters['q'] ?? null)
            ->when($filters['department'] ?? null, fn (Builder $query, $department) => $query->where('department_id', $department))
            ->when($filters['status'] ?? null, fn (Builder $query, $status) => match ($status) {
                Status::Active->value => $query->active(),
                Status::Inactive->value => $query->inactive(),
                default => $query,
            })
            ->when($filters['gender'] ?? null, fn (Builder $query, $gender) => $query->where('gender', $gender))
             ->orderBy($filters['sort'] ?? 'last_name', $filters['direction'] ?? 'asc')
            ->orderBy('first_name')
            ->when($filters['with_trashed'] ?? false, fn (Builder $query) => $query->withTrashed());
            
            return $query->paginate(10)->withQueryString(); 
     }  



        public function createEmployee(
            array $userData,
            array $employeeData
        ): Employee {
            $user = User::create($userData);

            $employeeData['user_id'] = $user->id;

            return Employee::create($employeeData);
        }


     public function updateEmployee(Employee $employee, array $data): Employee
     {
         $employee->update($data);
         return $employee;
     }

     public function deleteEmployee(Employee $employee): bool
     {
         return DB::transaction(function () use ($employee) {
            $employee->status = Status::Inactive;
            $employee->saveQuietly();
            $employee->delete();
        });
     }

     public function restoreEmployee(Employee $employee): bool
     {

       $restored =   DB::transaction(function () use ($employee) {
            $employee->status = Status::Active;
            // $employee->saveQuietly();
            return $employee->restore();
        });

        return $restored;
     }

     public function forceDeleteEmployee(Employee $employee): bool
     {

         return $employee->forceDelete();
     }

     public function getEmployeeById($id): ?Employee
     {
         return Employee::withTrashed()->find($id);
     }
 }