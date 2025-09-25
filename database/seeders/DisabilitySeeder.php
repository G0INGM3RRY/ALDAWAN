<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Disability;

class DisabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $disabilities = [
            [
                'name' => 'Visual Impairment',
                'description' => 'Blindness, low vision, or other visual disabilities',
                'category' => 'sensory'
            ],
            [
                'name' => 'Hearing Impairment',
                'description' => 'Deafness, hard of hearing, or hearing loss',
                'category' => 'sensory'
            ],
            [
                'name' => 'Mobility Impairment',
                'description' => 'Difficulty with movement, walking, or physical mobility',
                'category' => 'physical'
            ],
            [
                'name' => 'Cognitive Disability',
                'description' => 'Learning disabilities, intellectual disabilities',
                'category' => 'cognitive'
            ],
            [
                'name' => 'Autism Spectrum Disorder',
                'description' => 'Developmental disorder affecting communication and behavior',
                'category' => 'developmental'
            ],
            [
                'name' => 'Mental Health Condition',
                'description' => 'Depression, anxiety, bipolar disorder, PTSD',
                'category' => 'mental_health'
            ],
            [
                'name' => 'Speech Impairment',
                'description' => 'Difficulty with speech or communication',
                'category' => 'communication'
            ],
            [
                'name' => 'Chronic Illness',
                'description' => 'Diabetes, epilepsy, chronic pain, autoimmune conditions',
                'category' => 'chronic'
            ],
            [
                'name' => 'Multiple Disabilities',
                'description' => 'Combination of two or more disability types',
                'category' => 'multiple'
            ]
        ];

        foreach ($disabilities as $disability) {
            Disability::create($disability);
        }
    }
}
