<?php

namespace App\Repositories;

use App\Contracts\DepartmentRepository;
use App\Enums\Status;
use App\Models\Department;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class EloquentDepartmentRepository implements DepartmentRepository
{
    public function getAllDepartments($filters = []): LengthAwarePaginator
    {
        $query = Department::query()
            ->withCount('employees')
            ->when($filters['q'] ?? null, fn (Builder $query, $term) => $query->where(function (Builder $query) use ($term) {
                $query->where('name', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%");
            }))
            ->when($filters['status'] ?? null, fn (Builder $query, $status) => match ($status) {
                Status::Active->value => $query->where('status', Status::Active),
                Status::Inactive->value => $query->where('status', Status::Inactive),
                default => $query,
            })
            ->when(($filters['employees'] ?? null) === 'with', fn (Builder $query) => $query->has('employees'))
            ->when(($filters['employees'] ?? null) === 'without', fn (Builder $query) => $query->doesntHave('employees'))
            ->when($filters['employee_status'] ?? null, fn (Builder $query, $status) => $query->whereHas(
                'employees',
                fn (Builder $employees) => $employees->where('status', $status)
            ))
            ->when($filters['with_trashed'] ?? false, fn (Builder $query) => $query->withTrashed())
            ->orderBy($filters['sort'] ?? 'name', $filters['direction'] ?? 'asc');

        return $query
            ->paginate(10)
            ->withQueryString();
    }

    public function createDepartment(array $data): Department
    {
        return Department::create($data);
    }

    public function updateDepartment(Department $department, array $data): Department
    {
        $department->update($data);

        return $department;
    }

    public function deleteDepartment(Department $department): bool
    {
        return DB::transaction(function () use ($department) {
            $department->status = Status::Inactive;
            $department->saveQuietly();
            $department->delete();
        });
    }

    public function restoreDepartment(Department $department): bool
    {
        return DB::transaction(function () use ($department) {
            $department->status = Status::Active;
            $department->saveQuietly();

            return $department->restore();
        });
    }

    public function getDepartmentById($id): ?Department
    {
        return Department::query()
            ->with('employees')
            ->withCount('employees')
            ->findOrFail($id);
    }
}
