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
        // Create admin user
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@aldawan.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
            'role' => 'admin' // Set role to admin
        ]);

        // Add to admin_users table
        DB::table('admin_users')->insert([
            'user_id' => $adminUser->id,
            'admin_level' => 'admin',
            'is_active' => true,
            'department' => 'Administration',
            'notes' => 'Initial admin user created via seeder',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@aldawan.com');
        $this->command->info('Password: admin123');
    }
}
