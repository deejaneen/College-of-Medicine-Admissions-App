<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('action_logs')) {
            Schema::create('action_logs', function (Blueprint $table) {
                $table->id();
                $table->string('action');
                $table->string('table_name');
                $table->unsignedBigInteger('performed_by');
                $table->text('details')->nullable();
                $table->timestamp('performed_at')->useCurrent();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('action_logs');
    }
};