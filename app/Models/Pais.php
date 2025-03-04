<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo Pais
 *
 * Representa un país en el sistema.
 *
 * Atributos:
 * - name: Nombre del país.
 *
 * Relaciones:
 * - departamentos: Un país tiene muchos departamentos.
 *
 * @package App\Models
 */
class Pais extends Model
{
  protected $table = 'paises';

  protected $fillable = [
    'name',
  ];

  public $timestamps = true;

  /**
   * Relación: Un país tiene muchos departamentos.
   *
   * @return HasMany
   */
  public function departamentos(): HasMany
  {
    return $this->hasMany(Departamento::class, 'pais_id');
  }
}
