<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'rj@deckcheck.app'],
            [
                'email' => 'rj@deckcheck.app',
                'password' => Hash::make('password'),
                'first_name' => 'RJ',
                'last_name' => 'Cremin',
                'phone' => '2036158068',
                'system_role' => 'superadmin',
            ]

        );
    }
}
