<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
  public function up(): void
  {
    Schema::create('roles', function (Blueprint $table) {
      $table->id();
      $table->string('name', 50);
      $table->boolean('enabled')->default(true);
      $table->timestamps();
    });

    // Insertar roles por defecto
    DB::table('roles')->insert([
      ['name' => 'Administrador', 'enabled' => true, 'created_at' => now(), 'updated_at' => now()],
      ['name' => 'Estudiante',   'enabled' => true, 'created_at' => now(), 'updated_at' => now()],
      ['name' => 'Profesor',     'enabled' => true, 'created_at' => now(), 'updated_at' => now()],
      ['name' => 'Coordinador',  'enabled' => true, 'created_at' => now(), 'updated_at' => now()],
    ]);
  }

  public function down(): void
  {
    Schema::dropIfExists('roles');
  }
};
