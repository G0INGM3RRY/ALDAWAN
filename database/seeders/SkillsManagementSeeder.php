<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Skill;
use Illuminate\Support\Facades\DB;

class SkillsManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mark existing skills as built-in (not custom) and ensure they show in lists
        DB::table('skills')->update([
            'is_custom' => false,
            'show_in_list' => true,
            'usage_count' => 1, // Give built-in skills a base usage count
        ]);

        // Popular formal skills
        $formalSkills = [
            'Microsoft Office', 'Excel', 'PowerPoint', 'Word Processing',
            'Data Entry', 'Customer Service', 'Communication Skills',
            'Problem Solving', 'Time Management', 'Leadership',
            'Project Management', 'Accounting', 'Administrative Skills',
            'English Proficiency', 'Computer Literacy',
        ];

        foreach ($formalSkills as $skillName) {
            Skill::firstOrCreate(
                ['name' => $skillName, 'category' => 'formal'],
                [
                    'description' => "Professional skill: {$skillName}",
                    'is_active' => true,
                    'is_custom' => false,
                    'show_in_list' => true,
                    'usage_count' => 1,
                ]
            );
        }

        // Popular informal skills
        $informalSkills = [
            'House Cleaning', 'Cooking', 'Child Care', 'Elder Care',
            'Gardening', 'Laundry', 'Pet Care', 'Basic Repairs',
            'Massage Therapy', 'Sewing', 'Tutoring', 'Driving',
        ];

        foreach ($informalSkills as $skillName) {
            Skill::firstOrCreate(
                ['name' => $skillName, 'category' => 'informal'],
                [
                    'description' => "Informal skill: {$skillName}",
                    'is_active' => true,
                    'is_custom' => false,
                    'show_in_list' => true,
                    'usage_count' => 1,
                ]
            );
        }

        $this->command->info('Skills management seeder completed successfully!');
        $this->command->info('Existing skills marked as built-in, new popular skills added.');
    }
}
