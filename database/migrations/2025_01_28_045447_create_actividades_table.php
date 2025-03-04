<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('actividades', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('leccion_id');
      $table->string('name', 100);
      $table->text('content');
      $table->boolean('enabled')->default(true);
      $table->timestamps();
      $table->index('leccion_id');
      $table->foreign('leccion_id')->references('id')->on('lecciones')->onDelete('cascade')->onUpdate('cascade');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('actividades');
  }
};
