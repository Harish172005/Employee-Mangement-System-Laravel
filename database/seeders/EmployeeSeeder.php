<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeSeeder extends Seeder
{
   public function run(): void
{
    $departmentIds = Department::pluck('id');

    if ($departmentIds->isEmpty()) {
        throw new \Exception(
            'No departments found. Please seed departments first.'
        );
    }

    DB::transaction(function () use ($departmentIds) {

        for ($i = 1; $i <= 50; $i++) {

            // Generate the employee's name only once
            $firstName = fake()->firstName();
            $lastName = fake()->lastName();

            // Create/update the User
            $user = User::updateOrCreate(
                ['email' => "employee{$i}@example.com"],
                [
                    // Same person represented in the users table
                    'name'     => $firstName . ' ' . $lastName,
                    'username' => "employee{$i}",
                    'password' => 'password',
                    'role'     => 'employee',
                    'status'   => fake()->boolean(90)
                        ? 'active'
                        : 'inactive',
                ]
            );

            // Create/update the corresponding Employee
            Employee::updateOrCreate(
                ['employee_code' =>
                    'EMP' . str_pad($i, 4, '0', STR_PAD_LEFT)
                ],
                [
                    'user_id'         => $user->id,
                    'first_name'      => $firstName,
                    'last_name'       => $lastName,
                    'email'           => "employee{$i}@company.com",
                    'phone'           => fake()->numerify('##########'),
                    'date_of_birth'   => fake()
                        ->dateTimeBetween('-50 years', '-20 years')
                        ->format('Y-m-d'),
                    'gender'          => fake()
                        ->randomElement(['male', 'female']),
                    'date_of_joining' => fake()
                        ->dateTimeBetween('-5 years', 'now')
                        ->format('Y-m-d'),
                    'department_id'   => $departmentIds->random(),
                    'designation'     => fake()->randomElement([
                        'Software Developer',
                        'Senior Developer',
                        'QA Engineer',
                        'HR Executive',
                        'Project Manager',
                    ]),
                    'salary'          => fake()->numberBetween(
                        25000,
                        100000
                    ),
                    'address'         => fake()->address(),
                    'status'          => fake()->boolean(90)
                        ? 'active'
                        : 'inactive',
                ]
            );
        }
    });
    
    }
}
