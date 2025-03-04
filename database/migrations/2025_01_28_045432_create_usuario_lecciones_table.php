<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('usuario_lecciones', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
      $table->unsignedBigInteger('leccion_id');
      $table->text('status');
      $table->integer('score')->default(0);
      $table->integer('attempts')->default(0);
      $table->boolean('enabled')->default(true);
      $table->timestamp('started_at')->nullable();
      $table->timestamp('completed_at')->nullable();
      $table->timestamps();
      $table->foreign('leccion_id')->references('id')->on('lecciones')->onDelete('cascade')->onUpdate('cascade');
    });
  }
  public function down(): void
  {
    Schema::dropIfExists('usuario_lecciones');
  }
};
