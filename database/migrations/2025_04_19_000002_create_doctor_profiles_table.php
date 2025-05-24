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
        Schema::create('doctor_profiles', function (Blueprint $table) {
            $table->uuid('doctor_id')->primary();
            $table->string('license_no', 40)->nullable();
            $table->string('specialty', 60)->nullable();
            $table->timestampTz('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));

            // Foreign key constraint
            $table->foreign('doctor_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_profiles');
    }
};
