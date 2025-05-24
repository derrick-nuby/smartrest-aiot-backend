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
        Schema::create('sensor_readings', function (Blueprint $table) {
            $table->uuid('reading_id')->primary();
            $table->uuid('patient_id');
            $table->string('bed_id', 64);
            $table->string('sensor_type')->dbType('sensor_type');
            $table->float('sensor_value');
            $table->string('sensor_unit', 20)->nullable();
            $table->timestampTz('timestamp')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->jsonb('additional_metadata')->nullable();

            // Foreign key constraint
            $table->foreign('patient_id')
                  ->references('patient_id')
                  ->on('patient_profiles')
                  ->onDelete('cascade');
        });

        // Add check constraint for valid_pressure
        DB::statement("ALTER TABLE sensor_readings ADD CONSTRAINT valid_pressure CHECK (sensor_type != 'pressure' OR (sensor_value >= 0 AND sensor_value <= 100))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensor_readings');
    }
};
