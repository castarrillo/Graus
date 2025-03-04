<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('colegios', function (Blueprint $table) {
      $table->id();
      $table->string('name', 100);
      $table->string('address', 200)->nullable();
      $table->unsignedBigInteger('ciudad_id')->nullable();
      $table->boolean('enabled')->default(true);
      $table->timestamps();

      $table->foreign('ciudad_id')
        ->references('id')
        ->on('ciudades')
        ->onDelete('set null')
        ->onUpdate('cascade');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('colegios');
  }
};
