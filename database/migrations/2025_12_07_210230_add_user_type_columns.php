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
        Schema::table('action_logs', function (Blueprint $table) {
            // Add after existing column
            $table->string('user_type')->nullable()->after('performed_by');

            // user_model does NOT exist, so remove ->after()
            $table->string('user_role')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('action_logs', function (Blueprint $table) {
            $table->dropColumn(['user_type', 'user_role']);
        });
    }
};