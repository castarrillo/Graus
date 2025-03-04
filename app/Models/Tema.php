<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo Tema
 *
 * Representa un tema dentro de un curso.
 *
 * Atributos:
 * - curso_id: Identificador del curso al que pertenece el tema.
 * - title: Título del tema.
 * - description: Descripción del tema.
 * - enabled: Estado del tema (activo o deshabilitado).
 *
 * Relaciones:
 * - curso: El tema pertenece a un curso.
 * - lecciones: El tema tiene muchas lecciones.
 *
 * @package App\Models
 */
class Tema extends Model
{
  protected $table = 'temas';

  protected $fillable = [
    'curso_id',
    'title',
    'description',
    'enabled',
  ];

  public $timestamps = true;

  /**
   * Relación: El tema pertenece a un curso.
   *
   * @return BelongsTo
   */
  public function curso(): BelongsTo
  {
    return $this->belongsTo(Curso::class, 'curso_id');
  }

  /**
   * Relación: Un tema tiene muchas lecciones.
   *
   * @return HasMany
   */
  public function lecciones(): HasMany
  {
    return $this->hasMany(Leccion::class, 'tema_id');
  }
}
