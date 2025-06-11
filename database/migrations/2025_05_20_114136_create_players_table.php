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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('player_id')->unique();
            $table->string('nickname');
            $table->string('region');
            $table->integer('level')->default(0);
            $table->integer('total_matches')->default(0);
            $table->integer('win_rate')->default(0);
            $table->string('favorite_hero')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};