<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user - simple approach, just set role to 'admin'
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@aldawan.com'],
            [
                'name' => 'Admin User',
                'email_verified_at' => now(),
                'password' => Hash::make('admin123'),
                'role' => 'admin'
            ]
        );

        $this->command->info('Admin user created/verified successfully!');
        $this->command->info('Email: admin@aldawan.com');
        $this->command->info('Password: admin123');
        $this->command->info('Role: admin');
    }
}
