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
        Schema::create('job_listings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('job_title');
            $table->text('description');
            $table->foreignId('company_id')->constrained('users')->onDelete('cascade');
            $table->string('location');
            $table->decimal('salary', 10, 2);
            $table->string('employment_type');
            $table->text('requirements');
            $table->timestamp('posted_at')->useCurrent();
            $table->enum('status', ['open', 'closed']);
            $table->string('classification');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_listings');
    }
};
