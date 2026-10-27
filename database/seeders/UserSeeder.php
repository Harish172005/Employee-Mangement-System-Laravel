<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Default admin. The 'hashed' cast on the model stores the password hashed.
        // CHANGE THIS PASSWORD outside local development.
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'     => 'Admin User',
                'username' => 'admin',
                'password' => 'password',
                'role'     => 'admin',
                'status'   => 'active',
            ]
        );
    }
}
