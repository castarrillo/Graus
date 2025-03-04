<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('departamentos', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('pais_id');
      $table->string('name');
      $table->timestamps();

      $table->foreign('pais_id')
        ->references('id')
        ->on('paises')
        ->onDelete('cascade')
        ->onUpdate('cascade');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('departamentos');
  }
};
