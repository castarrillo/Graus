<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo Actividad
 *
 * Representa una actividad asociada a una lección.
 *
 * Atributos:
 * - leccion_id: Identificador de la lección a la que pertenece la actividad.
 * - name: Nombre de la actividad.
 * - content: Contenido o descripción de la actividad.
 * - enabled: Estado de la actividad (1 = activo, 0 = deshabilitado).
 *
 * Relaciones:
 * - leccion: La actividad pertenece a una lección.
 *
 * @package App\Models
 */
class Actividad extends Model
{
  protected $table = 'actividades';

  protected $fillable = [
    'leccion_id',
    'name',
    'content',
    'enabled',
  ];

  public $timestamps = true;

  /**
   * Relación: La actividad pertenece a una lección.
   *
   * @return BelongsTo
   */
  public function leccion(): BelongsTo
  {
    return $this->belongsTo(Leccion::class, 'leccion_id');
  }
}
