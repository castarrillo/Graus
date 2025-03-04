<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo EvaluacionAlternativa
 *
 * Representa una evaluación alternativa realizada por un usuario en una lección y actividad.
 *
 * Atributos:
 * - user_id: Identificador del usuario.
 * - leccion_id: Identificador de la lección.
 * - actividad_id: Identificador de la actividad.
 * - tipo_evaluacion: Tipo de evaluación (texto, emocional, desempeño).
 * - resultado: Resultado de la evaluación.
 * - enabled: Estado de la evaluación (activo o deshabilitado).
 *
 * Relaciones:
 * - user: La evaluación pertenece a un usuario.
 * - leccion: La evaluación pertenece a una lección.
 * - actividad: La evaluación pertenece a una actividad.
 *
 * @package App\Models
 */
class EvaluacionAlternativa extends Model
{
  protected $table = 'evaluaciones_alternativas';

  protected $fillable = [
    'user_id',
    'leccion_id',
    'actividad_id',
    'tipo_evaluacion',
    'resultado',
    'enabled',
  ];

  public $timestamps = true;

  /**
   * Relación: Pertenece a un usuario.
   *
   * @return BelongsTo
   */
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  /**
   * Relación: Pertenece a una lección.
   *
   * @return BelongsTo
   */
  public function leccion(): BelongsTo
  {
    return $this->belongsTo(Leccion::class, 'leccion_id');
  }

  /**
   * Relación: Pertenece a una actividad.
   *
   * @return BelongsTo
   */
  public function actividad(): BelongsTo
  {
    return $this->belongsTo(Actividad::class, 'actividad_id');
  }
}
