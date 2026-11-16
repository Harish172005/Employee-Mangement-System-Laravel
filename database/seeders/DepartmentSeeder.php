<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'IT',         'description' => 'Software development and infrastructure'],
            ['name' => 'HR',         'description' => 'Recruitment and people operations'],
            ['name' => 'Finance',    'description' => 'Accounts and payroll'],
            ['name' => 'Sales',      'description' => 'Sales and customer relations'],
            ['name' => 'Operations', 'description' => 'Day-to-day business operations'],
        ];

        foreach ($departments as $department) {
            // updateOrCreate: safe to run the seeder more than once
            Department::updateOrCreate(
                ['name' => $department['name']],
                ['description' => $department['description'], 'status' => 'active']
            );
        }
    }
}
