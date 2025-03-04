<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo Leccion
 *
 * Representa una lección perteneciente a un tema.
 *
 * Atributos:
 * - tema_id: Identificador del tema al que pertenece la lección.
 * - title: Título de la lección.
 * - description: Descripción de la lección.
 * - difficulty: Dificultad de la lección (facil, medio, dificil, personalizado).
 * - created_by: Identificador del usuario que creó la lección.
 * - enabled: Estado de la lección (activo o deshabilitado).
 *
 * Relaciones:
 * - tema: La lección pertenece a un tema.
 * - actividades: La lección tiene muchas actividades.
 *
 * @package App\Models
 */
class Leccion extends Model
{
  protected $table = 'lecciones';

  protected $fillable = [
    'tema_id',
    'title',
    'description',
    'difficulty',
    'created_by',
    'enabled',
  ];

  public $timestamps = true;

  /**
   * Relación: La lección pertenece a un tema.
   *
   * @return BelongsTo
   */
  public function tema(): BelongsTo
  {
    return $this->belongsTo(Tema::class, 'tema_id');
  }

  /**
   * Relación: La lección tiene muchas actividades.
   *
   * @return HasMany
   */
  public function actividades(): HasMany
  {
    return $this->hasMany(Actividad::class, 'leccion_id');
  }
}
