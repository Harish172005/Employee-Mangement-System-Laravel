<?php

namespace App\Http\Controllers;

use App\Enums\Gender;
use App\Enums\Role;
use App\Enums\Status;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'department' => ['nullable', 'integer', 'exists:departments,id'],
            'status' => ['nullable', Rule::enum(Status::class)],
            'gender' => ['nullable', Rule::enum(Gender::class)],
            'sort' => ['nullable', Rule::in(['employee_code', 'first_name', 'last_name', 'salary', 'date_of_joining', 'created_at'])],
            'direction' => ['nullable', Rule::in(['asc', 'desc'])],
            'with_trashed' => ['nullable', 'boolean'],
        ]);

        $query = Employee::withoutGlobalScope(\App\Scopes\ActiveEmployeeScope::class)
            ->with(['department', 'user'])
            ->search($filters['q'] ?? null)
            ->when(isset($filters['department']), fn ($query) => $query->where('department_id', $filters['department']))
            ->when(isset($filters['status']), fn ($query) => $query->where('status', $filters['status']))
            ->when(isset($filters['gender']), fn ($query) => $query->where('gender', $filters['gender']));

        if ($request->boolean('with_trashed')) {
            $query->withTrashed();
        }

        $sort = $filters['sort'] ?? 'last_name';
        $employees = $query->orderBy($sort, $filters['direction'] ?? 'asc')
            ->orderBy('first_name')
            ->paginate(10)
            ->withQueryString();

        return view('employees.index', compact('employees', 'filters'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        $data['employee_code'] = 'EMP' . str_pad((string) ($this->employeeQuery()->withTrashed()->max('id') + 1), 4, '0', STR_PAD_LEFT);
        $data['profile_photo'] = $request->file('profile_photo')?->store('employee-photos', 'public');
        Employee::create($data);

        return redirect()->route('admin.employees.index')->with('success', 'Employee created successfully.');
    }

    public function show(int $id)
    {
        $employee = $this->employeeQuery()->findOrFail($id);
        $employee->load(['department', 'skills', 'activityLogs']);

        return view('employees.show', compact('employee'));
    }

    public function edit(int $id)
    {
        $employee = $this->employeeQuery()->findOrFail($id);

        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, int $id)
    {
        $employee = $this->employeeQuery()->findOrFail($id);
        $data = $this->validatedData($request, $employee);

        if ($request->hasFile('profile_photo')) {
            if ($employee->profile_photo) {
                Storage::disk('public')->delete($employee->profile_photo);
            }
            $data['profile_photo'] = $request->file('profile_photo')->store('employee-photos', 'public');
        }

        $employee->fill($data);
        $employee->save();

        return redirect()->route('admin.employees.show', $employee)->with('success', 'Employee updated successfully.');
    }

    public function destroy(int $id)
    {
        $this->employeeQuery()->findOrFail($id)->delete();

        return redirect()->route('admin.employees.index')->with('success', 'Employee deactivated successfully.');
    }

    public function restore(int $id)
    {
        $employee = $this->employeeQuery()->withTrashed()->find($id);
        abort_if($employee === null, 404);
        abort_unless($employee->trashed(), 404);
        $employee->restore();

        return redirect()->route('admin.employees.index', ['with_trashed' => 1])->with('success', 'Employee restored successfully.');
    }

    public function forceDelete(int $id)
    {
        abort_unless(auth()->check() && auth()->user()->role === Role::Admin, 403);

        $employee = $this->employeeQuery()->withTrashed()->findOrFail($id);
        abort_unless($employee->trashed(), 404);
        $employee->forceDelete();

        return redirect()->route('admin.employees.index', ['with_trashed' => 1])->with('success', 'Employee permanently deleted.');
    }

    public function search(Request $request)
    {
        $query = $request->validate(['q' => ['nullable', 'string', 'max:100']])['q'] ?? null;
        $employees = $this->employeeQuery()->with('department')->search($query)
            ->orderBy('last_name')->limit(10)->get()
            ->map(fn ($employee) => [
                'id' => $employee->id,
                'code' => $employee->employee_code,
                'name' => $employee->full_name,
                'department' => $employee->department?->name,
                'status' => $employee->status->value,
                'urls' => [
                    'show' => route('admin.employees.show', $employee),
                    'edit' => route('admin.employees.edit', $employee),
                    'destroy' => route('admin.employees.destroy', $employee),
                ],
            ]);

        return response()->json([
            'status' => true,
            'message' => 'Employees retrieved successfully',
            'data' => $employees,
        ]);
    }

    public function export()
    {
        $employees = $this->employeeQuery()->with('department')->orderBy('last_name')->get();
        $csv = "Code,Name,Email,Department,Status\n";
        foreach ($employees as $employee) {
            $csv .= implode(',', array_map(
                fn ($value) => '"' . str_replace('"', '""', (string) $value) . '"',
                [$employee->employee_code, $employee->full_name, $employee->email, $employee->department?->name, $employee->status->value]
            )) . "\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="employees.csv"',
        ]);
    }

    private function employeeQuery()
    {
        return Employee::withoutGlobalScope(\App\Scopes\ActiveEmployeeScope::class);
    }

    private function validatedData(Request $request, ?Employee $employee = null): array
    {
        $id = $employee?->id;

        return $request->validate([
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255', Rule::unique('employees', 'email')->ignore($id)],
            'phone' => ['required', 'string', 'max:10'],
            'date_of_birth' => ['required', 'date'],
            'date_of_joining' => ['required', 'date'],
            'gender' => ['required', Rule::enum(Gender::class)],
            'department_id' => ['required', 'exists:departments,id'],
            'designation' => ['required', 'string', 'max:100'],
            'salary' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'address' => ['nullable', 'string', 'max:5000'],
            'status' => ['required', Rule::enum(Status::class)],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);
    }
}
