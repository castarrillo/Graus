<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo UsuarioLeccion
 *
 * Representa el registro de una lección realizada por un usuario.
 *
 * Atributos:
 * - user_id: Identificador del usuario.
 * - leccion_id: Identificador de la lección.
 * - status: Estado o progreso de la lección.
 * - score: Puntuación obtenida en la lección.
 * - attempts: Número de intentos realizados.
 * - enabled: Estado del registro (activo o deshabilitado).
 *
 * Relaciones:
 * - user: El registro pertenece a un usuario.
 * - leccion: El registro pertenece a una lección.
 *
 * @package App\Models
 */
class UsuarioLeccion extends Model
{
  protected $table = 'usuario_lecciones';

  protected $fillable = [
    'user_id',
    'leccion_id',
    'status',
    'score',
    'attempts',
    'enabled',
  ];

  public $timestamps = true;

  /**
   * Relación: El registro de lección pertenece a un usuario.
   *
   * @return BelongsTo
   */
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  /**
   * Relación: El registro de lección pertenece a una lección.
   *
   * @return BelongsTo
   */
  public function leccion(): BelongsTo
  {
    return $this->belongsTo(Leccion::class, 'leccion_id');
  }
}
