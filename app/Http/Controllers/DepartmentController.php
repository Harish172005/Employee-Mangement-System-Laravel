<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Enums\Status;
use App\Http\Requests\DepartmentRequest;
use App\Models\Department;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate($this->filterRules() + [
            'sort' => ['nullable', Rule::in(['name', 'status', 'created_at'])],
            'direction' => ['nullable', Rule::in(['asc', 'desc'])],
            'with_trashed' => ['nullable', 'boolean'],
        ]);

        $query = $this->filteredQuery($filters);

        if ($request->boolean('with_trashed')) {
            $query->withTrashed();
        }

        $departments = $query
            ->orderBy($filters['sort'] ?? 'name', $filters['direction'] ?? 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('departments.index', compact('departments', 'filters'));
    }

    public function create()
    {
        return view('departments.create');
    }

    public function store(DepartmentRequest $request)
    {
        Department::create($request->validated());

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department created successfully.');
    }

    public function show(int $id)
    {
        $department = Department::withTrashed()
            ->with('employees')
            ->withCount('employees')
            ->findOrFail($id);

        return view('departments.show', compact('department'));
    }

    public function edit(int $id)
    {
        $department = Department::findOrFail($id);

        return view('departments.edit', compact('department'));
    }

    public function update(DepartmentRequest $request, Department $department)
    {
        $department->update($request->validated());

        return redirect()->route('admin.departments.show', $department)
            ->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        DB::transaction(function () use ($department) {
            $department->status = Status::Inactive;
            $department->saveQuietly();
            $department->delete();
        });

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department deactivated successfully.');
    }

    public function restore(int $id)
    {
        $department = Department::withTrashed()->findOrFail($id);

        abort_unless($department->trashed(), 404);

        DB::transaction(function () use ($department) {
            $department->restore();
            $department->status = Status::Active;
            $department->saveQuietly();
        });

        return redirect()->route('admin.departments.index', ['with_trashed' => 1])
            ->with('success', 'Department restored successfully.');
    }

    public function forceDelete(int $id)
    {
        abort_unless(
            auth()->check() && auth()->user()->role === Role::Admin,
            403
        );

        $department = Department::withTrashed()->findOrFail($id);

        abort_unless($department->trashed(), 404);

        $department->forceDelete();

        return redirect()->route('admin.departments.index', ['with_trashed' => 1])
            ->with('success', 'Department permanently deleted.');
    }

    private function filterRules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::enum(Status::class)],
            'employees' => ['nullable', Rule::in(['with', 'without'])],
            'employee_status' => ['nullable', Rule::enum(Status::class)],
        ];
    }

    private function filteredQuery(array $filters): Builder
    {
        return Department::query()
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
            ));
    }
}