<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared("
            DO $$
            BEGIN
                IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'user_role') THEN
                    CREATE TYPE user_role AS ENUM ('patient', 'doctor', 'customer', 'admin');
                END IF;
            END$$;
        ");

        DB::unprepared("
            DO $$
            BEGIN
                IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'sensor_type') THEN
                    CREATE TYPE sensor_type AS ENUM ('pressure', 'heart_rate', 'breathing_rate', 'temperature', 'humidity', 'body_movement', 'posture', 'vibration', 'sleep_apnea');
                END IF;
            END$$;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("
            DO $$
            BEGIN
                IF EXISTS (SELECT 1 FROM pg_type WHERE typname = 'user_role') THEN
                    DROP TYPE user_role;
                END IF;
            END$$;
        ");

        DB::unprepared("
            DO $$
            BEGIN
                IF EXISTS (SELECT 1 FROM pg_type WHERE typname = 'sensor_type') THEN
                    DROP TYPE sensor_type;
                END IF;
            END$$;
        ");
    }
};
