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
        Schema::create('messages', function (Blueprint $table) {
            $table->uuid('message_id')->primary();
            $table->uuid('sender_id');
            $table->uuid('recipient_id');
            $table->text('title')->nullable();
            $table->text('body');
            $table->string('type', 24);
            $table->boolean('is_read')->default(false);
            $table->timestampTz('sent_at')->default(DB::raw('CURRENT_TIMESTAMP'));

            // Foreign key constraints
            $table->foreign('sender_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade'); // Consider SET NULL if messages should persist if a user is deleted

            $table->foreign('recipient_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade'); // Consider SET NULL for recipient as well
        });

        // Add check constraint for message type
        DB::statement("ALTER TABLE messages ADD CONSTRAINT check_message_type CHECK (type IN ('alert', 'chat', 'promo'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
