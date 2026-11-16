<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Models\Department;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    // Dummy data. In Module 8 this becomes the logged-in employee: $request->user()->employee

    public function show()
    {
        $employee = auth()->user()->employee;
        Gate::authorize('view', $employee);

        $employee = Employee::findOrFail($employee->id);

        return view('profile.show', compact('employee'));
    }

    public function edit()
    {
        $employee = auth()->user()->employee;
        Gate::authorize('update', $employee);

        return view('profile.edit', compact('employee'));
    }

    public function update(Request $request)
    {
        $employee = auth()->user()->employee;
        Gate::authorize('update', $employee);
        return redirect()->route('profile.show')->with('success', 'Profile updated successfullly');
    }

   public function department()
    {
        $department = auth()->user()->employee->department;
        Gate::authorize('view', $department);   
        $employee = Employee::where('email', auth()->user()->email)
            ->firstOrFail();

        $query = Department::withCount('employees')->findOrFail($employee->department_id);
        
         $department = ['name' => $query->name, 
        'description' => $query->description, 
        'employee_count' => $query->employees_count, 
        'status' => $query->status];

        $query = Employee::where('department_id', $employee->department_id)
            ->get();
        $employees = $query->map(function ($employee) {
            return [
                'employee_code' => $employee->employee_code,
                'full_name' => $employee->full_name,
                'email' => $employee->email,
                'status' => $employee->status,
            ];
        });
        return view('profile.department', compact('department', 'employees'));
    }


    public function editPassword()
    {
        return view('profile.password');
    }

    public function updatePassword(Request $request)
    {
        return redirect()->route('profile.show')->with('success', 'Password changed (placeholder)');
    }
}
