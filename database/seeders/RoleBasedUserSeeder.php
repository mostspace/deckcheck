<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RoleBasedUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command?->info('Creating role-based users...');

        // Create users with different roles
        $this->createSuperAdmin();
        $this->createStaff();
        $this->createDev();

        $this->command?->info('Role-based users created successfully!');
    }


    /**
     * Create Super Admin user
     */
    private function createSuperAdmin(): void
    {
        $userData = [
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'superadmin@deckcheck.app',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'phone' => '+1-555-0001',
            'system_role' => 'superadmin',
            'is_beta_user' => true,
            'is_test_user' => false,
            'has_completed_onboarding' => true,
            'accepts_marketing' => true,
            'accepts_updates' => true,
            'agreed_at' => now(),
            'agreed_ip' => '127.0.0.1',
            'agreed_user_agent' => 'Seeder',
            'terms_version' => '1.0',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        User::firstOrCreate(['email' => $userData['email']], $userData);
        $this->command?->info('Super Admin user created/updated');
    }

    /**
     * Create Staff user
     */
    private function createStaff(): void
    {
        $userData = [
            'first_name' => 'Staff',
            'last_name' => 'Member',
            'email' => 'staff@deckcheck.app',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'phone' => '+1-555-0002',
            'system_role' => 'staff',
            'is_beta_user' => true,
            'is_test_user' => false,
            'has_completed_onboarding' => true,
            'accepts_marketing' => true,
            'accepts_updates' => true,
            'agreed_at' => now(),
            'agreed_ip' => '127.0.0.1',
            'agreed_user_agent' => 'Seeder',
            'terms_version' => '1.0',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        User::firstOrCreate(['email' => $userData['email']], $userData);
        $this->command?->info('👥 Staff user created/updated');
    }

    /**
     * Create Dev user
     */
    private function createDev(): void
    {
        $userData = [
            'first_name' => 'Dev',
            'last_name' => 'User',
            'email' => 'dev@deckcheck.app',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'phone' => '+1-555-0003',
            'system_role' => 'dev',
            'is_beta_user' => true,
            'is_test_user' => true,
            'has_completed_onboarding' => true,
            'accepts_marketing' => false,
            'accepts_updates' => true,
            'agreed_at' => now(),
            'agreed_ip' => '127.0.0.1',
            'agreed_user_agent' => 'Seeder',
            'terms_version' => '1.0',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        User::firstOrCreate(['email' => $userData['email']], $userData);
        $this->command?->info('Dev user created/updated');
    }

}
