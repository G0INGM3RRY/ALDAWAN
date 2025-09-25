<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EducationLevel;

class EducationLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $educationLevels = [
            [
                'name' => 'Elementary',
                'description' => 'Elementary school education',
                'level_order' => 1
            ],
            [
                'name' => 'High School',
                'description' => 'High school diploma or equivalent',
                'level_order' => 2
            ],
            [
                'name' => 'Senior High School',
                'description' => 'Senior high school (K-12)',
                'level_order' => 3
            ],
            [
                'name' => 'Technical/Vocational',
                'description' => 'Technical or vocational certification',
                'level_order' => 4
            ],
            [
                'name' => 'Associate Degree',
                'description' => 'Two-year college degree',
                'level_order' => 5
            ],
            [
                'name' => "Bachelor's Degree",
                'description' => 'Four-year undergraduate degree',
                'level_order' => 6
            ],
            [
                'name' => "Master's Degree",
                'description' => 'Graduate level degree',
                'level_order' => 7
            ],
            [
                'name' => 'Doctoral Degree',
                'description' => 'Highest academic degree (PhD, EdD, etc.)',
                'level_order' => 8
            ],
            [
                'name' => 'Professional Degree',
                'description' => 'Professional certification (Law, Medicine, etc.)',
                'level_order' => 7
            ]
        ];

        foreach ($educationLevels as $level) {
            EducationLevel::create($level);
        }
    }
}
