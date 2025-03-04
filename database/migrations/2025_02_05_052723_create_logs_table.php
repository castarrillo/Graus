<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('logs', function (Blueprint $table) {
      $table->id();

      $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
      $table->foreignId('users_id')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
      $table->foreignId('roles_id')->nullable()->constrained('roles')->nullOnDelete()->cascadeOnUpdate();
      $table->foreignId('colegios_id')->nullable()->constrained('colegios')->nullOnDelete()->cascadeOnUpdate();
      $table->foreignId('cursos_id')->nullable()->constrained('cursos')->nullOnDelete()->cascadeOnUpdate();
      $table->foreignId('curso_usuarios_id')->nullable()->constrained('curso_usuarios')->nullOnDelete()->cascadeOnUpdate();
      $table->foreignId('temas_id')->nullable()->constrained('temas')->nullOnDelete()->cascadeOnUpdate();
      $table->foreignId('lecciones_id')->nullable()->constrained('lecciones')->nullOnDelete()->cascadeOnUpdate();
      $table->foreignId('usuario_lecciones_id')->nullable()->constrained('usuario_lecciones')->nullOnDelete()->cascadeOnUpdate();
      $table->foreignId('actividades_id')->nullable()->constrained('actividades')->nullOnDelete()->cascadeOnUpdate();
      $table->foreignId('recompensas_id')->nullable()->constrained('recompensas')->nullOnDelete()->cascadeOnUpdate();
      $table->foreignId('usuario_recompensas_id')->nullable()->constrained('usuario_recompensas')->nullOnDelete()->cascadeOnUpdate();
      $table->foreignId('puntos_usuario_id')->nullable()->constrained('puntos_usuario')->nullOnDelete()->cascadeOnUpdate();
      $table->foreignId('rachas_amigos_id')->nullable()->constrained('rachas_amigos')->nullOnDelete()->cascadeOnUpdate();

      $table->enum('operation', ['INSERT', 'UPDATE', 'DELETE']);
      $table->text('old_data')->nullable();
      $table->text('new_data')->nullable();

      $table->timestamp('created_at')->useCurrent();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('logs');
  }
};
