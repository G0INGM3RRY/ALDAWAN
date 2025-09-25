<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('jobseeker_profiles', function (Blueprint $table) {
            // Remove JSON columns
            $table->dropColumn(['skills', 'disability', 'education', 'work_experience']);
            
            // Add proper foreign keys
            $table->foreignId('education_level_id')->nullable()->constrained()->nullOnDelete();
            
            // Add additional fields that were buried in JSON
            $table->string('institution_name')->nullable(); // for education
            $table->year('graduation_year')->nullable();
            $table->decimal('gpa', 3, 2)->nullable();
            $table->string('degree_field')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobseeker_profiles', function (Blueprint $table) {
            // Restore JSON columns
            $table->json('skills')->nullable();
            $table->json('disability')->nullable(); 
            $table->json('education')->nullable();
            $table->json('work_experience')->nullable();
            
            // Remove foreign keys and new columns
            $table->dropForeign(['education_level_id']);
            $table->dropColumn(['education_level_id', 'institution_name', 'graduation_year', 'gpa', 'degree_field']);
        });
    }
};
