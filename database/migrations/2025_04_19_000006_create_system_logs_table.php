<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_logs', function (Blueprint $table) {
            $table->uuid('log_id')->primary();
            $table->string('bed_id', 64);
            $table->string('severity', 10);
            $table->text('message');
            $table->timestampTz('logged_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        // Add check constraint for log severity
        DB::statement("ALTER TABLE system_logs ADD CONSTRAINT check_log_severity CHECK (severity IN ('DEBUG', 'INFO', 'WARN', 'ERROR', 'CRITICAL'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_logs');
    }
};
