<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\Department;

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
        return redirect()->route('profile.show')->with('success', 'Profile updated (placeholder)');
    }

   public function department()
    {
        $employee = Employee::where('user_id', auth()->id())
            ->firstOrFail();

        $department = Department::findOrFail($employee->department_id);

        return view('profile.department', compact('department'));
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
