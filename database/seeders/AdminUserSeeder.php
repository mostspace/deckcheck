<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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
                'system_role' => 'superadmin'
            ]

        
        );
    }
}