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
            // Drop the existing enum column
            $table->dropColumn('status');
        });
        
        Schema::table('job_listings', function (Blueprint $table) {
            // Add the new enum column with 'filled' option
            $table->enum('status', ['open', 'closed', 'filled'])->default('open');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_listings', function (Blueprint $table) {
            // Drop the modified column
            $table->dropColumn('status');
        });
        
        Schema::table('job_listings', function (Blueprint $table) {
            // Restore the original enum
            $table->enum('status', ['open', 'closed'])->default('open');
        });
    }
};
