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
        Schema::table('skills', function (Blueprint $table) {
            $table->integer('usage_count')->default(0)->after('is_active');
            $table->boolean('is_custom')->default(false)->after('usage_count');
            $table->boolean('show_in_list')->default(true)->after('is_custom');
            $table->timestamp('last_used_at')->nullable()->after('show_in_list');
            $table->index(['category', 'show_in_list', 'usage_count']);
            $table->index(['category', 'is_custom', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->dropIndex(['category', 'show_in_list', 'usage_count']);
            $table->dropIndex(['category', 'is_custom', 'created_at']);
            $table->dropColumn(['usage_count', 'is_custom', 'show_in_list', 'last_used_at']);
        });
    }
};
