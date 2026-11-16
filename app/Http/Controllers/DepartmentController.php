<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    // Dummy data. Replaced by Eloquent in Module 6.
    private function sample(int|string $id): array
    {
        return [
            'id' => $id, 'name' => 'IT', 'description' => 'Software and infrastructure',
            'status' => 'active', 'employee_count' => 45,
        ];
    }

    public function index(Request $request)
    {
        $search = $request->query('q');

        $departments = [
            ['id' => 1, 'name' => 'IT',      'description' => 'Software and infrastructure', 'status' => 'active'],
            ['id' => 2, 'name' => 'HR',      'description' => 'People and recruitment',      'status' => 'active'],
            ['id' => 3, 'name' => 'Finance', 'description' => 'Accounts and payroll',        'status' => 'inactive'],
        ];

        return view('departments.index', compact('departments', 'search'));
    }

    public function create()
    {
        return view('departments.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('admin.departments.index')
            ->with('success', 'Department created (placeholder)');
    }

    public function show($id)
    {
        $department = $this->sample($id);

        return view('departments.show', compact('department'));
    }

    public function edit($id)
    {
        $department = $this->sample($id);

        return view('departments.edit', compact('department'));
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('admin.departments.index')
            ->with('success', 'Department updated (placeholder)');
    }

    public function destroy($id)
    {
        return redirect()->route('admin.departments.index')
            ->with('success', 'Department deactivated (placeholder)');
    }
}
