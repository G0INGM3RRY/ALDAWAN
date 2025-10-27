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
        // Formal jobseeker verification (Professional/Office jobs)
        Schema::create('formal_jobseeker_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jobseeker_id')->constrained('jobseeker_profiles')->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            
            // Identity (Strict verification required)
            $table->string('government_id_path'); // Government ID document
            
            // Education/Skills (Required for formal positions)
            $table->string('educational_document_path'); // Single diploma/certificate
            $table->string('skills_certificate_path')->nullable(); // Professional certification
            
            // Background Check (Required for formal positions)
            $table->string('nbi_clearance_path'); // NBI clearance
            
            // Admin Review
            $table->text('verification_notes')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->text('rejection_reason')->nullable();
            
            $table->timestamps();
            
            $table->index(['jobseeker_id', 'status']);
        });

        // Informal jobseeker verification (Household/Manual labor jobs)
        Schema::create('informal_jobseeker_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jobseeker_id')->constrained('jobseeker_profiles')->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            
            // Identity (Basic verification)
            $table->string('basic_id_path'); // Any government-issued ID
            
            // Character Reference (Required for community trust)
            $table->string('barangay_clearance_path'); // Community endorsement
            
            // Health Certificate (Job-specific, especially for household workers)
            $table->string('health_certificate_path')->nullable();
            
            // Admin Review
            $table->text('verification_notes')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->text('rejection_reason')->nullable();
            
            $table->timestamps();
            
            $table->index(['jobseeker_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formal_jobseeker_verifications');
        Schema::dropIfExists('informal_jobseeker_verifications');
    }
};