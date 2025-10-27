<?php

namespace Database\Seeders;

use App\Models\User;
 use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       
        // Call the lookup table seeders first
        $this->call([
            SkillSeeder::class,
            EducationLevelSeeder::class,
            ClassificationSeeder::class,
            DisabilitySeeder::class,
            CompanyTypeSeeder::class,
            
            // Then call existing seeders
            UserSeeder::class,
            JobSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}
