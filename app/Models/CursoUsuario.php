<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo CursoUsuario
 *
 * Representa la asignación de un curso a un usuario.
 *
 * Atributos:
 * - course_id: Identificador del curso.
 * - user_id: Identificador del usuario.
 * - enabled: Estado de la asignación (activo o deshabilitado).
 *
 * Relaciones:
 * - curso: La asignación pertenece a un curso.
 * - user: La asignación pertenece a un usuario.
 *
 * @package App\Models
 */
class CursoUsuario extends Model
{
  protected $table = 'curso_usuarios';

  protected $fillable = [
    'course_id',
    'user_id',
    'enabled',
  ];

  public $timestamps = true;

  /**
   * Relación: La asignación pertenece a un curso.
   *
   * @return BelongsTo
   */
  public function curso(): BelongsTo
  {
    return $this->belongsTo(Curso::class, 'course_id');
  }

  /**
   * Relación: La asignación pertenece a un usuario.
   *
   * @return BelongsTo
   */
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class, 'user_id');
  }
}
