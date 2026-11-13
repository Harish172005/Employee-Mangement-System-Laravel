<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Enums\Status;
use App\Http\Requests\EmployeeRequest;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use App\Services\EmployeeService;
use App\Http\Requests\EmployeeFilterRequest;
class EmployeeController extends Controller
{
    public function __construct(protected EmployeeService $employeeService){
    }

    public function index(EmployeeFilterRequest $request)
    {
        Gate::authorize('viewAny', Employee::class);
        $filters = $request->validated();
        $employees = $this->employeeService->getAllEmployees();
        return view('employees.index', compact('employees', 'filters'));
    }

    public function search(EmployeeFilterRequest $request)
    {
        $filters = $request->validated();

        $employees = $this->employeeService->getFilteredEmployees($filters);

        $employees->getCollection()->transform(function (Employee $employee) {
        return [
            'id' => $employee->id,
            'employee_code' => $employee->employee_code,
            'first_name' => $employee->first_name,
            'last_name' => $employee->last_name,
            'status' => $employee->status->value,
            'deleted_at' => $employee->deleted_at,

            'department' => [
                'id' => $employee->department?->id,
                'name' => $employee->department?->name,
            ],

            'urls' => [
                'show' => route('admin.employees.show', $employee),
                'edit' => route('admin.employees.edit', $employee),
                'destroy' => route('admin.employees.destroy', $employee),
                'restore' => route('admin.employees.restore', $employee),
                'force_delete' => route('admin.employees.force-delete', $employee),
            ],
        ];
    });

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
        $this->employeeService->createEmployee($data, $request->file('profile_photo'));

        // try {
        //     $data['employee_code'] = 'EMP' . str_pad((string) (Employee::withTrashed()->max('id') + 1), 4, '0', STR_PAD_LEFT);

        //     if ($request->hasFile('profile_photo')) {
        //         $path = $request->file('profile_photo')->store(self::PHOTO_DIR, self::PHOTO_DISK);
        //         $data['profile_photo'] = $path;
        //     }

        //     Employee::create($data);
        // } catch (\Throwable $e) {
        //     if ($path) {
        //         Storage::disk(self::PHOTO_DISK)->delete($path);
        //     }

        //     Log::error('Employee create failed: ' . $e->getMessage());

        //     return back()->withInput()->with('error', 'Failed to create employee.');
        // }

        return redirect()->route('admin.employees.index')->with('success', 'Employee created successfully.');
    }

    public function show(int $id)
    {
        Gate::authorize('viewAny', Employee::class);
        $employee = $this->employeeService->getEmployeeById($id);

        $employee->load([
            'department',
            'activityLogs' => fn ($query) => $query->latest('id')->limit(10),
        ]);

        $logs = $employee->activityLogs;

        return view('employees.show', compact('employee', 'logs'));
    }

    public function edit(int $id)
    {
        Gate::authorize('update', Employee::class);
        $employee = $this->employeeService->getEmployeeById($id);
        $availableUsers = $this->availableUsers($employee); 

        return view('employees.edit',compact('employee', 'availableUsers' ));
    }

    public function update(EmployeeRequest $request, Employee $employee)
    {
        $data = $request->validated();
        unset($data['profile_photo']);

        return redirect()->route('admin.employees.show', $employee)->with('success', 'Employee updated.');
    }

     public function destroy(Employee $employee)
     {
            Gate::authorize('delete', $employee);

            DB::transaction(function () use ($employee) {
                $employee->update(['status' => Status::Inactive]);
                $employee->delete();                            // soft delete: fills deleted_at
            });

            return redirect()->route('admin.employees.index')->with('success', 'Employee deactivated successfully.');
     }

    public function restore(int $id)
    {
        Gate::authorize('restore', Employee::class);
        $this->employeeService->restoreEmployee($id);
        return redirect()->route('admin.employees.index', ['with_trashed' => 1])
            ->with('success', 'Employee restored successfully.');
    }

    public function forceDelete(int $id)
    {
        Gate::authorize('forceDelete', Employee::class);
        $this->employeeService->forceDeleteEmployee($id);

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


    // private function filteredQuery(array $filters): Builder
    // {
    //     return Employee::query()
    //         ->with(['department', 'user'])
    //         ->search($filters['q'] ?? null)
    //         ->when($filters['department'] ?? null, fn (Builder $query, $department) => $query->where('department_id', $department))
    //         ->when($filters['status'] ?? null, fn (Builder $query, $status) => match ($status) {
    //             Status::Active->value => $query->active(),
    //             Status::Inactive->value => $query->inactive(),
    //             default => $query,
    //         })
    //         ->when($filters['gender'] ?? null, fn (Builder $query, $gender) => $query->where('gender', $gender));
    // }
}