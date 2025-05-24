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
        Schema::create('patient_profiles', function (Blueprint $table) {
            $table->uuid('patient_id')->primary();
            $table->char('national_id', 16)->unique()->nullable();
            $table->date('date_of_birth')->nullable();
            $table->char('sex', 1)->nullable();
            // created_at is specified, updated_at is not.
            // Using default(DB::raw()) for database-level default.
            $table->timestampTz('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));

            // Foreign key constraint
            $table->foreign('patient_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade');
        });

        // Add check constraint for sex column using raw SQL
        // This should be safe as the table and column now exist.
        DB::statement("ALTER TABLE patient_profiles ADD CONSTRAINT check_sex CHECK (sex IN ('M', 'F', 'O'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_profiles');
    }
};
