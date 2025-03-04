<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo AprendizajeEstudiante
 *
 * Representa el proceso de aprendizaje de un estudiante en una actividad.
 *
 * Atributos:
 * - user_id: Identificador del usuario (estudiante).
 * - leccion_id: Identificador de la lección.
 * - actividad_id: Identificador de la actividad.
 * - tiempo_respuesta: Tiempo de respuesta del estudiante.
 * - errores_cometidos: Número de errores cometidos.
 * - nivel_confianza: Nivel de confianza del estudiante (bajo, medio, alto).
 * - reintentos: Número de reintentos.
 * - estado_emocional: Estado emocional del estudiante (tranquilo, estresado, motivado).
 * - hora_de_interaccion: Hora del día en que se realizó la interacción.
 * - correctas: Número de respuestas correctas.
 * - incorrectas: Número de respuestas incorrectas.
 * - progreso_acumulado: Progreso acumulado del estudiante.
 *
 * Relaciones:
 * - user: Pertenece a un usuario.
 * - leccion: Pertenece a una lección.
 * - actividad: Pertenece a una actividad.
 *
 * @package App\Models
 */
class AprendizajeEstudiante extends Model
{
  protected $table = 'aprendizaje_estudiante';

  protected $fillable = [
    'user_id',
    'leccion_id',
    'actividad_id',
    'tiempo_respuesta',
    'errores_cometidos',
    'nivel_confianza',
    'reintentos',
    'estado_emocional',
    'hora_de_interaccion',
    'correctas',
    'incorrectas',
    'progreso_acumulado',
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
