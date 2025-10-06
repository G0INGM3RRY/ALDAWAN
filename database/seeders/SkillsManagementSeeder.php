<?php<?php



namespace Database\Seeders;namespace Database\Seeders;



use Illuminate\Database\Seeder;use Illuminate\Database\Seeder;

use App\Models\Skill;use App\Models\Skill;

use Illuminate\Support\Facades\DB;use Illuminate\Support\Facades\DB;



class SkillsManagementSeeder extends Seederclass SkillsManagementSeeder extends Seeder

{{

    /**    /**

     * Run the database seeder.     * Run the database seeder.

     */     */

    public function run(): void    public function run(): void

    {    {

        // Update existing skills to be marked as built-in (not custom)        // Update existing skills to be marked as built-in (not custom)

        DB::table('skills')->update([        DB::table('skills')->update([

            'is_custom' => false,            'is_custom' => false,

            'show_in_list' => true,            'show_in_list' => true,

            'usage_count' => 1            'usage_count' => 1 // Give built-in skills a base usage count

        ]);        ]);



        // Add some popular formal skills if they don't exist        // Add some popular formal skills if they don't exist

        $formalSkills = [        $formalSkills = [

            'Microsoft Office', 'Excel', 'PowerPoint', 'Word Processing',             'Microsoft Office', 'Excel', 'PowerPoint', 'Word Processing', 

            'Data Entry', 'Customer Service', 'Communication Skills',             'Data Entry', 'Customer Service', 'Communication Skills', 

            'Problem Solving', 'Time Management', 'Leadership',            'Problem Solving', 'Time Management', 'Leadership',

            'Project Management', 'Accounting', 'Administrative Skills',            'Project Management', 'Accounting', 'Administrative Skills',

            'English Proficiency', 'Computer Literacy'            'English Proficiency', 'Computer Literacy'

        ];        ];



        foreach ($formalSkills as $skillName) {        foreach ($formalSkills as $skillName) {

            Skill::firstOrCreate([            Skill::firstOrCreate([

                'name' => $skillName,                'name' => $skillName,

                'category' => 'formal'                'category' => 'formal'

            ], [            ], [

                'description' => "Professional skill: {$skillName}",                'description' => "Professional skill: {$skillName}",

                'is_active' => true,                'is_active' => true,

                'is_custom' => false,                'is_custom' => false,

                'show_in_list' => true,                'show_in_list' => true,

                'usage_count' => 1                'usage_count' => 1

            ]);            ]);

        }        }



        // Add some popular informal skills if they don't exist        // Add some popular informal skills if they don't exist

        $informalSkills = [        $informalSkills = [

            'House Cleaning', 'Cooking', 'Child Care', 'Elder Care',            'House Cleaning', 'Cooking', 'Child Care', 'Elder Care',

            'Gardening', 'Laundry', 'Pet Care', 'Basic Repairs',            'Gardening', 'Laundry', 'Pet Care', 'Basic Repairs',

            'Massage Therapy', 'Sewing', 'Tutoring', 'Driving'            'Massage Therapy', 'Sewing', 'Tutoring', 'Driving'

        ];        ];



        foreach ($informalSkills as $skillName) {        foreach ($informalSkills as $skillName) {

            Skill::firstOrCreate([            Skill::firstOrCreate([

                'name' => $skillName,                'name' => $skillName,

                'category' => 'informal'                'category' => 'informal'

            ], [            ], [

                'description' => "Informal skill: {$skillName}",                'description' => "Informal skill: {$skillName}",

                'is_active' => true,                'is_active' => true,

                'is_custom' => false,                'is_custom' => false,

                'show_in_list' => true,                'show_in_list' => true,

                'usage_count' => 1                'usage_count' => 1

            ]);            ]);

        }        }



        $this->command->info('Skills management seeder completed successfully!');        $this->command->info('Skills management seeder completed successfully!');

        $this->command->info('Existing skills marked as built-in, new popular skills added.');        $this->command->info('Existing skills marked as built-in, new popular skills added.');

    }    }

}}Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkillsManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
    }
}
