<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo Departamento
 *
 * Representa un departamento dentro de un país.
 *
 * Atributos:
 * - pais_id: Identificador del país al que pertenece el departamento.
 * - name: Nombre del departamento.
 *
 * Relaciones:
 * - pais: El departamento pertenece a un país.
 * - ciudades: El departamento tiene muchas ciudades.
 *
 * @package App\Models
 */
class Departamento extends Model
{
  protected $table = 'departamentos';

  protected $fillable = [
    'pais_id',
    'name',
  ];

  public $timestamps = true;

  /**
   * Relación: El departamento pertenece a un país.
   *
   * @return BelongsTo
   */
  public function pais(): BelongsTo
  {
    return $this->belongsTo(Pais::class, 'pais_id');
  }

  /**
   * Relación: Un departamento tiene muchas ciudades.
   *
   * @return HasMany
   */
  public function ciudades(): HasMany
  {
    return $this->hasMany(Ciudad::class, 'departamento_id');
  }
}
