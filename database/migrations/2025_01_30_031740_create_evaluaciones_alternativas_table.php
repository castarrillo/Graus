<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('evaluaciones_alternativas', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
      $table->unsignedBigInteger('leccion_id');
      $table->unsignedBigInteger('actividad_id');
      $table->enum('tipo_evaluacion', ['texto', 'emocional', 'desempeño']);
      $table->text('resultado');
      $table->boolean('enabled')->default(true);
      $table->timestamps();
      $table->foreign('leccion_id')->references('id')->on('lecciones')->onDelete('cascade')->onUpdate('cascade');
      $table->foreign('actividad_id')->references('id')->on('actividades')->onDelete('cascade')->onUpdate('cascade');
    });
  }
  public function down(): void
  {
    Schema::dropIfExists('evaluaciones_alternativas');
  }
};
