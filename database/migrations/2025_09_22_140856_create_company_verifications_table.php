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
        Schema::create('company_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employer_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'rejected', 'requires_info'])->default('pending');
            $table->string('business_registration_number')->nullable();
            $table->string('tax_id')->nullable();
            $table->string('verification_document_path')->nullable();
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
        Schema::dropIfExists('company_verifications');
    }
};
