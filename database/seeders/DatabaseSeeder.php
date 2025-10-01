<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
        $this->call(RoleBasedUserSeeder::class);
        $this->call(MockDataSeeder::class);
    }
}
