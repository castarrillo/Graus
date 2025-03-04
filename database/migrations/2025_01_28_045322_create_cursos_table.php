<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('cursos', function (Blueprint $table) {
      $table->id();
      $table->string('name', 100);
      $table->text('description');
      $table->foreignId('professor_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
      $table->boolean('enabled')->default(true);
      $table->timestamps();
    });
  }
  public function down(): void
  {
    Schema::dropIfExists('cursos');
  }
};
