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
        Schema::table('job_listings', function (Blueprint $table) {
            // Note: Original table doesn't have JSON skills/qualifications columns to remove
            // Just add the new normalized fields
            
            // Add education requirements
            $table->foreignId('minimum_education_level_id')->nullable()->constrained('education_levels')->nullOnDelete();
            $table->integer('minimum_experience_years')->default(0);
            
            // Add additional fields
            $table->text('benefits')->nullable();
            // employment_type already exists, but let's modify it to be an enum
            $table->dropColumn('employment_type');
            $table->enum('employment_type', ['full_time', 'part_time', 'contract', 'temporary', 'internship'])->default('full_time');
            $table->boolean('remote_work_available')->default(false);
            $table->integer('positions_available')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_listings', function (Blueprint $table) {
            // Remove new columns
            $table->dropForeign(['minimum_education_level_id']);
            $table->dropColumn([
                'minimum_education_level_id', 
                'minimum_experience_years', 
                'benefits', 
                'employment_type', 
                'remote_work_available', 
                'positions_available'
            ]);
            
            // Restore original employment_type as string
            $table->string('employment_type');
        });
    }
};
