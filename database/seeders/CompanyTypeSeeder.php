<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CompanyType;

class CompanyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companyTypes = [
            [
                'name' => 'Corporation',
                'description' => 'Large business corporation with multiple departments'
            ],
            [
                'name' => 'Small Business',
                'description' => 'Small to medium enterprise (SME) with fewer than 200 employees'
            ],
            [
                'name' => 'Startup',
                'description' => 'Early-stage company with innovative business model'
            ],
            [
                'name' => 'Non-Profit Organization',
                'description' => 'Organization focused on social causes rather than profit'
            ],
            [
                'name' => 'Government Agency',
                'description' => 'Federal, state, or local government organization'
            ],
            [
                'name' => 'Educational Institution',
                'description' => 'Schools, colleges, universities, and training centers'
            ],
            [
                'name' => 'Healthcare Organization',
                'description' => 'Hospitals, clinics, medical practices, health systems'
            ],
            [
                'name' => 'Consulting Firm',
                'description' => 'Professional services and consulting companies'
            ],
            [
                'name' => 'Manufacturing Company',
                'description' => 'Companies involved in production and manufacturing'
            ],
            [
                'name' => 'Retail Business',
                'description' => 'Retail stores, e-commerce, and consumer-facing businesses'
            ],
            [
                'name' => 'Technology Company',
                'description' => 'Software, hardware, and technology service companies'
            ],
            [
                'name' => 'Financial Services',
                'description' => 'Banks, insurance companies, investment firms'
            ]
        ];

        foreach ($companyTypes as $type) {
            CompanyType::create($type);
        }
    }
}
