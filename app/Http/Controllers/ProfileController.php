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
    private function sample(): array
    {
        return [
            'code' => 'EMP001', 'first_name' => 'John', 'last_name' => 'Doe',
            'email' => 'john@example.com', 'phone' => '9876543210',
            'address' => 'Salem, Tamil Nadu', 'department' => 'IT',
            'designation' => 'Developer', 'date_of_joining' => '2024-06-01',
            'status' => 'active',
        ];
    }

    public function show()
    {
        Gate::authorize('view', Employee::class);
        $employee = $this->sample();

        return view('profile.show', compact('employee'));
    }

    public function edit()
    {
        $employee = $this->sample();

        return view('profile.edit', compact('employee'));
    }

    public function update(Request $request)
    {
        Gate::authorize('update', Employee::class);
        return redirect()->route('profile.show')->with('success', 'Profile updated (placeholder)');
    }

   public function department()
    {
        Gate::authorize('view', Department::class);
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
