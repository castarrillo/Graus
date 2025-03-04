<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('ciudades', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('departamento_id');
      $table->string('name');
      $table->timestamps();

      $table->foreign('departamento_id')
        ->references('id')
        ->on('departamentos')
        ->onDelete('cascade')
        ->onUpdate('cascade');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('ciudades');
  }
};
