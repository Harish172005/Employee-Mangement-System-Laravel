<?php

namespace App\Http\Controllers;

use App\Services\ProfileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProfileController extends Controller
{
    // Dummy data. In Module 8 this becomes the logged-in employee: $request->user()->employee

    public function __construct(protected ProfileService $profileService) {}

    public function showAdmin()
    {
        return view('profile.admin-show', ['user' => auth()->user()]);
    }

    public function show()
    {

        $employee = $this->profileService->getOwnProfile();

        Gate::authorize('view', $employee);

        return view('profile.show', compact('employee'));
    }

    public function edit()
    {
        $employee = $this->profileService->getOwnProfile();
        Gate::authorize('update', $employee);

        return view('profile.edit', compact('employee'));
    }

    public function update(Request $request)
    {

        $employee = $this->profileService->updateOwnProfile($request->all());
        Gate::authorize('update', $employee);

        return redirect()->route('profile.show')->with('success', 'Profile updated successfullly');
    }

    public function department()
    {

        $id = auth()->user()->employee->id;
        $department = $this->profileService->getOwnDepartment();

        Gate::authorize('view', $department);

        $departmentPayload = [
            'name' => $department->name,
            'description' => $department->description,
            'employee_count' => $department->employees_count,
            'status' => $department->status,
        ];

        $employeesPayload = $department->employees->map(fn ($employee) => [
            'employee_code' => $employee->employee_code,
            'full_name' => $employee->full_name,
            'email' => $employee->email,
            'status' => $employee->status,
        ]);

        return view('profile.department', [
            'department' => $departmentPayload,
            'employees' => $employeesPayload,
        ]);
    }

    public function editPassword()
    {
        return view('profile.password');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required'],
            'password' => [
                'required',
                'confirmed',
                'min:8',
            ],
        ]);

        $this->profileService->changePassword(
            auth()->id(),
            $validated['current_password'],
            $validated['password']
        );

        $profileRoute = auth()->user()->role->value === 'admin'
            ? 'admin.profile.show'
            : 'profile.show';

        return redirect()->route($profileRoute)->with('success', 'Password updated successfully.');
    }
}
