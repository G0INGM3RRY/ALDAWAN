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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // e.g., 'job_application', 'message', 'job_match'
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // additional notification data
            $table->timestamp('read_at')->nullable();
            $table->boolean('is_actionable')->default(false);
            $table->string('action_url')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'read_at']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
