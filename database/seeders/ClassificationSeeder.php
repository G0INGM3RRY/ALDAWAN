<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Classification;

class ClassificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classifications = [
            [
                'name' => 'Information Technology',
                'description' => 'Software development, IT support, cybersecurity',
                'code' => 'IT'
            ],
            [
                'name' => 'Healthcare & Medical',
                'description' => 'Medical professionals, healthcare workers, nursing',
                'code' => 'HEALTH'
            ],
            [
                'name' => 'Education & Training',
                'description' => 'Teachers, trainers, educational administrators',
                'code' => 'EDU'
            ],
            [
                'name' => 'Business & Finance',
                'description' => 'Accounting, banking, business analysis, consulting',
                'code' => 'BF'
            ],
            [
                'name' => 'Engineering',
                'description' => 'Civil, mechanical, electrical, software engineering',
                'code' => 'ENG'
            ],
            [
                'name' => 'Sales & Marketing',
                'description' => 'Sales representatives, marketing specialists, advertising',
                'code' => 'SM'
            ],
            [
                'name' => 'Customer Service',
                'description' => 'Customer support, call center, client relations',
                'code' => 'CS'
            ],
            [
                'name' => 'Manufacturing & Production',
                'description' => 'Factory workers, quality control, production management',
                'code' => 'MFG'
            ],
            [
                'name' => 'Construction & Trades',
                'description' => 'Construction workers, electricians, plumbers, carpenters',
                'code' => 'TRADE'
            ],
            [
                'name' => 'Transportation & Logistics',
                'description' => 'Drivers, delivery personnel, logistics coordinators',
                'code' => 'TRANS'
            ],
            [
                'name' => 'Hospitality & Tourism',
                'description' => 'Hotels, restaurants, travel industry, event planning',
                'code' => 'HOSP'
            ],
            [
                'name' => 'Agriculture & Farming',
                'description' => 'Farming, livestock, agricultural technology',
                'code' => 'AGRI'
            ],
            [
                'name' => 'Government & Public Service',
                'description' => 'Government employees, public administration',
                'code' => 'GOV'
            ],
            [
                'name' => 'Arts & Creative',
                'description' => 'Graphic design, photography, writing, media',
                'code' => 'ART'
            ],
            [
                'name' => 'Security & Law Enforcement',
                'description' => 'Security guards, law enforcement, private investigation',
                'code' => 'SEC'
            ]
        ];

        foreach ($classifications as $classification) {
            Classification::create($classification);
        }
    }
}
