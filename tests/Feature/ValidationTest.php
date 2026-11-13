<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_duplicate_department_name_is_rejected(): void
    {
        Department::factory()->create(['name' => 'Finance']);

        $this->actingAs(User::factory()->admin()->create())
            ->from(route('admin.departments.create'))
            ->post(route('admin.departments.store'), [
                'name' => 'Finance',
                'description' => 'Duplicate department',
                'status' => 'active',
            ])
            ->assertRedirect(route('admin.departments.create'))
            ->assertSessionHasErrors('name');

        $this->assertSame(1, Department::where('name', 'Finance')->count());
    }

    public function test_employee_younger_than_eighteen_is_rejected(): void
    {
        $department = Department::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
            ->from(route('admin.employees.create'))
            ->post(route('admin.employees.store'), [
                'username' => 'young.employee',
                'email' => 'young.employee@example.com',
                'password' => 'ValidPass1!',
                'role' => 'employee',
                'status' => 'active',
                'first_name' => 'Young',
                'last_name' => 'Person',
                'phone' => '5551234567',
                'date_of_birth' => now()->subYears(17)->toDateString(),
                'date_of_joining' => now()->toDateString(),
                'gender' => 'other',
                'department_id' => $department->id,
                'designation' => 'Intern',
                'salary' => 30000,
            ])
            ->assertRedirect(route('admin.employees.create'))
            ->assertSessionHasErrors('date_of_birth');

        $this->assertDatabaseMissing('employees', ['email' => 'young.employee@example.com']);
    }
}
