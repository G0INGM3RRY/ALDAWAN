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
        Schema::table('jobseeker_profiles', function (Blueprint $table) {
            // Add education JSON column for formal jobseekers to store multiple education records
            // Informal jobseekers will continue using education_level_id and related fields
            $table->json('education')->nullable()->after('employmentstatus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobseeker_profiles', function (Blueprint $table) {
            $table->dropColumn('education');
        });
    }
};
