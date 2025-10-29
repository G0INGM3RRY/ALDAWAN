<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * SIMPLE FIX: Drop and recreate verification tables with all fields nullable
     * This allows users to upload documents gradually instead of all at once
     */
    public function up(): void
    {
        // Drop existing tables (we'll recreate them with nullable fields)
        Schema::dropIfExists('formal_jobseeker_verifications');
        Schema::dropIfExists('informal_jobseeker_verifications');
        
        // Recreate formal jobseeker verification table with ALL fields nullable
        Schema::create('formal_jobseeker_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jobseeker_id')->constrained('jobseeker_profiles')->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            
            // Identity - NOW NULLABLE (users can upload later)
            $table->string('government_id_path')->nullable();
            
            // Education/Skills - NOW NULLABLE (users can upload later)
            $table->string('educational_document_path')->nullable();
            $table->string('skills_certificate_path')->nullable();
            
            // Background Check - NOW NULLABLE (users can upload later)
            $table->string('nbi_clearance_path')->nullable();
            
            // Admin Review
            $table->text('verification_notes')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->text('rejection_reason')->nullable();
            
            $table->timestamps();
            
            $table->index(['jobseeker_id', 'status']);
        });

        // Recreate informal jobseeker verification table with ALL fields nullable
        Schema::create('informal_jobseeker_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jobseeker_id')->constrained('jobseeker_profiles')->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            
            // Identity - NOW NULLABLE (users can upload later)
            $table->string('basic_id_path')->nullable();
            
            // Character Reference - NOW NULLABLE (users can upload later)
            $table->string('barangay_clearance_path')->nullable();
            
            // Health Certificate - Already nullable (optional)
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
        // Just drop the tables if we need to rollback
        Schema::dropIfExists('formal_jobseeker_verifications');
        Schema::dropIfExists('informal_jobseeker_verifications');
    }
};
