<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('rachas_amigos', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('user1_id');
      $table->unsignedBigInteger('user2_id');
      $table->integer('streak_days')->default(0);   // Días consecutivos
      $table->timestamp('last_challenge_at')->nullable(); // Fecha/hora último desafío
      $table->boolean('enabled')->default(true);
      $table->timestamps();

      // Llaves foráneas
      $table->foreign('user1_id')
        ->references('id')->on('users')
        ->onDelete('cascade')
        ->onUpdate('cascade');

      $table->foreign('user2_id')
        ->references('id')->on('users')
        ->onDelete('cascade')
        ->onUpdate('cascade');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('rachas_amigos');
  }
};
