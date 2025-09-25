<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Skill;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = [
            // Technical Skills
            ['name' => 'PHP Programming', 'description' => 'Server-side web development using PHP', 'category' => 'technical'],
            ['name' => 'Laravel Framework', 'description' => 'PHP web application framework', 'category' => 'technical'],
            ['name' => 'JavaScript', 'description' => 'Client-side and server-side programming', 'category' => 'technical'],
            ['name' => 'HTML/CSS', 'description' => 'Web markup and styling languages', 'category' => 'technical'],
            ['name' => 'MySQL Database', 'description' => 'Relational database management', 'category' => 'technical'],
            ['name' => 'React.js', 'description' => 'JavaScript library for user interfaces', 'category' => 'technical'],
            ['name' => 'Vue.js', 'description' => 'Progressive JavaScript framework', 'category' => 'technical'],
            ['name' => 'Python Programming', 'description' => 'General-purpose programming language', 'category' => 'technical'],
            ['name' => 'Java Programming', 'description' => 'Object-oriented programming language', 'category' => 'technical'],
            ['name' => 'C# Programming', 'description' => '.NET framework programming', 'category' => 'technical'],
            ['name' => 'Git Version Control', 'description' => 'Source code version control system', 'category' => 'technical'],
            ['name' => 'Docker', 'description' => 'Containerization platform', 'category' => 'technical'],
            ['name' => 'AWS Cloud Services', 'description' => 'Amazon Web Services cloud platform', 'category' => 'technical'],
            ['name' => 'Linux System Administration', 'description' => 'Unix-like operating system management', 'category' => 'technical'],
            
            // Soft Skills
            ['name' => 'Communication', 'description' => 'Verbal and written communication skills', 'category' => 'soft'],
            ['name' => 'Leadership', 'description' => 'Team management and guidance abilities', 'category' => 'soft'],
            ['name' => 'Problem Solving', 'description' => 'Analytical and creative problem-solving', 'category' => 'soft'],
            ['name' => 'Time Management', 'description' => 'Efficient task and schedule management', 'category' => 'soft'],
            ['name' => 'Teamwork', 'description' => 'Collaborative work environment skills', 'category' => 'soft'],
            ['name' => 'Adaptability', 'description' => 'Ability to adjust to changing circumstances', 'category' => 'soft'],
            ['name' => 'Critical Thinking', 'description' => 'Objective analysis and evaluation', 'category' => 'soft'],
            ['name' => 'Customer Service', 'description' => 'Client relationship and service skills', 'category' => 'soft'],
            
            // Language Skills
            ['name' => 'English (Fluent)', 'description' => 'Fluent English communication', 'category' => 'language'],
            ['name' => 'Filipino/Tagalog (Native)', 'description' => 'Native Filipino/Tagalog speaker', 'category' => 'language'],
            ['name' => 'Spanish', 'description' => 'Spanish language proficiency', 'category' => 'language'],
            ['name' => 'Japanese', 'description' => 'Japanese language proficiency', 'category' => 'language'],
            ['name' => 'Mandarin Chinese', 'description' => 'Mandarin Chinese language skills', 'category' => 'language'],
            
            // Trade Skills
            ['name' => 'Electrical Work', 'description' => 'Electrical installation and maintenance', 'category' => 'trade'],
            ['name' => 'Plumbing', 'description' => 'Plumbing installation and repair', 'category' => 'trade'],
            ['name' => 'Carpentry', 'description' => 'Woodworking and construction skills', 'category' => 'trade'],
            ['name' => 'Welding', 'description' => 'Metal joining and fabrication', 'category' => 'trade'],
            ['name' => 'Automotive Repair', 'description' => 'Vehicle maintenance and repair', 'category' => 'trade'],
            ['name' => 'HVAC Systems', 'description' => 'Heating, ventilation, and air conditioning', 'category' => 'trade'],
        ];

        foreach ($skills as $skill) {
            Skill::create($skill);
        }
    }
}
