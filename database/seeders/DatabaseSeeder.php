<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Order matters: employees need departments (and optionally users) first.
        $this->call([
            DepartmentSeeder::class,
            UserSeeder::class,
            EmployeeSeeder::class,
        ]);
    }
}
