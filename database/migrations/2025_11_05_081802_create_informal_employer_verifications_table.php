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
        Schema::create('informal_employer_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employer_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'rejected', 'requires_info'])->default('pending');
            
            // Verification documents for informal employers (households)
            $table->string('valid_id_path')->nullable(); // National ID, Driver's License, etc.
            $table->string('proof_of_address_path')->nullable(); // Utility bill, Barangay Certificate
            $table->string('barangay_clearance_path')->nullable(); // Optional barangay clearance
            
            // Verification metadata
            $table->text('verification_notes')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete(); // admin who verified
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
            
            $table->index(['employer_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('informal_employer_verifications');
    }
};
