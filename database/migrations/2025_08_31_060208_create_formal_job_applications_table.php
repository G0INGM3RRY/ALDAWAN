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
        Schema::create('formal_job_applications', function (Blueprint $table) {
            $table->id();
            
            // Foreign keys - relationships
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // jobseeker who applied
            $table->foreignId('job_id')->constrained('job_listings')->onDelete('cascade'); // job being applied to
            
            // Application data
            $table->enum('status', ['pending', 'under_review', 'shortlisted', 'accepted', 'rejected'])->default('pending');
            $table->text('cover_letter')->nullable(); // optional cover letter
            $table->string('resume_file_path')->nullable(); // uploaded resume file
            $table->json('additional_documents')->nullable(); // other documents (certificates, portfolio)
            
            // Tracking timestamps
            $table->timestamp('applied_at')->useCurrent(); // when application was submitted
            $table->timestamp('reviewed_at')->nullable(); // when employer first viewed
            $table->timestamp('status_updated_at')->nullable(); // last status change
            
            // Employer feedback
            $table->text('employer_notes')->nullable(); // private notes from employer
            $table->text('rejection_reason')->nullable(); // feedback for rejected applications
            
            // Prevent duplicate applications
            $table->unique(['user_id', 'job_id'], 'unique_user_job_application');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formal_job_applications');
    }
};
