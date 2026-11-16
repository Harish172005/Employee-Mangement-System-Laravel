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
use Illuminate\Support\Facades\Gate;
use App\Services\DepartmentService;
use App\Http\Requests\DepartmentFilterRequest;

class DepartmentController extends Controller
{
    public function __construct(protected DepartmentService $departmentService)
    { 
    }
   
    public function index(DepartmentFilterRequest $request)
    {
        $filters = $request->validated();

        $departments = $this->departmentService->getAllDepartments($filters);

        return view('departments.index', compact('departments', 'filters'));
    }

    public function create()
    {
        return view('departments.create');
    }

    public function store(DepartmentRequest $request)
    {
        Gate::authorize('create', Department::class);
        $this->departmentService->createDepartment($request->validated());

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department created successfully.');
    }

    public function show(int $id)
    {
        Gate::authorize('viewAny', Department::class);
        $department = $this->departmentService->getDepartmentById($id);

        return view('departments.show', compact('department'));
    }

    public function edit(int $id)
    {
        $department = $this->departmentService->getDepartmentById($id);

        return view('departments.edit', compact('department'));
    }

    public function update(DepartmentRequest $request, Department $department)
    {
        Gate::authorize('update', Department::class);
        $this->departmentService->updateDepartment($department, $request->validated());

        return redirect()->route('admin.departments.show', $department)
            ->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        Gate::authorize('delete', Department::class);

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department deactivated successfully.');
    }

    public function restore(int $id)
    {
        Gate::authorize('restore', Department::class);

        $this->departmentService->restoreDepartment($id);

        return redirect()->route('admin.departments.index', ['with_trashed' => 1])
            ->with('success', 'Department restored successfully.');
    }

    public function forceDelete(int $id)
    {
        Gate::authorize('forceDelete', Department::class);
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
}