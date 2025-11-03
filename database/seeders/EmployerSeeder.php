<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Employer;

class EmployerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if employers already exist
        if (User::where('role', 'employer')->where('email', 'LIKE', '%@samplecompany.com')->count() > 0) {
            $this->command->info('Sample employers already exist. Skipping employer seeding.');
            return;
        }

        // Create sample formal employers
        $formalEmployers = [
            [
                'name' => 'Tech Solutions Inc',
                'email' => 'techsolutions@samplecompany.com',
                'company_name' => 'Tech Solutions Inc',
                'employer_type' => 'formal',
                'company_description' => 'Leading software development company specializing in web and mobile applications.',
                'industry' => 'Information Technology',
                'company_size' => '50-100',
            ],
            [
                'name' => 'Global Marketing Corp',
                'email' => 'marketing@samplecompany.com',
                'company_name' => 'Global Marketing Corporation',
                'employer_type' => 'formal',
                'company_description' => 'Full-service marketing agency with expertise in digital marketing and brand management.',
                'industry' => 'Marketing & Advertising',
                'company_size' => '100-200',
            ],
            [
                'name' => 'Healthcare Plus',
                'email' => 'healthcare@samplecompany.com',
                'company_name' => 'Healthcare Plus Medical Center',
                'employer_type' => 'formal',
                'company_description' => 'Modern healthcare facility providing quality medical services.',
                'industry' => 'Healthcare',
                'company_size' => '200+',
            ],
        ];

        // Create sample informal employers
        $informalEmployers = [
            [
                'name' => 'Maria Santos',
                'email' => 'maria.santos@samplehome.com',
                'company_name' => 'Santos Household',
                'employer_type' => 'informal',
                'company_description' => 'Looking for reliable household help for our family in Makati.',
                'industry' => 'Household Services',
                'company_size' => '1-10',
            ],
            [
                'name' => 'Juan Reyes',
                'email' => 'juan.reyes@samplehome.com',
                'company_name' => 'Reyes Family',
                'employer_type' => 'informal',
                'company_description' => 'Seeking part-time help for elderly care and light housekeeping.',
                'industry' => 'Household Services',
                'company_size' => '1-10',
            ],
        ];

        // Create formal employers
        foreach ($formalEmployers as $employerData) {
            $user = User::create([
                'name' => $employerData['name'],
                'email' => $employerData['email'],
                'password' => Hash::make('password'),
                'role' => 'employer',
                'email_verified_at' => now(),
            ]);

            // Create employer profile
            Employer::create([
                'user_id' => $user->id,
                'company_name' => $employerData['company_name'],
                'employer_type' => $employerData['employer_type'],
                'company_description' => $employerData['company_description'],
                'industry' => $employerData['industry'],
                'company_size' => $employerData['company_size'],
                'street' => '123 Business St',
                'barangay' => 'Poblacion',
                'city' => 'Manila',
                'province' => 'Metro Manila',
                'zip_code' => '1000',
                'phone_number' => '02-1234-5678',
            ]);

            $this->command->info("Created formal employer: {$employerData['name']}");
        }

        // Create informal employers
        foreach ($informalEmployers as $employerData) {
            $user = User::create([
                'name' => $employerData['name'],
                'email' => $employerData['email'],
                'password' => Hash::make('password'),
                'role' => 'employer',
                'email_verified_at' => now(),
            ]);

            // Create employer profile
            Employer::create([
                'user_id' => $user->id,
                'company_name' => $employerData['company_name'],
                'employer_type' => $employerData['employer_type'],
                'company_description' => $employerData['company_description'],
                'industry' => $employerData['industry'],
                'company_size' => $employerData['company_size'],
                'street' => '456 Home Ave',
                'barangay' => 'Barangay 1',
                'city' => 'Quezon City',
                'province' => 'Metro Manila',
                'zip_code' => '1100',
                'phone_number' => '0917-123-4567',
            ]);

            $this->command->info("Created informal employer: {$employerData['name']}");
        }

        $this->command->info('Sample employers created successfully!');
    }
}
