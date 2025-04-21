<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('model_has_roles', function (Blueprint $table) {
            $table->morphs('model');
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
            $table->primary(['model_id', 'model_type', 'role_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('model_has_roles');
    }
};