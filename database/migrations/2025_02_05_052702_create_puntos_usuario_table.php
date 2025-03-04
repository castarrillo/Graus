<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('puntos_usuario', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->restrictOnUpdate();
      $table->integer('puntos');
      $table->text('motivo');
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('puntos_usuario');
  }
};
