<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Optionally create a test user
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'email' => 'test@example.com',
                'password' => Hash::make('password'),
                'first_name' => 'Cabin',
                'last_name' => 'Boy',
                'phone' => '123-456-7890',
            ]
        );

        // Call additional seeders
        $this->call(AdminUserSeeder::class);
    }
}
