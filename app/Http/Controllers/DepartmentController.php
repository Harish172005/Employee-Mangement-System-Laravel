<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::enum(Status::class)],
            'employees' => ['nullable', Rule::in(['with', 'without'])],
            'employee_status' => ['nullable', Rule::enum(Status::class)],
        ]);

        $query = Department::query()
            ->withCount(['employees' => fn ($query) => $query->withoutGlobalScope(\App\Scopes\ActiveEmployeeScope::class)])
            ->when($filters['q'] ?? null, fn ($query, $term) => $query->where(function ($query) use ($term) {
                $query->where('name', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%");
            }))
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->when(($filters['employees'] ?? null) === 'with', fn ($query) => $query->has(
                'employees',
                '>=',
                1,
                'and',
                fn ($employees) => $employees->withoutGlobalScope(\App\Scopes\ActiveEmployeeScope::class)
            ))
            ->when(($filters['employees'] ?? null) === 'without', fn ($query) => $query->doesntHave(
                'employees',
                'and',
                fn ($employees) => $employees->withoutGlobalScope(\App\Scopes\ActiveEmployeeScope::class)
            ))
            ->when($filters['employee_status'] ?? null, fn ($query, $status) => $query->whereHas(
                'employees',
                fn ($employees) => $employees->withoutGlobalScope(\App\Scopes\ActiveEmployeeScope::class)->where('status', $status)
            ));

        $departments = $query->orderBy('name')->paginate(10)->withQueryString();
        return view('departments.index', compact('departments', 'filters'));
    }

    public function create()
    {
        return view('departments.create');
    }

    public function store(Request $request)
    {
        Department::create($this->validatedData($request));

        return redirect()->route('admin.departments.index')->with('success', 'Department created successfully.');
    }

    public function show(int $id)
    {
        $department = Department::with(['employees' => fn ($query) => $query->withoutGlobalScope(\App\Scopes\ActiveEmployeeScope::class)])
            ->withCount(['employees' => fn ($query) => $query->withoutGlobalScope(\App\Scopes\ActiveEmployeeScope::class)])
            ->findOrFail($id);

        return view('departments.show', compact('department'));
    }

    public function edit(int $id)
    {
        $department = Department::findOrFail($id);

        return view('departments.edit', compact('department'));
    }

    public function update(Request $request, int $id)
    {
        $department = Department::findOrFail($id);
        $department->update($this->validatedData($request, $department));

        return redirect()->route('admin.departments.show', $department)->with('success', 'Department updated successfully.');
    }

    public function destroy(int $id)
    {
        $department = Department::findOrFail($id);
        $department->update(['status' => Status::Inactive]);

        return redirect()->route('admin.departments.index')->with('success', 'Department deactivated successfully.');
    }

    private function validatedData(Request $request, ?Department $department = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('departments', 'name')->ignore($department?->id)],
            'description' => ['nullable', 'string', 'max:5000'],
            'status' => ['required', Rule::enum(Status::class)],
        ]);
    }
}
