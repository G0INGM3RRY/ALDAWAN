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
        Schema::create('jobseeker_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jobseeker_profile_id')->constrained()->onDelete('cascade');
            $table->foreignId('skill_id')->constrained()->onDelete('cascade');
            $table->enum('proficiency_level', ['beginner', 'intermediate', 'advanced', 'expert'])->nullable();
            $table->integer('years_experience')->nullable();
            $table->timestamps();
            
            $table->unique(['jobseeker_profile_id', 'skill_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobseeker_skills');
    }
};
