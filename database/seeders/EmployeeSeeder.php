<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use App\Scopes\ActiveEmployeeScope;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $departmentIds = Department::pluck('id');
        $itId = Department::where('name', 'IT')->value('id');

        // One employee with a login account. Both rows are saved together:
        // if either insert fails, DB::transaction() rolls back both.
        DB::transaction(function () use ($itId) {
            $user = User::updateOrCreate(
                ['email' => 'employee@example.com'],
                [
                    'name'     => 'John Doe',
                    'username' => 'john',
                    'password' => 'password',
                    'role'     => 'employee',
                    'status'   => 'active',
                ]
            );

            Employee::withoutGlobalScope(ActiveEmployeeScope::class)->withTrashed()->updateOrCreate(
                ['employee_code' => 'EMP0001'],
                [
                    'user_id'         => $user->id,
                    'first_name'      => 'John',
                    'last_name'       => 'Doe',
                    'email'           => 'john.doe@example.com',
                    'phone'           => '9876543210',
                    'date_of_birth'   => '1998-05-14',
                    'gender'          => 'male',
                    'date_of_joining' => '2024-06-01',
                    'department_id'   => $itId,
                    'designation'     => 'Software Developer',
                    'salary'          => 45000,
                    'address'         => 'Salem, Tamil Nadu',
                    'status'          => 'active',
                ]
            );
        });

        // Stable seed keys keep reruns from creating duplicate demo employees.
        for ($index = 1; $index <= 50; $index++) {
            Employee::withoutGlobalScope(ActiveEmployeeScope::class)->withTrashed()->firstOrCreate(
                ['employee_code' => 'EMPSEED' . str_pad((string) $index, 4, '0', STR_PAD_LEFT)],
                [
                    'user_id' => null,
                    'first_name' => fake()->firstName(),
                    'last_name' => fake()->lastName(),
                    'email' => fake()->unique()->safeEmail(),
                    'phone' => fake()->randomElement(['6', '7', '8', '9']) . fake()->numerify('#########'),
                    'date_of_birth' => fake()->dateTimeBetween('-50 years', '-21 years')->format('Y-m-d'),
                    'gender' => fake()->randomElement(['male', 'female', 'other']),
                    'date_of_joining' => fake()->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
                    'department_id' => $departmentIds->random(),
                    'designation' => fake()->jobTitle(),
                    'salary' => fake()->numberBetween(20000, 120000),
                    'address' => fake()->address(),
                    'status' => fake()->boolean(90) ? 'active' : 'inactive',
                ]
            );
        }
    }
}
