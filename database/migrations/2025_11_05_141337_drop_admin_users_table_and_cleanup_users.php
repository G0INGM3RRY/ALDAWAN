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
        // Drop the admin_users table - we'll just use role field in users table
        Schema::dropIfExists('admin_users');
        
        // Remove admin-related columns from users table if they exist
        Schema::table('users', function (Blueprint $table) {
            // Drop foreign key first before dropping column
            if (Schema::hasColumn('users', 'verified_by')) {
                $table->dropForeign(['verified_by']);
            }
        });
        
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'admin_level')) {
                $table->dropColumn('admin_level');
            }
            if (Schema::hasColumn('users', 'assigned_region')) {
                $table->dropColumn('assigned_region');
            }
            if (Schema::hasColumn('users', 'verification_status')) {
                $table->dropColumn('verification_status');
            }
            if (Schema::hasColumn('users', 'verification_submitted_at')) {
                $table->dropColumn('verification_submitted_at');
            }
            if (Schema::hasColumn('users', 'verified_at')) {
                $table->dropColumn('verified_at');
            }
            if (Schema::hasColumn('users', 'verified_by')) {
                $table->dropColumn('verified_by');
            }
            if (Schema::hasColumn('users', 'account_status')) {
                $table->dropColumn('account_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate admin_users table
        Schema::create('admin_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('admin_level', ['super_admin', 'admin', 'moderator'])->default('moderator');
            $table->json('permissions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->string('department')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique('user_id');
            $table->index(['admin_level', 'is_active']);
        });
        
        // Restore columns to users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('admin_level')->nullable();
            $table->string('assigned_region')->nullable();
            $table->string('verification_status')->default('unverified');
            $table->timestamp('verification_submitted_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->string('account_status')->default('active');
        });
    }
};
