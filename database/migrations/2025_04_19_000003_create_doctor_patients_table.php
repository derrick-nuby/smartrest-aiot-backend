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
        Schema::create('doctor_patients', function (Blueprint $table) {
            $table->uuid('doctor_id');
            $table->uuid('patient_id');
            $table->timestampTz('assigned_at')->default(DB::raw('CURRENT_TIMESTAMP'));

            // Composite primary key
            $table->primary(['doctor_id', 'patient_id']);

            // Foreign key constraints
            $table->foreign('doctor_id')
                  ->references('doctor_id')
                  ->on('doctor_profiles')
                  ->onDelete('cascade');

            $table->foreign('patient_id')
                  ->references('patient_id')
                  ->on('patient_profiles')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_patients');
    }
};
