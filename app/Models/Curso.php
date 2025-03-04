<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Modelo Curso
 *
 * Representa un curso impartido por un profesor.
 *
 * Atributos:
 * - name: Nombre del curso.
 * - description: Descripción del curso.
 * - professor_id: Identificador del profesor que imparte el curso.
 * - enabled: Estado del curso (activo o deshabilitado).
 *
 * Relaciones:
 * - profesor: El curso pertenece a un profesor (usuario).
 * - temas: Un curso tiene muchos temas.
 * - cursoUsuarios: Relación con la asignación de cursos a usuarios.
 * - estudiantes: Los usuarios inscritos en el curso.
 *
 * @package App\Models
 */
class Curso extends Model
{
  protected $table = 'cursos';

  protected $fillable = [
    'name',
    'description',
    'professor_id',
    'enabled',
  ];

  public $timestamps = true;

  /**
   * Relación: El curso pertenece a un profesor.
   *
   * @return BelongsTo
   */
  public function profesor(): BelongsTo
  {
    return $this->belongsTo(User::class, 'professor_id');
  }

  /**
   * Relación: Un curso tiene muchos temas.
   *
   * @return HasMany
   */
  public function temas(): HasMany
  {
    return $this->hasMany(Tema::class, 'curso_id');
  }

  /**
   * Relación: Un curso tiene muchas asignaciones a usuarios.
   *
   * @return HasMany
   */
  public function cursoUsuarios(): HasMany
  {
    return $this->hasMany(CursoUsuario::class, 'course_id');
  }

  /**
   * Relación: Los estudiantes inscritos en el curso.
   *
   * @return BelongsToMany
   */
  public function estudiantes(): BelongsToMany
  {
    return $this->belongsToMany(User::class, 'curso_usuarios', 'course_id', 'user_id')
      ->withTimestamps();
  }
}
