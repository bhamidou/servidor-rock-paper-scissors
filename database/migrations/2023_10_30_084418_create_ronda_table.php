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
        Schema::create('ronda', function (Blueprint $table) {
            $table->id()->unique();
            $table->integer('id_user_1');
            $table->integer('id_user_2');
            $table->integer('status');
            $table->integer('ganador')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ronda');
    }
};
