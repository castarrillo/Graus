<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('recompensas', function (Blueprint $table) {
      $table->id();
      $table->string('nombre', 100);
      $table->text('descripcion');
      $table->integer('puntos_requeridos');
      $table->boolean('estado')->default(true);
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('recompensas');
  }
};
