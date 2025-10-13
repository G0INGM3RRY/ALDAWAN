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
            // Add JSON field to store disability restrictions
            // This will store an array of disability IDs that are not suitable for this job
            $table->json('disability_restrictions')->nullable()->after('positions_available');
            
            // Add text field for additional accessibility notes
            $table->text('accessibility_notes')->nullable()->after('disability_restrictions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_listings', function (Blueprint $table) {
            $table->dropColumn(['disability_restrictions', 'accessibility_notes']);
        });
    }
};
