<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_profile_photo_is_stored_on_the_public_disk(): void
    {
        Storage::fake('public');
        $department = Department::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
            ->post(route('admin.employees.store'), $this->employeePayload($department) + [
                'profile_photo' => UploadedFile::fake()->createWithContent(
                    'profile.png',
                    base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+jXioAAAAASUVORK5CYII=')
                ),
            ])
            ->assertRedirect(route('admin.employees.index'))
            ->assertSessionHasNoErrors();

        $employee = Employee::where('email', 'upload.employee@example.com')->firstOrFail();
        $this->assertNotNull($employee->profile_photo);
        Storage::disk('public')->assertExists($employee->profile_photo);
    }

    public function test_non_image_profile_photo_is_rejected(): void
    {
        Storage::fake('public');
        $department = Department::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
            ->from(route('admin.employees.create'))
            ->post(route('admin.employees.store'), $this->employeePayload($department) + [
                'profile_photo' => UploadedFile::fake()->create('profile.pdf', 100, 'application/pdf'),
            ])
            ->assertRedirect(route('admin.employees.create'))
            ->assertSessionHasErrors('profile_photo');

        $this->assertDatabaseMissing('employees', ['email' => 'upload.employee@example.com']);
    }

    private function employeePayload(Department $department): array
    {
        return [
            'username' => 'upload.employee',
            'email' => 'upload.employee@example.com',
            'password' => 'ValidPass1!',
            'role' => 'employee',
            'status' => 'active',
            'first_name' => 'Jordan',
            'last_name' => 'Kim',
            'phone' => '5551234567',
            'date_of_birth' => '1991-04-15',
            'date_of_joining' => '2024-01-15',
            'gender' => 'other',
            'department_id' => $department->id,
            'designation' => 'Designer',
            'salary' => 62000,
        ];
    }
}
