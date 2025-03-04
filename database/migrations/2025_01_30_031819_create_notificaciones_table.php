<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('notificaciones', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->restrictOnUpdate();
      $table->enum('tipo', ['sistema', 'recordatorio', 'premio']);
      $table->text('mensaje');
      $table->boolean('estado')->default(false); // no leída
      $table->timestamp('fecha_envio')->useCurrent();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('notificaciones');
  }
};
