<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('aprendizaje_estudiante', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
      $table->unsignedBigInteger('leccion_id');
      $table->unsignedBigInteger('actividad_id');
      $table->integer('tiempo_respuesta');
      $table->integer('errores_cometidos');
      $table->enum('nivel_confianza', ['bajo', 'medio', 'alto']);
      $table->integer('reintentos')->default(0);
      $table->enum('estado_emocional', ['tranquilo', 'estresado', 'motivado'])->nullable();
      $table->tinyInteger('hora_de_interaccion')->nullable()->comment('Hora del dia (0-23)');
      $table->integer('correctas')->default(0);
      $table->integer('incorrectas')->default(0);
      $table->tinyInteger('progreso_acumulado')->nullable()->comment('0-100 posible');
      $table->timestamps();
      $table->foreign('leccion_id')->references('id')->on('lecciones')->onDelete('cascade')->onUpdate('cascade');
      $table->foreign('actividad_id')->references('id')->on('actividades')->onDelete('cascade')->onUpdate('cascade');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('aprendizaje_estudiante');
  }
};
