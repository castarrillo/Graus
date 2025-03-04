<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('lecciones', function (Blueprint $table) {
      $table->id();
      $table->foreignId('tema_id')->constrained('temas')->cascadeOnDelete()->cascadeOnUpdate();
      $table->string('title', 100);
      $table->text('description')->nullable();
      $table->enum('difficulty', ['facil', 'medio', 'dificil', 'personalizado'])->default('medio');
      $table->foreignId('created_by')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
      $table->boolean('enabled')->default(true);
      $table->timestamps();
    });
  }
  public function down(): void
  {
    Schema::dropIfExists('lecciones');
  }
};
