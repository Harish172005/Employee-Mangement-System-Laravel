<?php

namespace App\Http\Controllers;

use App\Enums\Gender;
use App\Enums\Role;
use App\Enums\Status;
use App\Http\Requests\EmployeeRequest;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class EmployeeController extends Controller
{
    private const PHOTO_DISK = 'public';
    private const PHOTO_DIR = 'employee-photos';

    public function index(Request $request)
    {
        Gate::authorize('viewAny', Employee::class);
        $filters = $request->validate($this->filterRules() + [
            'sort' => ['nullable', Rule::in(['employee_code', 'first_name', 'last_name', 'salary', 'date_of_joining', 'created_at'])],
            'direction' => ['nullable', Rule::in(['asc', 'desc'])],
            'with_trashed' => ['nullable', 'boolean'],
        ]);

        $query = $this->filteredQuery($filters);

        if ($request->boolean('with_trashed')) {
            $query->withTrashed();
        }

        $employees = $query
            ->orderBy($filters['sort'] ?? 'last_name', $filters['direction'] ?? 'asc')
            ->orderBy('first_name')
            ->paginate(10)
            ->withQueryString();

        return view('employees.index', compact('employees', 'filters'));
    }

    public function search(Request $request)
    {
        $filters = $request->validate($this->filterRules());

        $employees = $this->filteredQuery($filters)
            ->orderBy('last_name')
            ->limit(10)
            ->get()
            ->map(fn (Employee $employee) => [
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

    public function create()
    {
        return view('employees.create', [
            'availableUsers' => $this->availableUsers(),
        ]);
    }

    public function store(EmployeeRequest $request)
    {
        $data = $request->validated();
        unset($data['profile_photo']);
        $path = null;

        try {
            $data['employee_code'] = 'EMP' . str_pad((string) (Employee::withTrashed()->max('id') + 1), 4, '0', STR_PAD_LEFT);

            if ($request->hasFile('profile_photo')) {
                $path = $request->file('profile_photo')->store(self::PHOTO_DIR, self::PHOTO_DISK);
                $data['profile_photo'] = $path;
            }

            Employee::create($data);
        } catch (\Throwable $e) {
            if ($path) {
                Storage::disk(self::PHOTO_DISK)->delete($path);
            }

            Log::error('Employee create failed: ' . $e->getMessage());

            return back()->withInput()->with('error', 'Failed to create employee.');
        }

        return redirect()->route('admin.employees.index')->with('success', 'Employee created successfully.');
    }

    public function show(int $id)
    {
        Gate::authorize('view', Employee::class);
        $employee = Employee::withTrashed()->findOrFail($id);

        $employee->load([
            'department',
            'activityLogs' => fn ($query) => $query->latest('id')->limit(10),
        ]);

        $logs = $employee->activityLogs;

        return view('employees.show', compact('employee', 'logs'));
    }

    public function edit(int $id)
    {
        Gate::authorize('edit', Employee::class);
        $employee = Employee::findOrFail($id);
        $availableUsers = $this->availableUsers($employee); 

        return view('employees.edit',compact('employee', 'availableUsers' ));
    }

    public function update(EmployeeRequest $request, Employee $employee)
    {
        $data = $request->validated();
        unset($data['profile_photo']);

        $oldPhoto = $employee->profile_photo;
        $newPhoto = null;

        try {
            if ($request->hasFile('profile_photo')) {
                $newPhoto = $request->file('profile_photo')->store(self::PHOTO_DIR, self::PHOTO_DISK);
                $data['profile_photo'] = $newPhoto;
            }

            $employee->update($data);
        } catch (\Throwable $e) {
            if ($newPhoto) {
                Storage::disk(self::PHOTO_DISK)->delete($newPhoto);
            }

            Log::error('Employee update failed: ' . $e->getMessage(), ['employee_id' => $employee->id]);

            return back()->withInput()->with('error', 'Could not update the employee. Please try again.');
        }

        if ($newPhoto && $oldPhoto) {
            Storage::disk(self::PHOTO_DISK)->delete($oldPhoto);
        }

        return redirect()->route('admin.employees.show', $employee)->with('success', 'Employee updated.');
    }

    public function destroy(Employee $employee)
    {
        Gate::authorize('delete', Employee::class);
        DB::transaction(function () use ($employee) {
            $employee->status = Status::Inactive;
            $employee->saveQuietly();
            $employee->delete();
        });

        return redirect()->route('admin.employees.index')->with('success', 'Employee deactivated successfully.');
    }

    public function restore(int $id)
    {
        Gate::authorize('restore', Employee::class);
        $employee = Employee::withTrashed()->findOrFail($id);
        abort_unless($employee->trashed(), 404);

        DB::transaction(function () use ($employee) {
            $employee->restore();
            $employee->status = Status::Active;
            $employee->saveQuietly();
        });

        return redirect()->route('admin.employees.index', ['with_trashed' => 1])
            ->with('success', 'Employee restored successfully.');
    }

    public function forceDelete(int $id)
    {
        Gate::authorize('forceDelete', Employee::class);
        abort_if(auth()->check() && auth()->user()->role !== Role::Admin, 403);

        $employee = Employee::withTrashed()->findOrFail($id);
        abort_unless($employee->trashed(), 404);

        $photo = $employee->profile_photo;
        $employee->forceDelete();

        if ($photo) {
            Storage::disk(self::PHOTO_DISK)->delete($photo);
        }

        return redirect()->route('admin.employees.index', ['with_trashed' => 1])
            ->with('success', 'Employee permanently deleted.');
    }

    public function export()
    {
        $employees = Employee::with('department')->orderBy('last_name')->get();
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

    private function filterRules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:100'],
            'department' => ['nullable', 'integer', 'exists:departments,id'],
            'status' => ['nullable', Rule::enum(Status::class)],
            'gender' => ['nullable', Rule::enum(Gender::class)],
        ];
    }

    private function filteredQuery(array $filters): Builder
    {
        return Employee::query()
            ->with(['department', 'user'])
            ->search($filters['q'] ?? null)
            ->when($filters['department'] ?? null, fn (Builder $query, $department) => $query->where('department_id', $department))
            ->when($filters['status'] ?? null, fn (Builder $query, $status) => match ($status) {
                Status::Active->value => $query->active(),
                Status::Inactive->value => $query->inactive(),
                default => $query,
            })
            ->when($filters['gender'] ?? null, fn (Builder $query, $gender) => $query->where('gender', $gender));
    }

    private function availableUsers(?Employee $employee = null): array
{
    return User::where('role', Role::Employee->value)
        ->where(function ($query) use ($employee) {
            $query->doesntHave('employee');
            if ($employee?->user_id) {
                $query->orWhere('id', $employee->user_id);
            }
        })
        ->orderBy('name')
        ->pluck('name', 'id')
        ->all();
}
}