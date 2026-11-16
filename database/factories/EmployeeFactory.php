<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            // EMP1000 - EMP9999 (EMP0001 is reserved for the seeded demo employee)
            'employee_code'   => 'EMP' . fake()->unique()->numberBetween(1000, 9999),
            'user_id'         => \App\Models\User::factory(),
            'first_name'      => fake()->firstName(),
            'last_name'       => fake()->lastName(),
            'email'           => fake()->unique()->safeEmail(),
            'phone'           => fake()->randomElement(['6', '7', '8', '9']) . fake()->numerify('#########'),
            'date_of_birth'   => fake()->dateTimeBetween('-50 years', '-21 years')->format('Y-m-d'),
            'gender'          => fake()->randomElement(['male', 'female', 'other']),
            'date_of_joining' => fake()->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
            'department_id'   => Department::factory(),
            
            'designation'     => fake()->randomElement([
                'Software Developer', 'QA Engineer', 'HR Executive', 'Accountant',
                'Sales Executive', 'Operations Manager', 'Team Lead', 'Business Analyst',
            ]),
            'salary'          => fake()->numberBetween(20000, 120000),
            'address'         => fake()->address(),
            'profile_photo'   => null,
            'status'          => fake()->boolean(90) ? 'active' : 'inactive',
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['status' => 'inactive']);
    }
}
