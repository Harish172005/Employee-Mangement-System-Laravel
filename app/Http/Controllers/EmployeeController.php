<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class EmployeeController extends Controller
{
    // ---- Dummy data. Replaced by Eloquent in Module 6 (then use Employee $employee binding). ----

    /** 25 fake employees so pagination and search have something to work with. */
    private function allEmployees(): array
    {
        $names = ['John Doe', 'Asha Rani', 'Ravi Kumar', 'Meena Devi', 'Karthik Raja',
                  'Priya Nair', 'Arun Prakash', 'Divya Shree', 'Vikram Singh', 'Lakshmi Iyer'];
        $departments = ['IT', 'HR', 'Finance', 'Sales', 'Operations'];

        $list = [];
        for ($i = 1; $i <= 25; $i++) {
            $list[] = [
                'id'         => $i,
                'code'       => sprintf('EMP%03d', $i),
                'name'       => $names[($i - 1) % count($names)],
                'department' => $departments[$i % count($departments)],
                'status'     => $i % 7 === 0 ? 'inactive' : 'active',
            ];
        }

        return $list;
    }

    /** Keep employees whose name, code or department contains the search text. */
    private function filtered(?string $search): array
    {
        $employees = $this->allEmployees();

        if ($search === null || $search === '') {
            return $employees;
        }

        return array_values(array_filter($employees, function ($employee) use ($search) {
            return stripos($employee['name'], $search) !== false
                || stripos($employee['code'], $search) !== false
                || stripos($employee['department'], $search) !== false;
        }));
    }

    private function sample(int|string $id): array
    {
        return [
            'id' => $id, 'code' => 'EMP' . str_pad((string) $id, 3, '0', STR_PAD_LEFT),
            'first_name' => 'John', 'last_name' => 'Doe',
            'email' => 'john@example.com', 'phone' => '9876543210',
            'date_of_birth' => '1998-05-14', 'date_of_joining' => '2024-06-01',
            'gender' => 'male', 'department_id' => 1,
            'designation' => 'Developer', 'salary' => 45000,
            'address' => 'Salem, Tamil Nadu', 'status' => 'active',
        ];
    }

    // ---- Resource actions ----

    public function index(Request $request)
    {
        $search  = $request->query('q');
        $items   = $this->filtered($search);
        $perPage = 10;
        $page    = LengthAwarePaginator::resolveCurrentPage();

        // Real paginator object, so $employees->links() renders Bootstrap pagination.
        // In Module 6 this becomes: Employee::search($search)->paginate(10)
        $employees = new LengthAwarePaginator(
            array_slice($items, ($page - 1) * $perPage, $perPage),
            count($items),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('employees.index', compact('employees', 'search'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $data = $request->only('first_name', 'last_name', 'email');   // Request object demo

        return redirect()->route('admin.employees.index')
            ->with('success', 'Employee created (placeholder)');
    }

    public function show($id)
    {
        $employee = $this->sample($id);

        return view('employees.show', compact('employee'));
    }

    public function edit($id)
    {
        $employee = $this->sample($id);

        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('admin.employees.show', $id)
            ->with('success', 'Employee updated (placeholder)');
    }

    public function destroy($id)
    {
        return redirect()->route('admin.employees.index')
            ->with('success', 'Employee deactivated (placeholder)');
    }

    // ---- JSON endpoint used by the live search (resources/js/employee-search.js) ----

    public function search(Request $request)
    {
        $results = array_slice($this->filtered($request->query('q')), 0, 10);

        $data = array_map(function ($employee) {
            $employee['urls'] = [
                'show'    => route('admin.employees.show', $employee['id']),
                'edit'    => route('admin.employees.edit', $employee['id']),
                'destroy' => route('admin.employees.destroy', $employee['id']),
            ];

            return $employee;
        }, $results);

        return response()->json([
            'status'  => true,
            'message' => 'Employees retrieved successfully',
            'data'    => $data,
        ]);
    }

    // ---- File download example ----

    public function export()
    {
        $csv = "id,name\n1,Test Employee\n";

        return response($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="employees.csv"',
        ]);
    }
}
