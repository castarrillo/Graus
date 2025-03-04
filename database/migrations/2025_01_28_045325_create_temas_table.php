<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('temas', function (Blueprint $table) {
      $table->id();
      $table->foreignId('curso_id')->constrained('cursos')->cascadeOnDelete()->cascadeOnUpdate();
      $table->string('title', 100);
      $table->text('description')->nullable();
      $table->boolean('enabled')->default(true);
      $table->timestamps();
    });
  }
  public function down(): void
  {
    Schema::dropIfExists('temas');
  }
};
