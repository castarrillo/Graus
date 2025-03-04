<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('usuario_recompensas', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('user_id');
      $table->unsignedBigInteger('recompensa_id');
      $table->timestamp('awarded_at')->nullable();
      $table->timestamps();

      $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade')
            ->onUpdate('cascade');
      $table->foreign('recompensa_id')
            ->references('id')
            ->on('recompensas')
            ->onDelete('cascade')
            ->onUpdate('cascade');
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('usuario_recompensas');
  }
};
