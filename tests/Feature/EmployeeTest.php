<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_the_employee_list(): void
    {
        Employee::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
            ->get(route('admin.employees.index'))
            ->assertOk()
            ->assertViewIs('employees.index');
    }

    public function test_admin_can_create_an_employee_and_login_account(): void
    {
        $department = Department::factory()->create();
        $user = User::factory()->admin()->create();

        $this->actingAs($user)
            ->post(route('admin.employees.store'), $this->employeePayload($department))
            ->assertRedirect(route('admin.employees.index'))
            ->assertSessionHas('success', 'Employee created successfully.');

        $this->assertDatabaseHas('users', [
            'username' => 'new.employee',
            'email' => 'new.employee@example.com',
            'role' => 'employee',
        ]);
        $this->assertDatabaseHas('employees', [
            'first_name' => 'Taylor',
            'last_name' => 'Reed',
            'department_id' => $department->id,
        ]);
    }

    public function test_admin_deactivating_an_employee_soft_deletes_the_record(): void
    {
        $employee = Employee::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
            ->delete(route('admin.employees.destroy', $employee))
            ->assertRedirect(route('admin.employees.index'))
            ->assertSessionHas('success', 'Employee deactivated successfully.');

        $this->assertSoftDeleted('employees', ['id' => $employee->id]);
        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'status' => 'inactive',
        ]);
    }

    private function employeePayload(Department $department): array
    {
        return [
            'username' => 'new.employee',
            'email' => 'new.employee@example.com',
            'password' => 'ValidPass1!',
            'role' => 'employee',
            'status' => 'active',
            'first_name' => 'Taylor',
            'last_name' => 'Reed',
            'phone' => '5551234567',
            'date_of_birth' => '1990-04-15',
            'date_of_joining' => '2024-01-15',
            'gender' => 'other',
            'department_id' => $department->id,
            'designation' => 'Software Developer',
            'salary' => 65000,
        ];
    }
}
