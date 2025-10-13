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
        Schema::create('jobseeker_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jobseeker_id')->constrained('jobseeker_profiles')->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'rejected', 'requires_info'])->default('pending');
            
            // Identity Documents
            $table->string('government_id_type')->nullable(); // Driver's License, UMID, SSS, etc.
            $table->string('government_id_number')->nullable();
            $table->string('government_id_path')->nullable(); // File path for ID photo
            
            // Address Verification
            $table->string('barangay_clearance_path')->nullable();
            $table->string('proof_of_address_path')->nullable();
            
            // Additional Documents (for skills verification)
            $table->json('skill_certificates')->nullable(); // Array of certificate file paths
            $table->string('nbi_clearance_path')->nullable(); // For household workers
            
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
        Schema::dropIfExists('jobseeker_verifications');
    }
};