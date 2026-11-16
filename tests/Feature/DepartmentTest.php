<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DepartmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_the_department_list(): void
    {
        Department::factory()->create(['name' => 'Engineering']);

        $this->actingAs(User::factory()->admin()->create())
            ->get(route('admin.departments.index'))
            ->assertOk()
            ->assertViewIs('departments.index');
    }

    public function test_admin_can_create_a_department_and_activity_log(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->post(route('admin.departments.store'), [
                'name' => 'Research',
                'description' => 'Research and development',
                'status' => 'active',
            ])
            ->assertRedirect(route('admin.departments.index'))
            ->assertSessionHas('success', 'Department created successfully.');

        $department = Department::where('name', 'Research')->firstOrFail();
        $this->assertDatabaseHas('activity_logs', [
            'loggable_type' => Department::class,
            'loggable_id' => $department->id,
            'action' => 'created',
        ]);
    }
}
