<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('curso_usuarios', function (Blueprint $table) {
      $table->id();
      $table->foreignId('course_id')
        ->constrained('cursos')
        ->cascadeOnDelete()
        ->cascadeOnUpdate();
      $table->foreignId('user_id')
        ->constrained('users')
        ->cascadeOnDelete()
        ->cascadeOnUpdate();
      $table->boolean('enabled')->default(true);
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('curso_usuarios');
  }
};
