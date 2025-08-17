<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some employer users (role = 'employer')
        $employers = User::where('role', 'employer')->get();
        
        // If no employers exist, create a sample employer
        if ($employers->isEmpty()) {
            $employer = User::create([
                'name' => 'Sample Company',
                'email' => 'employer@example.com',
                'password' => bcrypt('password'),
                'role' => 'employer',
                'email_verified_at' => now(),
            ]);
            $employers = collect([$employer]);
        }

        // Sample job data
        $jobs = [
            [
                'job_title' => 'Software Developer',
                'description' => 'We are looking for a skilled Software Developer to join our team. You will be responsible for developing and maintaining web applications.',
                'requirements' => 'Bachelor\'s degree in Computer Science, 2+ years PHP/Laravel experience, knowledge of MySQL, HTML, CSS, JavaScript',
                'salary' => 40000.00,
                'location' => 'Manila, Philippines',
                'employment_type' => 'full-time',
                'status' => 'open',
                'classification' => 'Information Technology',
                'posted_at' => now(),
            ],
            [
                'job_title' => 'Customer Service Representative',
                'description' => 'Join our customer service team and help provide excellent support to our clients. Great communication skills required.',
                'requirements' => 'High school diploma, excellent communication skills, experience in customer service preferred',
                'salary' => 21500.00,
                'location' => 'Cebu, Philippines',
                'employment_type' => 'full-time',
                'status' => 'open',
                'classification' => 'Customer Service',
                'posted_at' => now(),
            ],
            [
                'job_title' => 'Marketing Assistant',
                'description' => 'Support our marketing team with various campaigns and promotional activities. Creative mindset needed.',
                'requirements' => 'Bachelor\'s degree in Marketing or related field, social media experience, creative writing skills',
                'salary' => 26000.00,
                'location' => 'Davao, Philippines',
                'employment_type' => 'full-time',
                'status' => 'open',
                'classification' => 'Marketing',
                'posted_at' => now(),
            ],
            [
                'job_title' => 'Data Entry Clerk',
                'description' => 'Part-time position for accurate data entry and file management. Perfect for students or those seeking flexible hours.',
                'requirements' => 'Good typing skills, attention to detail, basic computer literacy',
                'salary' => 400.00,
                'location' => 'Remote',
                'employment_type' => 'part-time',
                'status' => 'open',
                'classification' => 'Administrative',
                'posted_at' => now(),
            ],
            [
                'job_title' => 'Graphic Designer',
                'description' => 'Create visual content for digital and print media. Portfolio required.',
                'requirements' => 'Proficiency in Adobe Creative Suite, strong portfolio, creative problem-solving skills',
                'salary' => 30000.00,
                'location' => 'Quezon City, Philippines',
                'employment_type' => 'full-time',
                'status' => 'open',
                'classification' => 'Creative',
                'posted_at' => now(),
            ],
        ];

        // Insert jobs and assign to random employers
        foreach ($jobs as $job) {
            $job['company_id'] = $employers->random()->id;
            DB::table('job_listings')->insert($job);
        }
    }
}
