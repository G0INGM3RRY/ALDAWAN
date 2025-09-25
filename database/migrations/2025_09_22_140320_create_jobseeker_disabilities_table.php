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
        Schema::create('jobseeker_disabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jobseeker_profile_id')->constrained()->onDelete('cascade');
            $table->foreignId('disability_id')->constrained()->onDelete('cascade');
            $table->text('accommodation_needs')->nullable();
            $table->timestamps();
            
            $table->unique(['jobseeker_profile_id', 'disability_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobseeker_disabilities');
    }
};
