<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo Log
 *
 * Representa un registro de auditoría que documenta las operaciones realizadas en el sistema.
 *
 * Atributos:
 * - user_id: Identificador del usuario que realizó la operación.
 * - roles_id: Identificador del rol asociado a la operación.
 * - colegios_id: Identificador del colegio relacionado.
 * - users_id: Identificador del usuario afectado.
 * - actividades_id: Identificador de la actividad relacionada.
 * - cursos_id: Identificador del curso relacionado.
 * - curso_usuarios_id: Identificador de la asignación de curso a usuario.
 * - temas_id: Identificador del tema relacionado.
 * - lecciones_id: Identificador de la lección relacionada.
 * - usuario_lecciones_id: Identificador del registro de lección del usuario.
 * - recompensas_id: Identificador de la recompensa relacionada.
 * - usuario_recompensas_id: Identificador de la asignación de recompensa a usuario.
 * - puntos_usuario_id: Identificador del registro de puntos del usuario.
 * - rachas_amigos_id: Identificador de la racha de amigos.
 * - operation: Operación realizada (INSERT, UPDATE, DELETE).
 * - old_data: Datos anteriores a la operación.
 * - new_data: Datos nuevos tras la operación.
 *
 * Nota: Se han eliminado los campos relacionados con "retos" y "usuario_retos", ya que ya no existen.
 *
 * @package App\Models
 */
class Log extends Model
{
  protected $table = 'logs';

  protected $fillable = [
    'user_id',
    'roles_id',
    'colegios_id',
    'users_id',
    'actividades_id',
    'cursos_id',
    'curso_usuarios_id',
    'temas_id',
    'lecciones_id',
    'usuario_lecciones_id',
    'recompensas_id',
    'usuario_recompensas_id',
    'puntos_usuario_id',
    'rachas_amigos_id',
    'operation',
    'old_data',
    'new_data',
  ];

  public $timestamps = false;

  /**
   * Relación: El log pertenece a un usuario.
   *
   * @return BelongsTo
   */
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class, 'user_id');
  }
}
