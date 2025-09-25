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
        Schema::table('employers', function (Blueprint $table) {
            $table->foreignId('company_type_id')->nullable()->constrained()->nullOnDelete();
            $table->text('company_description')->nullable();
            $table->string('website_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->integer('company_size_min')->nullable();
            $table->integer('company_size_max')->nullable();
            $table->year('founded_year')->nullable();
            $table->boolean('is_verified')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employers', function (Blueprint $table) {
            $table->dropForeign(['company_type_id']);
            $table->dropColumn([
                'company_type_id',
                'company_description',
                'website_url',
                'linkedin_url',
                'company_size_min',
                'company_size_max',
                'founded_year',
                'is_verified'
            ]);
        });
    }
};
