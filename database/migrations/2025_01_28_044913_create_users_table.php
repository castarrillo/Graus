<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('users', function (Blueprint $table) {
      $table->id(); // BIGINT UNSIGNED
      $table->string('name');
      $table->string('email')->unique();
      $table->timestamp('email_verified_at')->nullable();
      $table->string('password');
      $table->rememberToken();

      // Campos nativos de Jetstream/Fortify
      $table->foreignId('current_team_id')->nullable();
      $table->string('profile_photo_path', 2048)->nullable();

      // Campos para relaciones
      $table->foreignId('role_id')
        ->default(2)
        ->constrained('roles')
        ->restrictOnDelete()
        ->cascadeOnUpdate();
      $table->foreignId('colegio_id')
        ->nullable()
        ->constrained('colegios')
        ->nullOnDelete()
        ->cascadeOnUpdate();

      $table->boolean('enabled')->default(true);
      $table->enum('nivel', ['principiante', 'intermedio', 'avanzado'])->nullable();

      // Campos adicionales solicitados
      $table->string('phone')->nullable();
      $table->date('date_of_birth')->nullable();
      $table->integer('grado')->nullable();

      $table->timestamps();
    });

    // Creación de la tabla para reset de contraseñas
    Schema::create('password_reset_tokens', function (Blueprint $table) {
      $table->string('email')->primary();
      $table->string('token');
      $table->timestamp('created_at')->nullable();
    });

    // La tabla sessions se crea en otra migración o aquí, según tu preferencia
    Schema::create('sessions', function (Blueprint $table) {
      $table->string('id')->primary();
      $table->foreignId('user_id')->nullable()->index();
      $table->string('ip_address', 45)->nullable();
      $table->text('user_agent')->nullable();
      $table->longText('payload');
      $table->integer('last_activity')->index();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('sessions');
    Schema::dropIfExists('password_reset_tokens');
    Schema::dropIfExists('users');
  }
};
