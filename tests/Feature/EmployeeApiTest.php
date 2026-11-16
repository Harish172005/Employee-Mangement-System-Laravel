<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_search_employees_and_receive_json_results(): void
    {
        $employee = Employee::factory()->create([
            'first_name' => 'Avery',
            'last_name' => 'Morgan',
            'employee_code' => 'EMP2345',
        ]);

        $this->actingAs(User::factory()->admin()->create())
            ->getJson(route('admin.employees.search', ['q' => 'Avery']))
            ->assertOk()
            ->assertJsonPath('status', true)
            ->assertJsonPath('data.data.0.id', $employee->id)
            ->assertJsonPath('data.data.0.employee_code', 'EMP2345')
            ->assertJsonPath('data.data.0.department.name', $employee->department->name);
    }

    public function test_admin_can_download_employee_csv_export(): void
    {
        $department = Department::factory()->create(['name' => 'Research']);
        Employee::factory()->create([
            'first_name' => 'Jamie',
            'last_name' => 'Lee',
            'employee_code' => 'EMP5678',
            'department_id' => $department->id,
        ]);

        $this->actingAs(User::factory()->admin()->create())
            ->get(route('admin.employees.export'))
            ->assertOk()
            ->assertHeader('content-type', 'text/csv; charset=UTF-8')
            ->assertSee('Code,Name,Email,Department,Status', false)
            ->assertSee('"EMP5678","Jamie Lee"', false)
            ->assertSee('"Research"', false);
    }
}
