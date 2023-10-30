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
        Schema::create('partida', function (Blueprint $table) {
            $table->id()->unique();
            $table->integer('id_ronda');
            $table->integer('tirada_user_1');
            $table->integer('tirada_user_2');
            $table->integer('winner');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partida');
    }
};
